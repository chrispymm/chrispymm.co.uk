<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<?= $page->text()->kirbytext() ?>
<div class="flow">
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



<?php foreach($years as $year => $books): ?>
    <section>
    <header class="cluster" style="--cluster-vertical-alignment: baseline;"><h2><?= $year ?></h2><span><?= $books->count() ?> books</span></header>

    <div class="books grid">

    <?php foreach($books as $book): ?>
        <article class="book flow" style="--flow-space: 0.3rem; view-transition-class: 'book', view-transition-name: book-<?=$book->uid()?>;">
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
                    </picture>
                <?php endif ?>
            <h2 class="font-size-0"><?=$book->title()?></h2>
            <p class="font-size--1"><?= $book->author() ?></p>
            <?php snippet('rating', ['rating' => (float) $book->rating()->value()]) ?>
        </article>
    <?php endforeach ?>
        </div>
        </section>
<?php endforeach ?>
</div>

