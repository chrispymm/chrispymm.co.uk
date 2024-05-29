<div class="rating <?= isset($size) && $size == 'large' ? 'font-size-1' : '' ?>">
    <? for ($i=1; $i<6; $i++): ?>
        <? $class = 'star'; ?>
        <? $class .= ($i <= ceil($rating) ? ' star--filled' : ''); ?>
        <? $class .= ($i > $rating && floor($rating) == $i-1 && floor($rating) != $rating ? ' star--half' : ''); ?>

        <? if ($i <= ceil($rating)):?>
            <span class="<?= $class ?>">⭐</span>
        <?endif?>
    <?endfor;?>
</div>
