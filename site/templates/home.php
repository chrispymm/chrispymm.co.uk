
<?php snippet('layout', ['title' => null ], slots: true) ?>

<section class="masthead flow">
    <h1><?= $page->title() ?></h1>
    <?= $page->text()->kirbytext() ?>
</section>

<? if($posts): ?>
<section class="flow">
    <?= snippet('line-heading', ['level' => 2, 'text' => 'Articles']) ?>
    <ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <a href="<?= $post->url() ?>"><?= $post->title() ?></a>
        </li>
    <?php endforeach ?>
    </ul>
    <a href="/blog">More articles <span aria-hidden="true">&rarr;</span></a>
</section>
<? endif ?>

<section class="latest flow">
    <?= snippet('line-heading', ['level' => 2, 'text' => 'Latest']) ?>
    <div class="grid">
        <div class="flow latest__card">
            <h3 class="">Coffee</h3>
            <!-- <p><?= $latestBrew->method() ?></p> -->
            <?php snippet('coffee-card', ['coffee' => $latestBrew->coffee()->toPage(), 'rating' => false]) ?>
        </div>
        <div class="flow latest__card">
            <h3 class="">Book</h3>
            <div class="cluster">
                <?php if($image = $latestBook->cover()->toFile()): ?>
                    <img src="<?= $image->url() ?>" alt="" width="40px">
                <?php endif ?>
                <div class="flow" style="--flow-space:0.6rem">
                    <h4 class="font-size-0"><a href="/library"><?= $latestBook->title() ?></a></h4>
                    <p class="font-size--1"><?= $latestBook->author() ?></p>
                </div>
            </div>
        </div>
    </div>
</section>



