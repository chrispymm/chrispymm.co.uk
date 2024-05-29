<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

<div class="articles">
    <? foreach($coffees as $coffee): ?>
        <? snippet('coffee-list', ['coffee' => $coffee, 'rating' => true]) ?>
    <? endforeach; ?>
</div>
