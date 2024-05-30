
<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

    <article class="flow">
        <?= $page->text()->kirbytext() ?>
    </article>

