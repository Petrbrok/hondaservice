<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/site.php';

$state = hs_load_state();
$settings = $state['settings'];
$slug = trim((string) ($_GET['slug'] ?? ''));
$article = $slug !== '' ? hs_get_news_item($slug) : null;
$articleTitle = $article ? (($article['seo_title'] ?? '') ?: $article['title']) : 'Новость не найдена';
$articleDescription = $article ? (($article['seo_description'] ?? '') ?: ($article['excerpt'] ?? $settings['seo_description'])) : $settings['seo_description'];

http_response_code($article ? 200 : 404);
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= hs_e($articleDescription) ?>">
    <title><?= hs_e($articleTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=12">
  </head>
  <body>
    <header class="site-header">
      <a class="brand" href="index.php#top" aria-label="Хонда-сервис">
        <span class="brand-mark" aria-hidden="true"><img src="assets/honda-header-logo.png" alt=""></span>
        <span><strong>Honda Service</strong><small>Honda & Acura</small></span>
      </a>
      <div class="header-actions">
        <a class="header-phone" href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a>
        <a class="btn btn-small" href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
      </div>
    </header>

    <main class="section">
      <article class="container article-page">
        <?php if (!$article): ?>
          <p class="eyebrow">404</p>
          <h1>Новость не найдена</h1>
          <p><a href="news.php">Вернуться к новостям</a></p>
        <?php else: ?>
          <p class="eyebrow"><?= hs_e(hs_format_date($article['published_at'])) ?></p>
          <h1><?= hs_e($article['title']) ?></h1>
          <?php if (!empty($article['image_path'])): ?>
            <img src="<?= hs_e($article['image_path']) ?>" alt="<?= hs_e($article['title']) ?>">
          <?php endif; ?>
          <?= nl2br(hs_e($article['content'])) ?>
        <?php endif; ?>
      </article>
    </main>

    <footer class="site-footer">
      <div class="container footer-grid">
        <p><?= hs_e($settings['footer_text']) ?></p>
        <a href="news.php">Новости</a>
      </div>
    </footer>
  </body>
</html>
