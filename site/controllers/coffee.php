<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $coffees = $page->find('beans')->children()->listed()->flip();
    // $articles = $articles->paginate(20);
    // $pagination = $articles->pagination();

    return A::merge($siteController , compact('coffees'));
};
