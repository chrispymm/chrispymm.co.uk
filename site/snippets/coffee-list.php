<article class="flow" style="--flow-space:0.6rem;">
    <h2 class="font-size-0"><a href="<?=$coffee->url()?>"><?= $coffee->title() ?></a></h2>
    <p class="font-size--1"><?= $coffee->origin() ?> | <?= $coffee->process() ?> | <?= $coffee->cultivar() ?></p>
    <? if( $rating ): ?>
        <? snippet('rating', ['rating' => (float) $coffee->rating()->value()]) ?>
    <? endif ?>
</article>
