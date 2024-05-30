
<?php snippet('layout', ['title' => null ], slots: true) ?>

<section class="masthead flow">
    <h1><?= $page->title() ?></h1>
    <?= $page->text()->kirbytext() ?>
</section>

<? if($posts): ?>
<section class="flow">
    <h2>Articles</h2>
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
    <h2>Currently</h2>
    <div>
        <div>
            <h3>Drinking</h3>
            <!-- <p><?= $latestBrew->method() ?></p> -->
            <?php snippet('coffee-list', ['coffee' => $latestBrew->coffee()->toPage(), 'rating' => false]) ?>
        </div>
        <div>
            <h3>Reading</h3>
            <div class="flow" style="--flow-space:0.6rem">
                <h2 class="font-size-0"><a href="/library"><?= $latestBook->title() ?></a></h2>
                <p class="font-size--1"><?= $latestBook->author() ?></p>
            </div>
        </div>
    </div>
</section>



