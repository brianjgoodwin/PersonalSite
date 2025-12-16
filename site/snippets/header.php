<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title() ?> | <?= $site->title() ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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
        } else {
            echo "html {\n";
            echo "    background: #f0f0f0;\n";
            echo "}\n";
        }
        ?>

        html {
            height: 100%;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: relative;
        }

        /* Three-layer layout: background (html) -> border land -> body content */

        /* Border land layer - white at 95% opacity */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            height: 100vh;
            background: rgba(255, 255, 255, 0.95);
            z-index: -2;
            /* Width changes based on viewport */
            width: calc(900px + 200px); /* body + 100px border on each side */
        }

        /* Body content layer - solid white */
        body::after {
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

        /* Responsive breakpoints - progressive resize */
        /*
        Strategy: background shrinks first, then border land, then body

        Layout from outside in:
        - Background (colored)
        - Border land (white 95% opacity)
        - Body (white solid)

        Desktop (>1140px): background flexible, border 100px, body 900px
        1140px-980px: background shrinks to 20px, border stays 100px, body stays 900px
        980px-960px: background locked 20px, border shrinks to 20px, body stays 900px
        <960px: all locked, body becomes responsive
        */

        /* Phase 1: Background shrinks from flexible to 20px minimum (stops at 980px) */
        @media (max-width: 1140px) {
            body::before {
                /* calc(100vw - 40px) subtracts 20px from each side
                   At 1140px: 1100px = 900 body + 200 border land
                   At 980px: 940px = 900 body + 40 border land (20px each side) */
                width: calc(100vw - 40px);
            }
        }

        /* Phase 2: Background locked at 20px, border land shrinks from 100px to 20px */
        @media (max-width: 980px) {
            body::before {
                /* 900px body + 40px border (20px each side) + background handled by parent */
                width: calc(900px + 40px);
            }
        }

        /* Phase 3: Everything at minimum, body becomes responsive */
        @media (max-width: 960px) {
            body::before {
                width: calc(100vw - 40px); /* 20px background on each side */
            }
            body::after {
                width: calc(100vw - 80px); /* 20px border + 20px background on each side */
            }
            main {
                max-width: 100%;
            }
        }
        /* Header container - full-width white background */
        header {
            position: relative;
            padding: 20px;
            margin-bottom: 40px;
            background: white;
        }

        /* Full-width white background for header */
        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            height: 100%;
            background: white;
            z-index: -1;
        }

        /* Full-width header border */
        header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100vw;
            border-bottom: 2px solid #333;
        }

        /* Header content constrained to body width with space-between */
        header > * {
            position: relative;
            z-index: 1;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-inner {
            max-width: 900px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            font-size: 2rem;
            margin: 0;
        }

        /* Hamburger menu toggle (hidden checkbox) */
        #menu-toggle {
            display: none;
        }

        /* Hamburger icon */
        .menu-icon {
            display: none;
            flex-direction: column;
            cursor: pointer;
            gap: 4px;
        }

        .menu-icon span {
            display: block;
            width: 25px;
            height: 3px;
            background: #333;
            transition: all 0.3s ease;
        }

        /* Desktop navigation */
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

        /* Mobile navigation - below 768px */
        @media (max-width: 768px) {
            .menu-icon {
                display: flex;
            }

            nav {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.3s ease;
            }

            nav ul {
                flex-direction: column;
                gap: 0;
                padding: 0;
            }

            nav ul li {
                border-top: 1px solid #eee;
            }

            nav a {
                display: block;
                padding: 15px 20px;
                border-radius: 0;
            }

            /* Show menu when checkbox is checked */
            #menu-toggle:checked ~ nav {
                max-height: 500px;
            }

            /* Animate hamburger to X when open */
            #menu-toggle:checked ~ label .menu-icon span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }

            #menu-toggle:checked ~ label .menu-icon span:nth-child(2) {
                opacity: 0;
            }

            #menu-toggle:checked ~ label .menu-icon span:nth-child(3) {
                transform: rotate(-45deg) translate(7px, -7px);
            }
        }
        /* Main content - constrained to body width with proper containment */
        main {
            flex: 1;
            max-width: 860px; /* 900px - 40px (20px padding on each side) */
            margin: 0 auto;
            padding: 20px;
            width: calc(100% - 40px); /* Ensure content stays within white body area */
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        .text-content {
            line-height: 1.8;
            margin-bottom: 30px;
        }

        /* Footer - content constrained, background full-width and above body */
        footer {
            position: relative;
            margin-top: 60px;
            padding: 20px;
            background-color: #000;
        }

        /* Full-width black background - above body layer */
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

        /* Footer content constrained to body width */
        footer .footer-content {
            max-width: 900px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #fff;
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
        <div class="header-inner">
            <h1><a href="<?= $site->url() ?>" style="color: inherit; text-decoration: none;"><?= $site->title() ?></a></h1>

            <div style="display: flex; align-items: center; gap: 20px;">
                <!-- CSS-only hamburger menu -->
                <input type="checkbox" id="menu-toggle">
                <label for="menu-toggle">
                    <div class="menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </label>

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
            </div>
        </div>
    </header>
    <main>
