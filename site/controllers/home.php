<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $posts = $site->index()->filterBy('template', 'post')->listed()->flip()->limit(3);

    $latestBrew = $site->index()->filterBy('template', 'brew')->listed()->last();

    $latestBook = $site->index()->filterBy('template', 'book')->listed()->filterBy('completion_date', '==', '')->last();

    return A::merge($siteController , compact('posts', 'latestBrew', 'latestBook'));
};
