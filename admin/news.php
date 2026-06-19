<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';

hs_admin_require_login();

$pdo = hs_admin_db_or_fail();

function hs_admin_unique_slug(PDO $pdo, string $slug, int $id = 0): string
{
    $base = $slug;
    $counter = 2;

    while (true) {
        $stmt = $pdo->prepare('SELECT id FROM hs_new_news WHERE slug = ? AND id <> ? LIMIT 1');
        $stmt->execute([$slug, $id]);
        if (!$stmt->fetch()) {
            return $slug;
        }

        $slug = $base . '-' . $counter;
        $counter++;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    hs_admin_check_csrf();

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'save_news') {
        $id = (int) ($_POST['id'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));
        $excerpt = trim((string) ($_POST['excerpt'] ?? ''));
        $content = trim((string) ($_POST['content'] ?? ''));
        $seoTitle = trim((string) ($_POST['seo_title'] ?? ''));
        $seoDescription = trim((string) ($_POST['seo_description'] ?? ''));
        $publishedAt = trim((string) ($_POST['published_at'] ?? date('Y-m-d H:i:s')));
        $isActive = (int) ($_POST['is_active'] ?? 1);
        $oldImage = trim((string) ($_POST['old_image_path'] ?? ''));

        if ($title === '' || $content === '') {
            hs_admin_flash('Заполните заголовок и текст новости.');
            hs_admin_redirect('news.php');
        }

        $slug = hs_admin_unique_slug($pdo, $slug !== '' ? hs_admin_slugify($slug) : hs_admin_slugify($title), $id);
        $imagePath = $oldImage;

        try {
            $uploaded = hs_admin_upload_file('image');
            if ($uploaded) {
                $imagePath = $uploaded;
            }
        } catch (Throwable $e) {
            hs_admin_flash($e->getMessage());
            hs_admin_redirect('news.php' . ($id > 0 ? '?id=' . $id : ''));
        }

        if ($seoTitle === '') {
            $seoTitle = $title;
        }
        if ($seoDescription === '') {
            $seoDescription = $excerpt;
        }
        if ($excerpt === '') {
            $plainText = strip_tags($content);
            $excerpt = function_exists('mb_substr') ? mb_substr($plainText, 0, 180, 'UTF-8') : substr($plainText, 0, 180);
        }

        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE hs_new_news SET title = ?, slug = ?, excerpt = ?, content = ?, image_path = ?, seo_title = ?, seo_description = ?, published_at = ?, is_active = ? WHERE id = ?');
            $stmt->execute([$title, $slug, $excerpt, $content, $imagePath ?: null, $seoTitle, $seoDescription, $publishedAt, $isActive, $id]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO hs_new_news (title, slug, excerpt, content, image_path, seo_title, seo_description, published_at, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$title, $slug, $excerpt, $content, $imagePath ?: null, $seoTitle, $seoDescription, $publishedAt, $isActive]);
        }

        hs_admin_flash('Новость сохранена.');
        hs_admin_redirect('news.php');
    }

    if ($action === 'hide_news') {
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $stmt = $pdo->prepare('UPDATE hs_new_news SET is_active = 0 WHERE id = ?');
            $stmt->execute([$id]);
            hs_admin_flash('Новость скрыта.');
        }
        hs_admin_redirect('news.php');
    }
}

$editId = (int) ($_GET['id'] ?? 0);
$edit = null;
if ($editId > 0) {
    $stmt = $pdo->prepare('SELECT * FROM hs_new_news WHERE id = ? LIMIT 1');
    $stmt->execute([$editId]);
    $edit = $stmt->fetch() ?: null;
}

