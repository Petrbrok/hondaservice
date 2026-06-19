<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/site.php';

$state = hs_load_state();
$settings = $state['settings'];
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= hs_e($settings['seo_description']) ?>">
    <title>Новости | <?= hs_e($settings['seo_title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=13">
  </head>
  <body>
    <header class="site-header">
      <a class="brand" href="index.php#top" aria-label="Хонда-сервис">
        <span class="brand-mark" aria-hidden="true"><img src="assets/honda-header-logo.png" alt=""></span>
        <span><strong>Honda Service</strong><small>Honda & Acura</small></span>
      </a>
      <nav class="main-nav" aria-label="Основная навигация">
        <a href="index.php#services">Услуги</a>
        <a href="index.php#works">Работы</a>
        <a href="index.php#price">Прайс</a>
        <a href="index.php#contacts">Контакты</a>
      </nav>
      <div class="header-actions">
        <a class="header-phone" href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a>
        <a class="btn btn-small" href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
      </div>
    </header>

    <main class="section">
      <div class="container section-head">
        <p class="eyebrow">Новости</p>
        <h1>Новости сервиса</h1>
      </div>
      <div class="container news-grid">
        <?php if (!$state['news']): ?>
          <article class="news-card">
            <h2>Новостей пока нет</h2>
            <p>Раздел готов к публикациям. Добавьте первую запись в админке.</p>
          </article>
        <?php endif; ?>
        <?php foreach ($state['news'] as $item): ?>
          <article class="news-card">
            <?php if (!empty($item['image_path'])): ?>
              <img src="<?= hs_e($item['image_path']) ?>" alt="<?= hs_e($item['title']) ?>">
            <?php endif; ?>
            <span><?= hs_e(hs_format_date($item['published_at'] ?? '')) ?></span>
            <h2><a href="article.php?slug=<?= urlencode((string) $item['slug']) ?>"><?= hs_e($item['title']) ?></a></h2>
            <p><?= hs_e($item['excerpt']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </main>

    <footer class="site-footer">
      <div class="container footer-grid">
        <p><?= hs_e($settings['footer_text']) ?></p>
        <a href="index.php#top">На главную</a>
      </div>
    </footer>
  </body>
</html>
