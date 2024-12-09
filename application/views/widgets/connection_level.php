<?php if (isset($connectionLevel) && $connectionLevel) : ?>
    <?php
        $locale = 'en_US';
        $nf = new NumberFormatter($locale, NumberFormatter::ORDINAL);
    ?>
    <span class="badge badge-secondary noFloat" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Connection level') ?>"><?= $connectionLevel > 0 ? $nf->format($connectionLevel) : ''; ?></span>
    <!-- $nf->format($connectionLevel) -->
<?php endif; ?>