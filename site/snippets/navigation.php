<nav>
<ul role="list" class="cluster">
    <?php foreach($mainMenu as $page): ?>
    <li>
      <a href="<?= $page->url() ?>">
        <?= $page->title() ?>
      </a>
    </li>
    <?php endforeach ?>
</ul>
</nav>
