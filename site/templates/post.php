
<?php snippet('layout', ['title' => $page->title(), 'pretitle' => $page->date()->toDate('jS M Y') ], slots: true) ?>

<article class="flow">
    <?= $page->text()->kirbytext() ?>
</article>

