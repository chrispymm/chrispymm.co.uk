<nav class="pagination">

  <?php if ($pagination->hasPrevPage()): ?>
      <a href="<?= $pagination->prevPageUrl() ?>">previous</a>
  <?php endif ?>

  <?php if ($pagination->hasNextPage()): ?>
      <a href="<?= $pagination->nextPageUrl() ?>">next</a>
  <?php endif ?>

</nav>
