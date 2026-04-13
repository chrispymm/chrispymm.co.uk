<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<?= $page->text()->kirbytext() ?>
<div class="flow">
    <!--
<div class="center">
        <nav aria-labelledby="year-nav-title">
            <h2 id="year-nav-title" class="font-size-1">Year</h2>
            <ul role="list" class="cluster">
            <?php if( count($years)): ?>
                <li><a <?= !$currentYear ? 'aria-current="true"' : '' ?> href="/library/">All</a></li>
            <?php endif ?>
            <?php foreach($allYears as $year): ?>
                <li><a <?= $currentYear == $year ? 'aria-current="true"' : '' ?> href="/library/<?= $year ?>"><?= $year ?></a></li>
            <?php endforeach ?>
            </ul>
        </nav>
</div>
-->
<?php $counter = 0; ?>
<?php foreach($years as $year => $books): ?>
    <section class="year">
    <header>
        <?= snippet('line-heading', ['level' => 2, 'text' => $year]) ?>
        <span class="count">— <?= $books->count() ?> books</span>
    </header>

    <div class="books grid">

    <?php foreach($books as $book): ?>
        <? $counter++ ?>
        <article class="book flow" style="view-transition-class: 'book', view-transition-name: book-<?=$book->uid()?>;">
                <?php if($image = $book->cover()->toFile()): ?>
                <picture style="background-color: <?= $image->color()->dominantColor() ?>; background-size: cover; background-repeat: no-repeat; background-image: url('<?= $image->thumb(['width' => 30, 'quality' => 50, 'blur' => 10])->url() ?>');">

                    <source
                        srcset="<?= $image->srcset('book-webp') ?>"
                        type="image/webp"
                    >
                    <img
                        alt=""
                        src="<?= $image->thumb(['width' => 150, 'quality' => 60])->url() ?>"
                        srcset="<?= $image->srcset('book')?>"
                        width="<?= $image->thumb(['width' => 300])->width() ?>"
                        <?= $counter == 1 ? 'fetchpriority="high"' : '' ?>
                        <?= $counter >= 10 ? 'loading="lazy"' : '' ?>
                >
                    </picture>
                <?php endif ?>
                <div class="" >
                    <h2 class="font-size-0"><?=$book->title()?></h2>
                    <p class="font-size--1"><?= $book->author() ?></p>
                    <?php if($book->unfinished()->toBool() === true): ?>
                        <p class="font-size--1">DNF</p>
                    <?php else: ?>
                        <?php snippet('rating', ['rating' => (float) $book->rating()->value()]) ?>
                    <?php endif ?>
                </div>
        </article>
    <?php endforeach ?>
        </div>
        </section>
<?php endforeach ?>
</div>

