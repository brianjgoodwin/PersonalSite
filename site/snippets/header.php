<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> | <?= $site->title() ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%;
        }

        <?php
        // Get page color - check current page first, then parent
        $pageColor = $page->pagecolor()->value();
        if (empty($pageColor) && $page->parent()) {
            $pageColor = $page->parent()->pagecolor()->value();
        }

        // Define solid color background based on page color
        if (!empty($pageColor)) {
            $rgb = $pageColor;
            echo "html {\n";
            echo "    background: rgb($rgb);\n";
            echo "}\n";
        }
        ?>

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: transparent;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 100vh;
            background: white;
            z-index: -1;
        }
        @media (max-width: 920px) {
            body::before {
                width: 100%;
            }
        }
        header {
            position: relative;
            padding: 20px 20px 20px 20px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            border-bottom: 2px solid #333;
        }
        header h1 {
            font-size: 2rem;
            margin: 0;
        }
        nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
            margin: 0;
        }
        nav a {
            color: #333;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
        }
        nav a:hover {
            background: #f0f0f0;
        }
        nav a.active {
            background: #333;
            color: white;
        }
        main {
            flex: 1;
            padding: 0 20px;
        }
        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        .text-content {
            line-height: 1.8;
            margin-bottom: 30px;
        }
        footer {
            position: relative;
            background-color: #000;
            color: #fff;
            margin-top: 60px;
            padding: 20px;
        }
        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 100%;
            background-color: #000;
            border-top: 1px solid #ddd;
            z-index: -1;
        }
        footer .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }
        footer .footer-content p {
            margin: 0;
        }
        footer .footer-content a {
            color: #fff;
            text-decoration: none;
        }

        /* Component Styles */

        /* Card Component */
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .card-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        .card-content {
            padding: 20px;
        }
        .card-title {
            font-size: 1.4rem;
            margin-bottom: 10px;
        }
        .card-title a {
            color: #333;
            text-decoration: none;
        }
        .card-title a:hover {
            color: #0066cc;
        }
        .card-date {
            display: block;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        .card-description {
            color: #555;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .card-link {
            color: #0066cc;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .card-link:hover {
            text-decoration: underline;
        }

        /* Two Column Component */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        @media (max-width: 768px) {
            .two-column {
                grid-template-columns: 1fr;
            }
        }

        /* Link List Component */
        .link-list {
            margin-bottom: 30px;
        }
        .link-list-title {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }
        .link-list-items {
            list-style: none;
        }
        .link-list-items li {
            margin-bottom: 10px;
        }
        .link-list-items a {
            color: #0066cc;
            text-decoration: none;
        }
        .link-list-items a:hover {
            text-decoration: underline;
        }
        .link-description {
            display: block;
            color: #666;
            font-size: 0.85rem;
            margin-top: 4px;
        }

        /* Post Preview Component */
        .post-preview {
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .post-preview-header {
            margin-bottom: 10px;
        }
        .post-preview-title {
            font-size: 1.4rem;
            margin-bottom: 8px;
        }
        .post-preview-title a {
            color: #333;
            text-decoration: none;
        }
        .post-preview-title a:hover {
            color: #0066cc;
        }
        .post-preview-date {
            color: #666;
            font-size: 0.9rem;
            display: block;
        }
        .post-preview-excerpt {
            color: #555;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .post-preview-link {
            color: #0066cc;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .post-preview-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="<?= $site->url() ?>" style="color: inherit; text-decoration: none;"><?= $site->title() ?></a></h1>
        <nav>
            <ul>
                <?php foreach ($site->children()->listed() as $item): ?>
                <li>
                    <a href="<?= $item->url() ?>" <?php e($item->isOpen(), 'class="active"') ?>>
                        <?= $item->title() ?>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
        </nav>
    </header>
    <main>
