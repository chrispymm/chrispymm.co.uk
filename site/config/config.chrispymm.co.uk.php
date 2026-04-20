<?php
return [
    'debug'  => false,
    'api' => [
        'basicAuth' => true,
    ],
    'sylvainallignol.cache-stamp' => [
        'active' => true,          // Enable/disable the plugin
        'method' => 'xxh3',        // Hash method: md5, sha1, sha256, xxh3, timestamp
        'prefix' => '',            // Prefix before the hash (a-z, A-Z, 0-9, -, _)
        'suffix' => ''             // Suffix after the hash (a-z, A-Z, 0-9, -, _)
    ],
    'node' => '/usr/bin/node'
];
