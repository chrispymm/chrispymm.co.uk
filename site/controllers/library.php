<?php

return function($page, $pages, $site, $kirby, $year) {
    $siteController = $kirby->controller('site' , compact('page', 'pages', 'site', 'kirby'));


    $allYears = $page->children()
        ->listed()
        ->filterBy('completion_date', '!=', '')
        ->sortBy('completion_date', 'desc')
        ->group(
            fn ($child) => $child->completion_date()->toDate('Y')
        )
        ->keys();

    if ($year) {
        // $books = $page->children()
        //     ->listed()
        //     ->filter(
        //         fn ($child) => $child->completion_date()->toDate('Y') == $year
        //     )
        //     ->sortBy('sortIndex', 'desc');
        $years = $page->children()
        ->listed()
        ->filterBy('completion_date', '!=', '')
        ->sortBy('completion_date', 'desc')
        ->filter(
            fn ($child) => $child->completion_date()->toDate('Y') == $year
        )
        ->group(
            fn ($child) => $child->completion_date()->toDate('Y')
        );
    } else {
        // $books = $page->children()
        //     ->listed()
        //     ->filterBy('completion_date', '!=', '')
        //     ->sortBy('sortIndex', 'desc');
        //
        $years = $page->children()
        ->listed()
        ->filterBy('completion_date', '!=', '')
        ->sortBy('completion_date', 'desc')
        ->group(
            fn ($child) => $child->completion_date()->toDate('Y')
        );
    }
    // $books = $books->paginate(20);
    // $pagination = $books->pagination();
    $currentYear = $year;

    return A::merge($siteController , compact( 'allYears', 'years', 'currentYear'));
};
