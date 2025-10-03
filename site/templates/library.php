<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<?= $page->text()->kirbytext() ?>
<div class="flow">
<div class="center">
        <nav aria-labelledby="year-nav-title">
            <h2 id="year-nav-title" class="font-size-2">Year</h2>
            <ul role="list" class="cluster">
            <?php foreach($years as $year): ?>
                <li><a <?= $currentYear == $year ? 'aria-current="true"' : '' ?> href="/library/<?= $year ?>"><?= $year ?></a></li>
            <?php endforeach ?>
            </ul>
        </nav>
</div>

    <div class="books grid">
    <?php foreach($books as $book): ?>
        <article class="book flow" style="--flow-space: 0.6rem;">
            <figure>
                <?php if($image = $book->cover()->toFile()): ?>
                    <picture>

                    <source
                        srcset="<?= $image->srcset('book-webp') ?>"
                        type="image/webp"
                    >
                    <img
                        alt=""
                        src="<?= $image->thumb(['width' => 300, 'quality' => 60])->url() ?>"
                        srcset="<?= $image->srcset('book')?>"
                    >
                <?php endif ?>
            </figure>
            <h2 class="font-size-0"><?=$book->title()?></h2>
            <p class="font-size--1"><?= $book->author() ?></p>
            <?php snippet('rating', ['rating' => (float) $book->rating()->value()]) ?>
        </article>
    <?php endforeach ?>
    </div>
</div>

