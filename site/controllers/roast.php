<?php

return function($page, $pages, $site, $kirby) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));

    $roaster = $page->roaster()->toPage();

    return A::merge($siteController , compact('roaster'));
};
