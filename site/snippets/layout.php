<html lang="en">
    <head>
        <? snippet('head') ?>
    </head>
    <body>


        <? snippet('header') ?>

        <main id="main" tabindex="-1">
            <section id="content" class="center <?= $page->template() ?>">
<? if($title): ?>
                <header class="page-title center">
                    <h1><?= $title ?></h1>
                </header>
<?endif;?>
                <?= $slot ?>
            </section>
        </main>

    <footer>

    </footer>

    </body>
</html>
