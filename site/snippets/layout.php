<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <?php snippet('head') ?>
    </head>
    <body>
        <script>
            const theme = localStorage.getItem("theme")
            document.body.setAttribute('data-theme', theme)
        </script>
        <a href="#content" class="skip-link">Skip to main content</a>

        <?php snippet('header') ?>

        <main id="main" tabindex="-1">
            <div id="content" class="center template-<?= $page->template() ?>">
<?php if(isset($title)): ?>
                <header class="page-title center">
                    <?php if(isset($pretitle)): ?>
                        <span class=""><?= $pretitle ?></span>
                    <?php endif ?>
                    <?= snippet('line-heading', ['text' => $title]) ?>
                </header>
<?php endif;?>
                <?= $slot ?>
            </div>
        </main>

    <footer class="footer center">
        <?php snippet('footer') ?>
    </footer>

    </body>
</html>
