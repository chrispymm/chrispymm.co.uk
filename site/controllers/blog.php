<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $articles = $page->children()->listed()->flip();
    $articles = $articles->paginate(20);
    $pagination = $articles->pagination();

    return A::merge($siteController , compact('articles', 'pagination'));
};
