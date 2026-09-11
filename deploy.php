<?php

namespace Deployer;

require 'recipe/common.php';

set('application', 'chrispymm.co.uk');
set('repository', 'git@github.com:chrispymm/chrispymm.co.uk.git');
set('keep_releases', 5);

// Shared paths that sit at the same relative location in the release and in
// `shared/`, so Deployer's built-in `deploy:shared` task can handle them.
set('shared_dirs', [
    'content',
    'media',
    '.well-known',
]);

set('shared_files', [
    '.env',
    '.amazon-session.json',
]);

// Kirby keeps accounts, the cache and sessions under `site/`, but on the server
// they live at the top level of `shared/`. Keys are release-relative paths,
// values are `shared/`-relative paths.
set('kirby_shared_dirs', [
    'site/accounts' => 'accounts',
    'site/cache'    => 'cache',
    'site/sessions' => 'sessions',
]);

// php-fpm runs as `ploi` and deploys run as `chrispymm`; both are members of
// `deployhq`. Shared dirs created by a deploy are given this group with setgid
// so PHP can keep writing to them.
set('shared_group', 'deployhq');

// The shared dirs already carry the right permissions, so there is nothing for
// Deployer to chmod or setfacl on each release.
set('writable_dirs', []);

host('chrispymm.co.uk')
    ->set('hostname', '65.108.60.153')
    ->set('remote_user', 'chrispymm')
    ->set('deploy_path', '/var/www/chrispymm.co.uk')
    ->set('branch', 'main');

desc('Links Kirby site directories to their shared counterparts');
task('deploy:kirby:shared', function () {
    foreach (get('kirby_shared_dirs') as $releaseDir => $sharedDir) {
        $target = "{{deploy_path}}/shared/$sharedDir";
        $link   = "{{release_path}}/$releaseDir";

        if (!test("[ -d $target ]")) {
            run("mkdir -p $target");
            run("chgrp {{shared_group}} $target && chmod 2775 $target");
        }

        run("rm -rf $link");
        run('mkdir -p ' . dirname($link));
        run("{{bin/symlink}} $target $link");
    }
});

// `npm install` replaces a symlinked node_modules with a real directory, so it
// cannot be shared between releases. Playwright's browser binaries are cached
// in ~/.cache/ms-playwright, which keeps the per-release install cheap.
desc('Installs node dependencies for the Kindle highlights scripts');
task('deploy:npm', function () {
    cd('{{release_path}}');
    run('npm install --omit=dev --no-audit --no-fund', timeout: 600);
});

desc('Deploys the site');
task('deploy', [
    'deploy:prepare',
    'deploy:kirby:shared',
    'deploy:vendors',
    'deploy:npm',
    'deploy:publish',
]);

after('deploy:failed', 'deploy:unlock');
