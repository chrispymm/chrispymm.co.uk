<?php
require_once('vendor/autoload.php');
require_once(__DIR__ . '/../utils/fetch_og_image.php');
require_once __DIR__ . '/../plugins/kirby3-dotenv/global.php';
loadenv();

return [
    'url' => [
        'https://chrispymm.co.uk',
        'http://chrispymm.co.uk.test'
    ],
    'debug'  => true,
    'api' => [
        'basicAuth' => true,
        'allowInsecure' => true
    ],
    'sylvainjule.colorextractor.mode' => 'both',
    'thathoff.git-content' => [
        'cronHooksSecret' => getenv('GIT_CONTENT_CRON_HOOKS_SECRET'),
        'buttons' => [
            'reset' => true, // enables the reset to origin button (default: false)
            'commit' => true, // enables the commit button (default: false)
            'pull' => true, // enables the pull button (default: false)
            'push' => true, // enables the push button (default: false)
            'fetch' => true, // disables the fetch button (default: true)
        ],
    ],
    'beebmx.scheduler' => [
        'timezone' => 'Europe/London',
        'schedule' => function (\Beebmx\KirbScheduler\Schedule $schedule) {
            $schedule->call(function () {
                $url = rtrim(kirby()->site()->url(), '/') . '/git-content/push';
                $secret = getenv('GIT_CONTENT_CRON_HOOKS_SECRET');

                if ($secret) {
                    $url .= '?secret=' . urlencode($secret);
                }

                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => true,
                ]);

                curl_exec($ch);
                curl_close($ch);
            })->daily()->at('02:00');
        },
    ],
    's1syphos.highlight' => [
        'class' => 'hljs',
        'languages' => ['html', 'js', 'css', 'ruby', 'erb']
    ],
    'routes' => require('routes.php'),
    'hooks' => require('hooks.php'),
    'thumbs' => require('thumbs.php'),

];
