<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

function hs_e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function hs_default_state(): array
{
    return [
        'settings' => [
            'seo_title' => 'Хонда-сервис | Ремонт Honda & Acura в Санкт-Петербурге',
            'seo_description' => 'Хонда-сервис в Санкт-Петербурге: ремонт и обслуживание Honda & Acura, диагностика, ТО, запчасти в наличии. Рейтинг 5.0, 562 оценки.',
            'phone_primary' => '+7 (812) 642-60-60',
            'phone_primary_href' => 'tel:+78126426060',
            'phone_secondary' => '+7 (812) 715-97-56',
            'phone_secondary_href' => 'tel:+78127159756',
            'address_short' => 'Софийская ул., 8, корп. 1, стр. 1',
            'address_full' => 'Санкт-Петербург, Софийская ул., 8, корп. 1, стр. 1, этаж 1',
            'schedule_text' => 'Ежедневно с 10:00 до 20:00',
            'open_hour' => '10',
            'close_hour' => '20',
            'yandex_reviews_url' => 'https://yandex.com/maps/org/khonda_servis/1459326111/reviews/?ll=30.392386%2C59.882460&z=16',
            'map_embed_url' => 'https://yandex.ru/map-widget/v1/?ll=30.392386%2C59.882460&mode=search&oid=1459326111&ol=biz&z=16',
            'route_url' => 'https://yandex.ru/maps/?rtext=~59.882460%2C30.392386&rtt=auto',
            'footer_text' => '© 2026 Хонда-сервис. Ремонт и обслуживание Honda & Acura.',
        ],
        'content' => [
            'hero_eyebrow' => 'Ремонт Honda & Acura • Санкт-Петербург',
            'hero_title' => "Хонда-сервис\nдля тех, кто\nне любит\nсюрпризы после\nдиагностики.",
            'hero_text' => 'ТО, ремонт, диагностика и запчасти для Honda & Acura. Узкая специализация, понятные цены, запись по телефону.',
            'hero_panel_title' => 'Honda & Acura профиль',
            'hero_panel_subtitle' => 'Узкая специализация сервиса',
            'rating_value' => '5,0',
            'rating_text' => '562 оценки',
            'badge_title' => 'Хорошее место',
            'badge_year' => '2026',
            'stat_1_value' => '5,0',
            'stat_1_label' => '562 оценки на Яндекс Картах',
            'stat_2_value' => 'Смета',
            'stat_2_label' => 'согласуем до ремонта',
            'stat_3_value' => '20+',
            'stat_3_label' => 'популярных работ',
            'stat_4_value' => 'Honda & Acura',
            'stat_4_label' => 'основной профиль',
            'services_eyebrow' => 'Что делаем',
            'services_title' => 'Обслуживание, ремонт и диагностика Honda & Acura',
            'services_text' => 'Профильный сервис для планового обслуживания, диагностики и ремонта. Подбираем работы по состоянию автомобиля и согласуем стоимость заранее.',
            'works_eyebrow' => 'Наши работы',
            'works_title' => 'Работы сервиса',
            'works_text' => 'Ремонт, обслуживание и диагностика автомобилей в профильном сервисе.',
            'price_eyebrow' => 'Прайс',
            'price_title' => 'Популярные работы',
            'price_text' => 'Базовые цены на востребованные работы. Итоговая стоимость зависит от модели, состояния автомобиля и выбранных запчастей.',
            'reviews_eyebrow' => 'Отзывы',
            'reviews_title' => 'Клиенты отмечают компетентность, наличие запчастей и честный подход',
            'review_1_text' => 'Полный спектр работ, адекватный ценник и компетентные специалисты. На месте всегда можно приобрести необходимые запчасти.',
            'review_1_author' => 'Владимир Дмитриевич',
            'review_1_date' => '15 декабря 2025',
            'review_2_text' => 'В семье три Хонды, и все на ТО и ремонт приезжают только сюда. Не накручивают ненужных услуг.',
            'review_2_author' => 'Антон Канушин',
            'review_2_date' => '23 мая 2025',
            'review_3_text' => 'Приехали из Москвы в отпуск. Сотрудники вошли в положение и оперативно исправили проблему.',
            'review_3_author' => 'Александр С.',
            'review_3_date' => '20 июля 2025',
            'contact_eyebrow' => 'Контакты',
        ],
        'stats' => [
            ['value' => '5,0', 'label' => '562 оценки на Яндекс Картах'],
            ['value' => 'Смета', 'label' => 'согласуем до ремонта'],
            ['value' => '20+', 'label' => 'популярных работ'],
            ['value' => 'Honda & Acura', 'label' => 'основной профиль'],
        ],
        'services' => [
            ['title' => 'ТО и расходники', 'description' => 'Масло, фильтры, антифриз, свечи, ремни, регламентные работы.'],
            ['title' => 'Ходовая и рулевое', 'description' => 'Подвеска, стойки стабилизатора, шаровые, рулевые тяги и наконечники.'],
            ['title' => 'Двигатель и АКПП', 'description' => 'ГРМ, клапаны, термостат, диагностика, постгарантийный ремонт.'],
            ['title' => 'Запчасти на месте', 'description' => 'Клиентам не нужно искать расходники отдельно: всё ключевое можно подобрать сразу.'],
        ],
        'works' => [
            ['title' => 'Ремзона'],
            ['title' => 'Диагностика'],
            ['title' => 'ТО'],
            ['title' => 'Ходовая'],
            ['title' => 'Двигатель'],
            ['title' => 'Запчасти'],
            ['title' => 'Приёмка'],
            ['title' => 'Инструмент'],
            ['title' => 'АКПП'],
            ['title' => 'Тормоза'],
            ['title' => 'Электрика'],
            ['title' => 'Готовая машина'],
        ],
        'prices' => [
            ['title' => 'Компьютерная диагностика автомобиля', 'price' => 'от 1 200 ₽'],
            ['title' => 'Диагностика подвески авто', 'price' => 'от 1 200 ₽'],
            ['title' => 'Замена масла АКПП', 'price' => 'от 1 500 ₽'],
            ['title' => 'Замена свечей', 'price' => 'от 1 500 ₽'],
            ['title' => 'Замена антифриза', 'price' => 'от 2 000 ₽'],
            ['title' => 'Замена ремня генератора', 'price' => 'от 2 000 ₽'],
            ['title' => 'Замена тормозных дисков и колодок', 'price' => 'от 2 500 ₽'],
            ['title' => 'Техническое обслуживание автомобилей', 'price' => 'от 2 500 ₽'],
            ['title' => 'Замена ремня ГРМ', 'price' => 'от 8 000 ₽'],
            ['title' => 'Регулировка клапанов ДВС', 'price' => 'от 2 800 ₽'],
            ['title' => 'Замена топливного фильтра', 'price' => 'от 3 000 ₽'],
            ['title' => 'Замена фланцев глушителя', 'price' => 'от 8 000 ₽'],
        ],
        'reviews' => [
            ['text' => 'Полный спектр работ, адекватный ценник и компетентные специалисты. На месте всегда можно приобрести необходимые запчасти.', 'author' => 'Владимир Дмитриевич', 'date' => '15 декабря 2025'],
            ['text' => 'В семье три Хонды, и все на ТО и ремонт приезжают только сюда. Не накручивают ненужных услуг.', 'author' => 'Антон Канушин', 'date' => '23 мая 2025'],
            ['text' => 'Приехали из Москвы в отпуск. Сотрудники вошли в положение и оперативно исправили проблему.', 'author' => 'Александр С.', 'date' => '20 июля 2025'],
        ],
        'hero_image' => 'assets/hero-workshop.jpg',
        'news' => [],
    ];
}

