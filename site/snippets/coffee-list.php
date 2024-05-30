<article class="flow" style="--flow-space:0.6rem;">
    <h2 class="font-size-0"><a href="<?=$coffee->url()?>"><?= $coffee->title() ?></a></h2>
    <p class="font-size--1"><?= $coffee->origin() ?> | <?= $coffee->process() ?> | <?= $coffee->cultivar() ?></p>
    <?php if( $rating ): ?>
        <?php snippet('rating', ['rating' => (float) $coffee->rating()->value()]) ?>
    <?php endif ?>
</article>
