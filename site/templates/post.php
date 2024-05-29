
<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

    <article>
        <?= $page->text()->kirbytext() ?>
    </article>

