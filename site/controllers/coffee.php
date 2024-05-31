<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $coffees = $page->find('beans')->children()->listed();

    return A::merge($siteController , compact('coffees'));
};
