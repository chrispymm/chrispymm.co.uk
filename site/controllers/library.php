<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $books = $page->children()->listed()->sortBy('sortIndex', 'desc');
    // $books = $books->paginate(20);
    // $pagination = $books->pagination();

    return A::merge($siteController , compact('books'));
};
