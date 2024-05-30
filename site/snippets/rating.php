<div class="rating <?= isset($size) && $size == 'large' ? 'font-size-1' : '' ?>">
    <?php for ($i=1; $i<6; $i++): ?>
        <?php $class = 'star'; ?>
        <?php $class .= ($i <= ceil($rating) ? ' star--filled' : ''); ?>
        <?php $class .= ($i > $rating && floor($rating) == $i-1 && floor($rating) != $rating ? ' star--half' : ''); ?>

        <?php if ($i <= ceil($rating)):?>
            <span class="<?= $class ?>">⭐</span>
        <?php endif; ?>
    <?php endfor;?>
</div>
