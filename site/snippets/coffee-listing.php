<article class="coffee flow" style="">
    <header class="flow" style="--flow-space: 0.3rem;">
    <?= snippet('line-heading', ['text' => $coffee->title(), 'level' => 2]) ?>    
    <?php if( $rating ): ?>
        <?php snippet('rating', ['rating' => (float) $coffee->rating()->value()]) ?>
        <?php endif ?>
    </header>
    <dl>
            <dt>Roaster:</dt>
            <dd><a href=""><?= $coffee->roaster()->toPage()->title() ?></a></dd>
            <dt>Origin:</dt>
            <dd><?= $coffee->origin() ?></dd>
        <?php if(strlen($coffee->process())): ?>
            <dt>Process:</dt>
            <dd><?= $coffee->process() ?></dd>
        <?php endif; ?>
        <?php if(strlen($coffee->cultivar())): ?>
            <dt>Cultivar:</dt>
            <dd><?= $coffee->cultivar() ?></dd>
        <?php endif; ?>
        <?php if(strlen($coffee->tasting_notes())): ?>
            <dt>Flavours:</dt>
            <dd><?= $coffee->tasting_notes() ?></dd>
        <?php endif; ?>
    </dl>
    <?php if($coffee->comments()->isNotEmpty()): ?>
    <details> 
        <summary><h3 class="font-size-0">Comments</h3></summary>
        <blockquote>
            <?= $coffee->comments()->kirbyText() ?>
        </blockquote>
    </details>
    <?php endif; ?>
</article>