function hs_fetch_pairs(string $table, string $keyColumn, string $valueColumn): array
{
    $pdo = hs_db();
    if (!$pdo) {
        return [];
    }

    try {
        $rows = $pdo->query("SELECT {$keyColumn}, {$valueColumn} FROM {$table}")->fetchAll();
    } catch (Throwable $e) {
        return [];
    }

    $result = [];
    foreach ($rows as $row) {
        $result[(string) $row[$keyColumn]] = (string) $row[$valueColumn];
    }

    return $result;
}

function hs_load_state(): array
{
    $state = hs_default_state();
    $pdo = hs_db();

    if (!$pdo) {
        return $state;
    }

    $state['settings'] = array_replace($state['settings'], hs_fetch_pairs('hs_new_settings', 'setting_key', 'setting_value'));
    $state['content'] = array_replace($state['content'], hs_fetch_pairs('hs_new_site_content', 'content_key', 'content_value'));
    $state['stats'] = [
        ['value' => $state['content']['stat_1_value'], 'label' => $state['content']['stat_1_label']],
        ['value' => $state['content']['stat_2_value'], 'label' => $state['content']['stat_2_label']],
        ['value' => $state['content']['stat_3_value'], 'label' => $state['content']['stat_3_label']],
        ['value' => $state['content']['stat_4_value'], 'label' => $state['content']['stat_4_label']],
    ];
    $state['reviews'] = [
        ['text' => $state['content']['review_1_text'], 'author' => $state['content']['review_1_author'], 'date' => $state['content']['review_1_date']],
        ['text' => $state['content']['review_2_text'], 'author' => $state['content']['review_2_author'], 'date' => $state['content']['review_2_date']],
        ['text' => $state['content']['review_3_text'], 'author' => $state['content']['review_3_author'], 'date' => $state['content']['review_3_date']],
    ];

    try {
        $services = $pdo->query('SELECT title, description FROM hs_new_services WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
        if ($services) {
            $state['services'] = $services;
        }
    } catch (Throwable $e) {
    }

    try {
        $prices = $pdo->query('SELECT title, price FROM hs_new_prices WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
        if ($prices) {
            $state['prices'] = $prices;
        }
    } catch (Throwable $e) {
    }

    try {
        $hero = $pdo->query("SELECT file_path FROM hs_new_images WHERE image_key = 'hero' AND is_active = 1 ORDER BY id DESC LIMIT 1")->fetch();
        if ($hero && !empty($hero['file_path'])) {
            $state['hero_image'] = $hero['file_path'];
        }
    } catch (Throwable $e) {
    }

    try {
        $works = $pdo->query("SELECT title, alt, file_path FROM hs_new_images WHERE image_key = 'work' AND is_active = 1 ORDER BY sort_order, id")->fetchAll();
        if ($works) {
            $imagesByTitle = [];
            $extraWorks = [];
            foreach ($works as $work) {
                $title = trim((string) ($work['title'] ?? ''));
                if ($title !== '') {
                    $imagesByTitle[$title] = $work;
                } else {
                    $extraWorks[] = $work;
                }
            }

            foreach ($state['works'] as $index => $defaultWork) {
                $title = (string) $defaultWork['title'];
                if (isset($imagesByTitle[$title])) {
                    $state['works'][$index]['file_path'] = $imagesByTitle[$title]['file_path'];
                    $state['works'][$index]['alt'] = $imagesByTitle[$title]['alt'] ?: $title;
                    unset($imagesByTitle[$title]);
                }
            }

            $state['works'] = array_merge($state['works'], array_values($imagesByTitle), $extraWorks);
        }
    } catch (Throwable $e) {
    }

    try {
        $news = $pdo->query('SELECT title, slug, excerpt, image_path, published_at FROM hs_new_news WHERE is_active = 1 ORDER BY published_at DESC, id DESC')->fetchAll();
        $state['news'] = $news ?: [];
    } catch (Throwable $e) {
    }

    return $state;
}

function hs_get_news_item(string $slug): ?array
{
    $pdo = hs_db();
    if (!$pdo) {
        return null;
    }

    try {
        $stmt = $pdo->prepare('SELECT * FROM hs_new_news WHERE slug = ? AND is_active = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
    } catch (Throwable $e) {
        return null;
    }

    return $row ?: null;
}

function hs_lines(string $text): array
{
    return array_values(array_filter(array_map('trim', preg_split('/\R/u', $text) ?: [])));
}

function hs_format_date(?string $date): string
{
    if (!$date) {
        return '';
    }

    $timestamp = strtotime($date);
    return $timestamp ? date('d.m.Y', $timestamp) : $date;
}
