<?php snippet('layout', [], slots: true) ?>
                <header class="page-title book-title">
                    <?php if($image = $page->cover()->toFile()): ?>
        <picture style="view-transition-class: 'book-cover'; view-transition-name: book-<?=$page->uid()?>; background-color: <?= $image->color()->dominantColor() ?>; background-repeat: no-repeat; background-image: url('<?= $image->thumb(['width' => 30, 'quality' => 50, 'blur' => 10])->url() ?>');">

        <source
            srcset="<?= $image->srcset('book-webp') ?>"
            type="image/webp"
        >
        <img
            alt=""
            src="<?= $image->thumb(['width' => 150, 'quality' => 60])->url() ?>"
            srcset="<?= $image->srcset('book')?>"
            width="<?= $image->thumb(['width' => 300])->width() ?>"
            fetchpriority="high"
        >
        </picture>
    <?php endif ?>
<div>
                    <span class=""><?= $page->author() ?></span>
                    <?= snippet('line-heading', ['text' => $page->title()]) ?>
                        <?php if($page->unfinished()->toBool() === true): ?>
    <?php else: ?>
        <?php snippet('rating', ['rating' => (float) $page->rating()->value()]) ?>
    <?php endif ?>
</div>
                </header>

<div class="flow">




<?php if($page->comments() != ''): ?>
<h2 class="font-size-2">Comments</h2>
<?= $page->comments()->kirbytext() ?>
<?endif;?>
<?php if($page->hasChildren() ): ?>
<div class="highlights flow" style="--flow-space: var(--space-m)">
    <h2 class="font-size-2">Highlights</h2>
    <?php foreach($page->children() as $highlight): ?>
        <p class="book-highlight">
            <?= $highlight->text() ?>
        </p>
    <?php endforeach; ?>
</div>
<?php endif; ?>
</div>

