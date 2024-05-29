<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

    <?= $page->text()->kirbytext() ?>

<div class="articles">
    <?php foreach($articles as $article): ?>
        <article class="flow" style="--flow-space:0.6rem;">
            <h2 class="font-size-0"><a href="<?=$article->url()?>"><?=$article->title()?></a></h2>
            <p class="font-size--1"><?= $article->date()->toDate('dS M Y') ?></p>
        </article>
    <?php endforeach ?>
</div>
<?php snippet('pagination', compact('pagination')) ?>
