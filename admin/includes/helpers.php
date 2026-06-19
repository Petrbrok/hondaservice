<?php

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

function hs_admin_redirect(string $url = 'index.php'): void
{
    header('Location: ' . $url);
    exit;
}

function hs_admin_upsert(PDO $pdo, string $table, string $keyColumn, string $valueColumn, string $key, string $value): void
{
    $sql = "INSERT INTO {$table} ({$keyColumn}, {$valueColumn}) VALUES (?, ?)
            ON DUPLICATE KEY UPDATE {$valueColumn} = VALUES({$valueColumn})";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$key, $value]);
}

function hs_admin_slugify(string $value): string
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'А' => 'a', 'Б' => 'b', 'В' => 'v', 'Г' => 'g', 'Д' => 'd', 'Е' => 'e', 'Ё' => 'e', 'Ж' => 'zh',
        'З' => 'z', 'И' => 'i', 'Й' => 'y', 'К' => 'k', 'Л' => 'l', 'М' => 'm', 'Н' => 'n', 'О' => 'o',
        'П' => 'p', 'Р' => 'r', 'С' => 's', 'Т' => 't', 'У' => 'u', 'Ф' => 'f', 'Х' => 'h', 'Ц' => 'c',
        'Ч' => 'ch', 'Ш' => 'sh', 'Щ' => 'sch', 'Ъ' => '', 'Ы' => 'y', 'Ь' => '', 'Э' => 'e', 'Ю' => 'yu', 'Я' => 'ya',
    ];
    $value = strtolower(strtr(trim($value), $map));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?: '';
    $value = trim($value, '-');

    return $value !== '' ? $value : 'news-' . date('YmdHis');
}

function hs_admin_upload_file(string $field): ?string
{
    if (empty($_FILES[$field]['name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    $extension = strtolower(pathinfo((string) $_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed, true)) {
        throw new RuntimeException('Разрешены только JPG, PNG и WEBP.');
    }

    $info = getimagesize($_FILES[$field]['tmp_name']);
    if (!$info) {
        throw new RuntimeException('Файл не похож на изображение.');
    }

    $dir = __DIR__ . '/../../uploads';
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Не удалось создать папку uploads.');
    }

    $name = date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $target = $dir . '/' . $name;
    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
        throw new RuntimeException('Не удалось сохранить файл.');
    }

    return 'uploads/' . $name;
}

function hs_admin_rows(PDO $pdo, string $sql): array
{
    try {
        return $pdo->query($sql)->fetchAll();
    } catch (Throwable $e) {
        return [];
    }
}

function hs_admin_field_label(string $key): string
{
    $labels = [
        'seo_title' => 'SEO-заголовок страницы',
        'seo_description' => 'SEO-описание страницы',
        'phone_primary' => 'Основной телефон',
        'phone_primary_href' => 'Ссылка для звонка',
        'phone_secondary' => 'Дополнительный телефон',
        'phone_secondary_href' => 'Ссылка для второго телефона',
        'address_short' => 'Короткий адрес',
        'address_full' => 'Полный адрес',
        'schedule_text' => 'Режим работы',
        'open_hour' => 'Час открытия',
        'close_hour' => 'Час закрытия',
        'yandex_reviews_url' => 'Ссылка на отзывы Яндекса',
        'map_embed_url' => 'Карта Яндекса для вставки',
        'route_url' => 'Ссылка на маршрут',
        'footer_text' => 'Текст внизу сайта',
        'hero_eyebrow' => 'Надпись над главным заголовком',
        'hero_title' => 'Главный заголовок',
        'hero_text' => 'Текст под главным заголовком',
        'hero_panel_title' => 'Заголовок карточки справа',
        'hero_panel_subtitle' => 'Подпись карточки справа',
        'rating_value' => 'Рейтинг крупными цифрами',
        'rating_text' => 'Подпись рейтинга',
        'badge_title' => 'Название награды/бейджа',
        'badge_year' => 'Год награды/бейджа',
        'stat_1_value' => 'Статистика 1: крупный текст',
        'stat_1_label' => 'Статистика 1: подпись',
        'stat_2_value' => 'Статистика 2: крупный текст',
        'stat_2_label' => 'Статистика 2: подпись',
        'stat_3_value' => 'Статистика 3: крупный текст',
        'stat_3_label' => 'Статистика 3: подпись',
        'stat_4_value' => 'Статистика 4: крупный текст',
        'stat_4_label' => 'Статистика 4: подпись',
        'services_eyebrow' => 'Услуги: маленькая надпись',
        'services_title' => 'Услуги: заголовок',
        'services_text' => 'Услуги: описание',
        'works_eyebrow' => 'Работы: маленькая надпись',
        'works_title' => 'Работы: заголовок',
        'works_text' => 'Работы: описание',
        'price_eyebrow' => 'Прайс: маленькая надпись',
        'price_title' => 'Прайс: заголовок',
        'price_text' => 'Прайс: описание',
        'reviews_eyebrow' => 'Отзывы: маленькая надпись',
        'reviews_title' => 'Отзывы: заголовок',
        'review_1_text' => 'Отзыв 1: текст',
        'review_1_author' => 'Отзыв 1: имя',
        'review_1_date' => 'Отзыв 1: дата',
        'review_2_text' => 'Отзыв 2: текст',
        'review_2_author' => 'Отзыв 2: имя',
        'review_2_date' => 'Отзыв 2: дата',
        'review_3_text' => 'Отзыв 3: текст',
        'review_3_author' => 'Отзыв 3: имя',
        'review_3_date' => 'Отзыв 3: дата',
        'contact_eyebrow' => 'Контакты: маленькая надпись',
    ];

    return $labels[$key] ?? $key;
}
