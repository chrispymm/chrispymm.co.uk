<?php 
return [
    [
        'pattern' => 'library/(:num)',
        'action' => function ($value) {
            $data = [
              'year' => $value,
            ];
            return page('library')->render($data);
        }
    ]
];
