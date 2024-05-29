<?php

return function($page, $pages, $site, $kirby) {
    
    $mainMenu = $pages->find('about', 'blog', 'coffee', 'library');

    return compact('mainMenu');
};
