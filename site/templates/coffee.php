<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<div class="articles">
    <?php foreach($coffees as $coffee): ?>
        <?php snippet('coffee-list', ['coffee' => $coffee, 'rating' => true]) ?>
    <?php endforeach; ?>
</div>
