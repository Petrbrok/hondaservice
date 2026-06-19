<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/site.php';

$state = hs_load_state();
$settings = $state['settings'];
$content = $state['content'];
$heroImage = $state['hero_image'];
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= hs_e($settings['seo_description']) ?>">
    <title><?= hs_e($settings['seo_title']) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css?v=13">
  </head>
  <body>
    <header class="site-header" data-header>
      <a class="brand" href="#top" aria-label="Хонда-сервис">
        <span class="brand-mark" aria-hidden="true">
          <img src="assets/honda-header-logo.png" alt="">
        </span>
        <span>
          <strong>Honda Service</strong>
          <small>Honda & Acura</small>
        </span>
      </a>

      <nav class="main-nav" data-nav aria-label="Основная навигация">
        <a href="#services">Услуги</a>
        <a href="#works">Работы</a>
        <a href="#price">Прайс</a>
        <a href="#reviews">Отзывы</a>
        <a href="#contacts">Контакты</a>
        <a href="news.php">Новости</a>
      </nav>

      <div id="openStatusBadge" class="open-status-badge header-status" data-open-status-badge aria-live="polite"></div>

      <div class="header-actions">
        <a class="header-phone" href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a>
        <a class="btn btn-small" href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
        <button class="burger" type="button" data-burger aria-expanded="false" aria-controls="mobile-menu" aria-label="Открыть меню">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>

      <div class="mobile-menu" id="mobile-menu" data-mobile-menu>
        <a href="#services">Услуги</a>
        <a href="#works">Работы</a>
        <a href="#price">Прайс</a>
        <a href="#reviews">Отзывы</a>
        <a href="#contacts">Контакты</a>
        <a href="news.php">Новости</a>
        <a class="mobile-phone-link" href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a>
        <?php if (!empty($settings['phone_secondary'])): ?>
          <a class="mobile-phone-link" href="<?= hs_e($settings['phone_secondary_href']) ?>"><?= hs_e($settings['phone_secondary']) ?></a>
        <?php endif; ?>
        <a class="btn" href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
      </div>
    </header>

    <main id="top">
      <section class="hero section" style="--hero-image: url('<?= hs_e($heroImage) ?>');">
        <div class="hero-grid container">
          <div class="hero-copy">
            <p class="eyebrow"><?= hs_e($content['hero_eyebrow']) ?></p>
            <h1>
              <?php foreach (hs_lines($content['hero_title']) as $line): ?>
                <span><?= hs_e($line) ?></span>
              <?php endforeach; ?>
            </h1>
            <p class="hero-text"><?= hs_e($content['hero_text']) ?></p>
            <div class="hero-actions">
              <a class="btn" href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
            </div>
          </div>

          <aside class="hero-panel" aria-label="Ключевые данные сервиса">
            <div class="hero-logo-lockup">
              <span class="service-logo" aria-hidden="true">
                <img src="assets/honda-header-logo.png" alt="">
              </span>
              <span>
                <strong><?= hs_e($content['hero_panel_title']) ?></strong>
                <small><?= hs_e($content['hero_panel_subtitle']) ?></small>
              </span>
            </div>
            <div class="trust-badges" aria-label="Доверие и рейтинг">
              <div class="trust-badge">
                <span class="rating-number"><?= hs_e($content['rating_value']) ?></span>
                <span><?= hs_e($content['rating_text']) ?></span>
              </div>
              <div class="trust-badge">
                <span><?= hs_e($content['badge_title']) ?></span>
                <strong><?= hs_e($content['badge_year']) ?></strong>
              </div>
            </div>
            <dl>
              <div>
                <dt>Режим</dt>
                <dd><?= hs_e($settings['schedule_text']) ?></dd>
              </div>
              <div>
                <dt>Адрес</dt>
                <dd><?= hs_e($settings['address_full']) ?></dd>
              </div>
              <div>
                <dt>Телефон</dt>
                <dd><a href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a></dd>
              </div>
            </dl>
          </aside>
        </div>
      </section>

      <section class="section stats-band">
        <div class="container stats-grid">
          <?php foreach ($state['stats'] as $stat): ?>
            <div><strong><?= hs_e($stat['value']) ?></strong><span><?= hs_e($stat['label']) ?></span></div>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section" id="services">
        <div class="container section-head">
          <p class="eyebrow"><?= hs_e($content['services_eyebrow']) ?></p>
          <h2><?= hs_e($content['services_title']) ?></h2>
          <p><?= hs_e($content['services_text']) ?></p>
        </div>
        <div class="container feature-grid">
          <?php foreach ($state['services'] as $index => $service): ?>
            <article>
              <span><?= hs_e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
              <h3><?= hs_e($service['title']) ?></h3>
              <p><?= hs_e($service['description']) ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section works-section" id="works">
        <div class="container split-head">
          <div>
            <p class="eyebrow"><?= hs_e($content['works_eyebrow']) ?></p>
            <h2><?= hs_e($content['works_title']) ?></h2>
          </div>
          <p><?= hs_e($content['works_text']) ?></p>
        </div>
        <div class="container works-grid" data-works-grid>
          <?php foreach ($state['works'] as $index => $work): ?>
            <?php $extra = $index >= 8 ? ' extra-work' : ''; ?>
            <?php $hasImage = !empty($work['file_path']); ?>
            <article class="work-card<?= $extra ?><?= $hasImage ? ' has-image' : '' ?>">
              <?php if ($hasImage): ?>
                <img src="<?= hs_e($work['file_path']) ?>" alt="<?= hs_e($work['alt'] ?? $work['title']) ?>">
              <?php endif; ?>
              <span><?= hs_e(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)) ?></span>
              <strong><?= hs_e($work['title']) ?></strong>
            </article>
          <?php endforeach; ?>
        </div>
        <?php if (count($state['works']) > 8): ?>
          <div class="container center">
            <button class="btn btn-ghost" type="button" data-show-works>Показать ещё</button>
          </div>
        <?php endif; ?>
      </section>

      <section class="section price-section" id="price">
        <div class="container split-head">
          <div>
            <p class="eyebrow"><?= hs_e($content['price_eyebrow']) ?></p>
            <h2><?= hs_e($content['price_title']) ?></h2>
          </div>
          <p><?= hs_e($content['price_text']) ?></p>
        </div>

        <div class="container price-list">
          <?php foreach ($state['prices'] as $index => $price): ?>
            <div class="price-item<?= $index >= 8 ? ' extra-price' : '' ?>"><span><?= hs_e($price['title']) ?></span><strong><?= hs_e($price['price']) ?></strong></div>
          <?php endforeach; ?>
        </div>

        <?php if (count($state['prices']) > 8): ?>
          <div class="container center">
            <button class="btn btn-ghost" type="button" data-show-prices>Показать ещё</button>
          </div>
        <?php endif; ?>
      </section>

      <section class="section" id="reviews">
        <div class="container split-head">
          <div>
            <p class="eyebrow"><?= hs_e($content['reviews_eyebrow']) ?></p>
            <h2><?= hs_e($content['reviews_title']) ?></h2>
          </div>
        </div>
        <div class="container reviews-grid">
          <?php foreach ($state['reviews'] as $review): ?>
            <article>
              <p>“<?= hs_e($review['text']) ?>”</p>
              <strong><?= hs_e($review['author']) ?></strong>
              <span><?= hs_e($review['date']) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section contacts" id="contacts">
        <div class="container contacts-grid">
          <div>
            <p class="eyebrow"><?= hs_e($content['contact_eyebrow']) ?></p>
            <h2><?= hs_e($settings['address_short']) ?></h2>
            <p><?= hs_e($settings['schedule_text']) ?></p>
            <div class="contact-links">
              <a href="<?= hs_e($settings['phone_primary_href']) ?>"><?= hs_e($settings['phone_primary']) ?></a>
              <?php if (!empty($settings['phone_secondary'])): ?>
                <a href="<?= hs_e($settings['phone_secondary_href']) ?>"><?= hs_e($settings['phone_secondary']) ?></a>
              <?php endif; ?>
            </div>
          </div>
          <div class="map-frame" aria-label="Карта сервиса">
            <iframe title="Хонда-сервис на Яндекс.Картах" src="<?= hs_e($settings['map_embed_url']) ?>" loading="lazy"></iframe>
            <div class="map-overlay">
              <span>Открыть маршрут</span>
              <a href="<?= hs_e($settings['route_url']) ?>" target="_blank" rel="noreferrer">Санкт-Петербург → <?= hs_e($settings['address_short']) ?></a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <div class="container footer-grid">
        <p><?= hs_e($settings['footer_text']) ?></p>
        <a href="#top">Наверх</a>
      </div>
    </footer>

    <div class="mobile-cta" aria-label="Быстрые действия">
      <a href="<?= hs_e($settings['phone_primary_href']) ?>">Позвонить</a>
    </div>

    <script>
      window.HS_SCHEDULE = {
        openHour: <?= (int) $settings['open_hour'] ?>,
        openMinute: 0,
        closeMinute: 0,
        closeHour: <?= (int) $settings['close_hour'] ?>
      };
    </script>
    <script src="script.js?v=9"></script>
  </body>
</html>
