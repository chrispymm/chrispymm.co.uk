<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<div class="coffees grid">
    <?php foreach($coffees as $coffee): ?>
        <?php snippet('coffee-listing', ['coffee' => $coffee, 'rating' => true]) ?>
    <?php endforeach; ?>
</div>
