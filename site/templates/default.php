<?php snippet('layout', ['title' => $page->title()], slots: true) ?>

    <?= $page->text()->kirbytext() ?>
