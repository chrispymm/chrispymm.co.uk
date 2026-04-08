  <meta charset="UTF-8">
  <title>
    <?= $page->title() ?> | <?= $site->title() ?>
  </title>

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script>
      if('noModule' in HTMLScriptElement.prototype) {
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
      }
  </script>
  <?= js("assets/javascripts/trigger-visibility.js", ['type' => 'module']) ?>
  <?= js("assets/javascripts/accent-switcher.js", ['type' => 'module']) ?>
  <?= js("assets/javascripts/theme-switcher.js", ['type' => 'module']) ?>
  <?= js("assets/javascripts/lazy-loading.js", ['type' => 'module']) ?>
<style>
@layer reset, base, layout, theme, utilities;
</style>

<link rel="preload" href="<?= cacheStamp('assets/fonts/fira-code/FiraCode-Variable-LatinBasic.woff2') ?>" type="font/woff2" as="font" crossorigin="">
<link rel="preload" href="<?= cacheStamp('assets/fonts/petrona/Petrona-800-LatinBasic.woff2') ?>" type="font/woff2" as="font" crossorigin="">

<?= css('assets/stylesheets/reset.css') ?>
<?= css('assets/stylesheets/base.css') ?>
<?= css('assets/stylesheets/layout.css') ?>
<?= css('assets/stylesheets/utilities.css') ?>
<?= css('assets/stylesheets/theme-default.css') ?>
<?= css('assets/stylesheets/highlight/a11y-light-dark.css') ?>

<meta name="description" content="<?= $site->description() ?>">
<meta name="keywords" content="<?= $site->keywords() ?>">
