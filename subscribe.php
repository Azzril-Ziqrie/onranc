<?php
declare(strict_types=1);

$c = require __DIR__ . '/config.php';
$wantsJson = stripos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false;

function respond(bool $ok, string $msg, int $code, bool $json): void
{
    http_response_code($code);
    if ($json) {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode(['ok' => $ok, 'message' => $msg]);
    } else {
        header('Location: ./?' . ($ok ? 'subscribed=1' : 'error=1'));
    }
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    respond(false, 'Method not allowed.', 405, $wantsJson);
}

// Honeypot: bots fill the hidden field. Pretend success.
if (!empty($_POST['company'])) {
    respond(true, "You're on the list.", 200, $wantsJson);
}

$email = strtolower(trim((string)($_POST['email'] ?? '')));
if (strlen($email) > 254 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    respond(false, 'Please enter a valid e-mail.', 422, $wantsJson);
}

// Naive per-IP throttle: max 5 submissions / 10 min, stored in a tiny file.
$ip   = $_SERVER['REMOTE_ADDR'] ?? '0';
$rate = sys_get_temp_dir() . '/onranc_rl_' . md5($ip);
$hits = [];
if (is_file($rate)) {
    $hits = array_filter(array_map('intval', explode(',', (string)file_get_contents($rate))),
        static function ($t) { return $t > time() - 600; });
}
if (count($hits) >= 5) {
    respond(false, 'Too many attempts. Try again later.', 429, $wantsJson);
}
$hits[] = time();
@file_put_contents($rate, implode(',', $hits), LOCK_EX);

$store = $c['store'];
if (!is_dir(dirname($store))) {
    @mkdir(dirname($store), 0750, true);
}

$fh = @fopen($store, 'c+');
if (!$fh) {
    respond(false, 'Could not save. Try again later.', 500, $wantsJson);
}
flock($fh, LOCK_EX);
$exists = false;
while (($row = fgetcsv($fh)) !== false) {
    if (isset($row[1]) && $row[1] === $email) { $exists = true; break; }
}
if (!$exists) {
    fseek($fh, 0, SEEK_END);
    fputcsv($fh, [gmdate('c'), $email]);
}
flock($fh, LOCK_UN);
fclose($fh);

respond(true, "You're on the list. We'll be in touch.", 200, $wantsJson);
