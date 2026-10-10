<?php
$c = require __DIR__ . '/config.php';
$pages = [
    'terms'   => ['Terms of Use', 'Terms of use will be published here before launch.'],
    'privacy' => ['Privacy Policy', 'ONRANC collects only the e-mail address you submit to be notified about the collection launch. It is used solely for that purpose and is never sold or shared.'],
];
$p = $_GET['p'] ?? 'terms';
if (!isset($pages[$p])) { $p = 'terms'; }
[$title, $body] = $pages[$p];
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title><?= htmlspecialchars($title) ?> — ONRANC</title>
<link rel="icon" type="image/png" href="assets/logo-mark.png">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400&display=swap">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="page" style="grid-template-rows:auto auto 1fr">
  <header class="top"><a class="wordmark" href="./">ONRANC</a></header>
  <section class="copy">
    <p style="text-transform:uppercase"><?= htmlspecialchars($title) ?></p>
    <p><?= htmlspecialchars($body) ?></p>
  </section>
</main>
</body>
</html>
