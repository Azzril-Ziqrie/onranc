<?php
$c = require __DIR__ . '/config.php';
$v = static function ($f) { return '?v=' . (@filemtime(__DIR__ . '/' . $f) ?: 1); };
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($c['title']) ?></title>
<meta name="description" content="<?= htmlspecialchars($c['description']) ?>">
<meta name="theme-color" content="#ffffff">
<meta property="og:title" content="<?= htmlspecialchars($c['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($c['description']) ?>">
<meta property="og:type" content="website">
<link rel="icon" type="image/png" href="assets/logo-mark.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap">
<link rel="stylesheet" href="assets/style.css<?= $v('assets/style.css') ?>">
</head>
<body>
<main class="page">

  <header class="top">
    <h1 class="wordmark" data-chars aria-label="<?= htmlspecialchars($c['brand']) ?>"><?= htmlspecialchars($c['brand']) ?></h1>
  </header>

  <section class="copy" aria-label="About">
    <p data-reveal>ONRANC is a contemporary practice inspired by architecture and movement, rooted in continuous development and adaptation.</p>
    <p data-reveal>Derived from &ldquo;On Furtherance,&rdquo; the name represents an ongoing pursuit of progress through physical activity, exploration, and the evolution of form and function.</p>
    <p data-reveal>The collection explores evolving expressions of classic and high-performance forms, focusing on how meaningful details, exceptional fabrics, and understated silhouettes can inspire physical pursuits. Through material exploration and technical construction, each piece supports movement while balancing utility and aesthetics.</p>
  </section>

  <section class="notify" aria-label="Launch notification">
    <p class="notify__lead">Be the first to know when the collection launches.</p>
    <form class="notify__form" id="notify" action="subscribe" method="post" novalidate>
      <label class="sr-only" for="email">E-mail address</label>
      <input id="email" name="email" type="email" inputmode="email" autocomplete="email"
             placeholder="( Enter E-Mail )" required>
      <input class="hp" type="text" name="company" tabindex="-1" autocomplete="off" aria-hidden="true">
      <button type="submit" data-scramble>Notify Me</button>
    </form>
    <p class="notify__status" id="status" role="status" aria-live="polite"></p>
  </section>

  <footer class="foot">
    <a class="foot__logo" href="./" aria-label="ONRANC">
      <img src="assets/logo-mark.png" width="993" height="131" alt="OR">
    </a>
    <nav class="foot__links" aria-label="Footer">
      <a href="<?= htmlspecialchars($c['instagram']) ?>" target="_blank" rel="noopener">Instagram</a>
      <span><a href="terms">Terms of Use</a> <a href="privacy">Privacy Policy</a></span>
      <span><?= htmlspecialchars($c['year']) ?> All rights reserved, ONRANC&reg;</span>
      <span>Development by <a href="https://cantum-studio.site" target="_blank" rel="noopener">CantumStudio</a></span>
    </nav>
  </footer>

</main>
<script src="assets/app.js<?= $v('assets/app.js') ?>" defer></script>
</body>
</html>
