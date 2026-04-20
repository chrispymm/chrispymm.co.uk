<?php
use Bnomei\Janitor;
use Kirby\CLI\CLI;

return [
    'description' => 'Get kindle book highlights',
    'args' => [] + Janitor::ARGS, // page, file, user, site, data, model
    'command' => static function (CLI $cli): void {
        $page = page($cli->arg('page'));
        
        $node = kirby()->option('node', 'node');
        $root = kirby()->root();
        $cmd = $node . ' ' . $root . '/scripts/kindle-highlights.js "' . $page->title() . '" ' . $page->root() . ' 2>&1';
        $output = [];
        $returnVal = null;

        exec($cmd, $output, $returnVal);

        $outputStr = implode("\n", $output);

        if($returnVal === 0) {
            // output for the command line
            $cli->success('fetched highlights');

            // output for janitor
            janitor()->data($cli->arg('command'), [
                'status' => 200,
                'message' => 'Fetched page highlights',
            ]);
        } else {
            $cli->error('Error fetching highlights: ' . $outputStr);

            // output for janitor
            janitor()->data($cli->arg('command'), [
                'status' => 500,
                'message' => 'Error: ' . $outputStr,
            ]);

        }
    }
];
