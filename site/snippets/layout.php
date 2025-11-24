<html lang="en">
    <head>
        <?php snippet('head') ?>
    </head>
    <body class="flow" style="--flow-space: var(--space-m-xl)">
        <script>
            const theme = localStorage.getItem("theme")
            console.log(`setting theme data attributes to ${theme}`)
            document.body.setAttribute('data-theme', theme)
        </script>
        <a href="#content" class="skip-link">Skip to main content</a>

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

    <footer class="footer center">
        <?php snippet('footer') ?>
    </footer>

    </body>
</html>
