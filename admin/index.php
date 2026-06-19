<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

if (!hs_admin_is_logged_in()):
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход в админку</title>
    <link rel="stylesheet" href="admin.css">
  </head>
  <body>
    <main class="admin-wrap">
      <section class="admin-card" style="max-width:420px;margin:80px auto;">
        <h1>Вход</h1>
        <?php if ($flash = hs_admin_flash()): ?><div class="admin-flash"><?= hs_e($flash) ?></div><?php endif; ?>
        <form method="post" action="login.php">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <div class="admin-row">
            <label>Логин</label>
            <input name="username" required autocomplete="username">
          </div>
          <div class="admin-row">
            <label>Пароль</label>
            <input name="password" type="password" required autocomplete="current-password">
          </div>
          <button class="admin-btn" type="submit">Войти</button>
        </form>
      </section>
    </main>
  </body>
</html>
<?php
exit;
endif;

$pdo = hs_admin_db_or_fail();
$state = hs_load_state();
$defaults = hs_default_state();
$settings = $state['settings'];
$content = $state['content'];
$services = hs_admin_rows($pdo, 'SELECT * FROM hs_new_services ORDER BY sort_order, id');
$prices = hs_admin_rows($pdo, 'SELECT * FROM hs_new_prices ORDER BY sort_order, id');
$images = hs_admin_rows($pdo, 'SELECT * FROM hs_new_images ORDER BY image_key, sort_order, id DESC');
$activeWorkImages = [];
foreach ($images as $image) {
    if ($image['image_key'] === 'work' && (int) $image['is_active'] === 1 && $image['title'] !== '') {
        $activeWorkImages[$image['title']] = $image;
    }
}
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Админка Honda Service</title>
    <link rel="stylesheet" href="admin.css">
  </head>
  <body>
    <main class="admin-wrap">
      <header class="admin-header">
        <div>
          <h1>Админка Honda Service</h1>
          <p class="admin-muted">Публичный сайт работает на дефолтах, даже если часть записей в базе отсутствует.</p>
        </div>
        <div class="admin-actions">
          <a class="admin-btn secondary" href="../index.php" target="_blank">Открыть сайт</a>
          <a class="admin-btn secondary" href="news.php">Новости</a>
          <a class="admin-btn secondary" href="logout.php">Выйти</a>
        </div>
      </header>

      <?php if ($flash = hs_admin_flash()): ?><div class="admin-flash"><?= hs_e($flash) ?></div><?php endif; ?>

      <section class="admin-card">
        <h2>Первичное заполнение</h2>
        <p class="admin-muted">Кнопка переносит дефолтные тексты, услуги и цены в новые таблицы `hs_new_*`. Старые таблицы WordPress не используются.</p>
        <form method="post" action="save.php">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="action" value="seed_defaults">
          <button class="admin-btn secondary" type="submit">Заполнить базу дефолтами</button>
        </form>
      </section>

      <section class="admin-card">
        <h2>SEO и контакты</h2>
        <form method="post" action="save.php">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="action" value="save_settings_content">
          <div class="admin-grid">
            <?php foreach ($defaults['settings'] as $key => $defaultValue): ?>
              <div class="admin-row">
                <label><?= hs_e(hs_admin_field_label($key)) ?></label>
                <?php if (strlen((string) $defaultValue) > 70): ?>
                  <textarea name="settings[<?= hs_e($key) ?>]" rows="3"><?= hs_e($settings[$key] ?? $defaultValue) ?></textarea>
                <?php else: ?>
                  <input name="settings[<?= hs_e($key) ?>]" value="<?= hs_e($settings[$key] ?? $defaultValue) ?>">
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>

          <h2>Тексты сайта</h2>
          <div class="admin-grid">
            <?php foreach ($defaults['content'] as $key => $defaultValue): ?>
              <div class="admin-row">
                <label><?= hs_e(hs_admin_field_label($key)) ?></label>
                <textarea name="content[<?= hs_e($key) ?>]" rows="<?= $key === 'hero_title' ? 5 : 3 ?>"><?= hs_e($content[$key] ?? $defaultValue) ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <button class="admin-btn" type="submit">Сохранить SEO, контакты и тексты</button>
        </form>
      </section>

      <section class="admin-card">
        <h2>Услуги</h2>
        <table class="admin-table">
          <thead><tr><th>Порядок</th><th>Название</th><th>Описание</th><th>Статус</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($services as $service): ?>
              <?php $formId = 'service-form-' . (int) $service['id']; ?>
              <tr>
                <td data-label="Порядок">
                  <input form="<?= hs_e($formId) ?>" name="sort_order" value="<?= (int) $service['sort_order'] ?>">
                </td>
                <td data-label="Название"><input form="<?= hs_e($formId) ?>" name="title" value="<?= hs_e($service['title']) ?>"></td>
                <td data-label="Описание"><textarea form="<?= hs_e($formId) ?>" name="description" rows="2"><?= hs_e($service['description']) ?></textarea></td>
                <td data-label="Статус"><select form="<?= hs_e($formId) ?>" name="is_active"><option value="1" <?= $service['is_active'] ? 'selected' : '' ?>>активна</option><option value="0" <?= !$service['is_active'] ? 'selected' : '' ?>>скрыта</option></select></td>
                <td data-label="Действие">
                  <form id="<?= hs_e($formId) ?>" method="post" action="save.php">
                    <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
                    <input type="hidden" name="action" value="save_service">
                    <input type="hidden" name="id" value="<?= (int) $service['id'] ?>">
                    <button class="admin-btn secondary" type="submit">Сохранить</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <h3>Добавить услугу</h3>
        <form method="post" action="save.php" class="admin-grid">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="action" value="save_service">
          <div class="admin-row"><label>Порядок</label><input name="sort_order" value="100"></div>
          <div class="admin-row"><label>Название</label><input name="title" required></div>
          <div class="admin-row"><label>Описание</label><textarea name="description" rows="3" required></textarea></div>
          <div class="admin-row"><label>&nbsp;</label><button class="admin-btn" type="submit">Добавить</button></div>
        </form>
      </section>

      <section class="admin-card">
        <h2>Цены</h2>
        <table class="admin-table">
          <thead><tr><th>Порядок</th><th>Работа</th><th>Цена</th><th>Статус</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($prices as $price): ?>
              <?php $formId = 'price-form-' . (int) $price['id']; ?>
              <tr>
                <td data-label="Порядок">
                  <input form="<?= hs_e($formId) ?>" name="sort_order" value="<?= (int) $price['sort_order'] ?>">
                </td>
                <td data-label="Работа"><input form="<?= hs_e($formId) ?>" name="title" value="<?= hs_e($price['title']) ?>"></td>
                <td data-label="Цена"><input form="<?= hs_e($formId) ?>" name="price" value="<?= hs_e($price['price']) ?>"></td>
                <td data-label="Статус"><select form="<?= hs_e($formId) ?>" name="is_active"><option value="1" <?= $price['is_active'] ? 'selected' : '' ?>>активна</option><option value="0" <?= !$price['is_active'] ? 'selected' : '' ?>>скрыта</option></select></td>
                <td data-label="Действие">
                  <form id="<?= hs_e($formId) ?>" method="post" action="save.php">
                    <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
                    <input type="hidden" name="action" value="save_price">
                    <input type="hidden" name="id" value="<?= (int) $price['id'] ?>">
                    <button class="admin-btn secondary" type="submit">Сохранить</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <h3>Добавить цену</h3>
        <form method="post" action="save.php" class="admin-grid">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="action" value="save_price">
          <div class="admin-row"><label>Порядок</label><input name="sort_order" value="100"></div>
          <div class="admin-row"><label>Работа</label><input name="title" required></div>
          <div class="admin-row"><label>Цена</label><input name="price" required></div>
          <div class="admin-row"><label>&nbsp;</label><button class="admin-btn" type="submit">Добавить</button></div>
        </form>
      </section>

      <section class="admin-card">
        <h2>Фотографии</h2>
        <h3>Главное фото первого экрана</h3>
        <form method="post" action="upload.php" enctype="multipart/form-data" class="admin-grid">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="image_key" value="hero">
          <div class="admin-row"><label>Файл</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required></div>
          <div class="admin-row"><label>Название фото</label><input name="title" value="Главное фото"></div>
          <div class="admin-row"><label>Описание для поиска</label><input name="alt" value="Автосервис Honda"></div>
          <div class="admin-row"><label>Порядок</label><input name="sort_order" value="10"></div>
          <div class="admin-row"><label>&nbsp;</label><button class="admin-btn" type="submit">Загрузить</button></div>
        </form>

        <h3>Фото для карточек “Работы сервиса”</h3>
        <p class="admin-muted">У каждой карточки отдельная загрузка. Новое фото заменит старое для этой же карточки, старый файл физически не удаляется.</p>
        <div class="work-upload-grid">
          <?php foreach ($defaults['works'] as $index => $work): ?>
            <?php $title = (string) $work['title']; ?>
            <?php $activeImage = $activeWorkImages[$title] ?? null; ?>
            <form class="work-upload-card" method="post" action="upload.php" enctype="multipart/form-data">
              <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
              <input type="hidden" name="image_key" value="work">
              <input type="hidden" name="title" value="<?= hs_e($title) ?>">
              <input type="hidden" name="alt" value="<?= hs_e($title) ?>">
              <input type="hidden" name="sort_order" value="<?= ($index + 1) * 10 ?>">
              <?php if ($activeImage): ?>
                <img class="admin-image wide" src="../<?= hs_e($activeImage['file_path']) ?>" alt="">
              <?php else: ?>
                <div class="work-upload-empty">Фото не загружено</div>
              <?php endif; ?>
              <strong><?= hs_e($title) ?></strong>
              <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required>
              <button class="admin-btn secondary" type="submit">Загрузить фото</button>
            </form>
          <?php endforeach; ?>
        </div>

        <h3>Все загруженные изображения</h3>
        <table class="admin-table">
          <thead><tr><th>Фото</th><th>Тип</th><th>Название</th><th>Статус</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($images as $image): ?>
              <tr>
                <td data-label="Фото"><img class="admin-image" src="../<?= hs_e($image['file_path']) ?>" alt=""></td>
                <td data-label="Тип"><?= $image['image_key'] === 'hero' ? 'Главное фото' : 'Работа сервиса' ?></td>
                <td data-label="Название"><?= hs_e($image['title']) ?><br><span class="admin-muted"><?= hs_e($image['file_path']) ?></span></td>
                <td data-label="Статус"><?= $image['is_active'] ? 'активна' : 'скрыта' ?></td>
                <td data-label="Действие">
                  <?php if ($image['is_active']): ?>
                    <form method="post" action="hide-image.php">
                      <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
                      <input type="hidden" name="id" value="<?= (int) $image['id'] ?>">
                      <button class="admin-btn secondary" type="submit">Скрыть</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </section>
    </main>
  </body>
</html>
