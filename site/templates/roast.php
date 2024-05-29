<?php snippet('layout', ['title' => $page->title()], slots: true) ?>
<?php snippet('rating', ['rating' => (float) $page->rating()->value(), 'size' => 'large']) ?>

<table>
    <tr>
        <th>Roaster</th>
        <td><a href="<?= $roaster->website() ?>"><?= $roaster->title() ?></a></td>
    </tr>
    <tr>
    <th>Origin</th>
    <td><?= $page->origin() ?></td>
    </tr>
    <tr>
        <th>Process</th>
        <td><?= $page->process() ?></td>
</tr>
<tr>
<th>Cultivar</th>
        <td><?= $page->cultivar() ?></td>
</tr>
<tr>
<th>Tasting Notes</th>
    <td><?= $page->tasting_notes() ?></td>
</tr>
</table>



<h2>Comments</h2>
<?= $page->comments()->kirbyText() ?>


