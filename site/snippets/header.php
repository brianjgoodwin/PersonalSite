<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title()->html() ?> | <?= $site->title()->html() ?></title>
    <?= css('assets/css/index.css') ?>
    
</head>
<body>
    <header class="header">
        <a class="logo" href="<?= $site->url() ?>"><?= $site->title()->html() ?></a>

        <nav class="menu">
            <ul>
                <?php foreach ($site->children()->listed() as $item): ?>
                <li><a href="<?= $item->url() ?>"><?= $item->title()->html() ?></a></li>
                <?php endforeach ?>
            </ul>
        </nav>

    </header>
    <main>