$news = hs_admin_rows($pdo, 'SELECT * FROM hs_new_news ORDER BY published_at DESC, id DESC');
?>
<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Новости</title>
    <link rel="stylesheet" href="admin.css">
  </head>
  <body>
    <main class="admin-wrap">
      <header class="admin-header">
        <div>
          <h1>Новости</h1>
          <p class="admin-muted">Модуль отдельный: новости доступны на `/news.php`, на главной не выводятся.</p>
        </div>
        <div class="admin-actions">
          <a class="admin-btn secondary" href="index.php">Админка</a>
          <a class="admin-btn secondary" href="../news.php" target="_blank">Открыть новости</a>
        </div>
      </header>

      <?php if ($flash = hs_admin_flash()): ?><div class="admin-flash"><?= hs_e($flash) ?></div><?php endif; ?>

      <section class="admin-card">
        <h2><?= $edit ? 'Редактировать новость' : 'Добавить новость' ?></h2>
        <form method="post" action="news.php" enctype="multipart/form-data">
          <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
          <input type="hidden" name="action" value="save_news">
          <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
          <input type="hidden" name="old_image_path" value="<?= hs_e($edit['image_path'] ?? '') ?>">

          <div class="admin-grid">
            <div class="admin-row"><label>Заголовок</label><input name="title" value="<?= hs_e($edit['title'] ?? '') ?>" required></div>
            <div class="admin-row"><label>Slug</label><input name="slug" value="<?= hs_e($edit['slug'] ?? '') ?>" placeholder="Можно оставить пустым"></div>
            <div class="admin-row"><label>Дата публикации</label><input name="published_at" value="<?= hs_e($edit['published_at'] ?? date('Y-m-d H:i:s')) ?>"></div>
            <div class="admin-row"><label>Статус</label><select name="is_active"><option value="1" <?= (($edit['is_active'] ?? 1) ? 'selected' : '') ?>>активна</option><option value="0" <?= (isset($edit['is_active']) && !$edit['is_active'] ? 'selected' : '') ?>>скрыта</option></select></div>
            <div class="admin-row"><label>SEO title</label><input name="seo_title" value="<?= hs_e($edit['seo_title'] ?? '') ?>"></div>
            <div class="admin-row"><label>SEO description</label><textarea name="seo_description" rows="3"><?= hs_e($edit['seo_description'] ?? '') ?></textarea></div>
            <div class="admin-row"><label>Фото</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></div>
            <div class="admin-row">
              <label>Текущее фото</label>
              <?php if (!empty($edit['image_path'])): ?>
                <img class="admin-image" src="../<?= hs_e($edit['image_path']) ?>" alt="">
              <?php else: ?>
                <span class="admin-muted">Нет фото</span>
              <?php endif; ?>
            </div>
          </div>

          <div class="admin-row"><label>Краткое описание</label><textarea name="excerpt" rows="3"><?= hs_e($edit['excerpt'] ?? '') ?></textarea></div>
          <div class="admin-row"><label>Текст статьи</label><textarea name="content" rows="12" required><?= hs_e($edit['content'] ?? '') ?></textarea></div>
          <button class="admin-btn" type="submit">Сохранить новость</button>
          <?php if ($edit): ?><a class="admin-btn secondary" href="news.php">Отменить редактирование</a><?php endif; ?>
        </form>
      </section>

      <section class="admin-card">
        <h2>Список новостей</h2>
        <table class="admin-table">
          <thead><tr><th>Заголовок</th><th>Дата</th><th>Статус</th><th></th></tr></thead>
          <tbody>
            <?php foreach ($news as $item): ?>
              <tr>
                <td data-label="Заголовок"><?= hs_e($item['title']) ?><br><span class="admin-muted">/article.php?slug=<?= hs_e($item['slug']) ?></span></td>
                <td data-label="Дата"><?= hs_e($item['published_at']) ?></td>
                <td data-label="Статус"><?= $item['is_active'] ? 'активна' : 'скрыта' ?></td>
                <td data-label="Действие" class="admin-actions">
                  <a class="admin-btn secondary" href="news.php?id=<?= (int) $item['id'] ?>">Редактировать</a>
                  <?php if ($item['is_active']): ?>
                    <form method="post" action="news.php">
                      <input type="hidden" name="csrf" value="<?= hs_e(hs_admin_csrf()) ?>">
                      <input type="hidden" name="action" value="hide_news">
                      <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
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
