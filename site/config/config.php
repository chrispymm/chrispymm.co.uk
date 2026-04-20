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
    's1syphos.highlight' => [
        'class' => 'hljs',
        'languages' => ['html', 'js', 'css', 'ruby', 'erb']
    ],
    'routes' => require_once('routes.php'),
    'hooks' => require_once('hooks.php'),
    'thumbs' => require_once('thumbs.php'),

];
