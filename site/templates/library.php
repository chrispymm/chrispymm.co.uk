<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

    <?= $page->text()->kirbytext() ?>

    <div class="books grid">
    <?php foreach($books as $book): ?>
        <article class="book flow" style="--flow-space: 0.6rem;">
            <figure>
                <?php if($image = $book->cover()->toFile()): ?>
                    <img src="<?= $image->url() ?>" alt="">
                <?php endif ?>
            </figure>
            <h2 class="font-size-0"><a href="<?=$book->url()?>"><?=$book->title()?></a></h2>
            <p class="font-size--1"><?= $book->author() ?></p>
            <? snippet('rating', ['rating' => (float) $book->rating()->value()]) ?>
        </article>
    <?php endforeach ?>
</div>

