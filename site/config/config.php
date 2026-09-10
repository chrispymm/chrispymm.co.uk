<?php
require_once('vendor/autoload.php');
require_once(__DIR__ . '/../utils/fetch_og_image.php');
require_once __DIR__ . '/../plugins/kirby3-dotenv/global.php';
loadenv();

return [
    'debug'  => true,
    'api' => [
        'basicAuth' => true,
        'allowInsecure' => true
    ],
    'sylvainjule.colorextractor.mode' => 'both',
    'thathoff.git-content' => [
        'buttons' => [
            'reset' => true, // enables the reset to origin button (default: false)
            'commit' => true, // enables the commit button (default: false)
            'pull' => true, // enables the pull button (default: false)
            'push' => true, // enables the push button (default: false)
            'fetch' => true, // disables the fetch button (default: true)
        ],
    ],
    's1syphos.highlight' => [
        'class' => 'hljs',
        'languages' => ['html', 'js', 'css', 'ruby', 'erb']
    ],
    'routes' => require('routes.php'),
    'hooks' => require('hooks.php'),
    'thumbs' => require('thumbs.php'),

];
