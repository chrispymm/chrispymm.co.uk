<?php
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
return [
    'debug'  => false,
    'api' => [
        'basicAuth' => true,
    ],
        'beebmx.scheduler' => [
        'timezone' => 'Europe/London',
        'schedule' => function (\Beebmx\KirbScheduler\Schedule $schedule) {
            $schedule->call(function () {
                $url = rtrim(kirby()->site()->url(), '/') . '/git-content/push';
                $secret = $_ENV['GIT_CONTENT_CRON_HOOKS_SECRET'];

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
            })->hourly();
        },
    ],
    'node' => '/usr/bin/node'
];
