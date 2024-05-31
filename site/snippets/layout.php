<html lang="en">
    <head>
        <?php snippet('head') ?>
    </head>
    <body>


        <?php snippet('header') ?>

        <main id="main" tabindex="-1">
            <div id="content" class="center <?= $page->template() ?>">
<?php if($title): ?>
                <header class="page-title center">
                    <h1><?= $title ?></h1>
                </header>
<?php endif;?>
                <?= $slot ?>
            </div>
        </main>

    <footer>

    </footer>

    </body>
</html>
