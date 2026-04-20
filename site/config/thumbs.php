<?php
return [
    'srcsets' => [
        'book' => [
            '1x'  => ['width' => 300, 'quality' => 60 ],
            '2x'  => ['width' => 600, 'quality' => 40 ],
            '3x'  => ['width' => 900, 'quality' => 30 ],
        ],
        'book-webp' => [
            '1x'  => ['width' => 300, 'quality' => 60, 'format' => 'webp' ],
            '2x'  => ['width' => 600, 'quality' => 40, 'format' => 'webp'],
            '3x'  => ['width' => 900, 'quality' => 30, 'format' => 'webp' ],
        ],
        'book-avif' => [
            '1x'  => ['width' => 300, 'quality' => 60, 'format' => 'avif' ],
            '2x'  => ['width' => 600, 'quality' => 40, 'format' => 'avif'],
            '3x'  => ['width' => 900, 'quality' => 30, 'format' => 'avif' ],
        ]
    ]
];
