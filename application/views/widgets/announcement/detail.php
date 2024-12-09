<section class="my-5">
    <div class="container">
        <div class="row my-5">

            <?php if ($announcement['announcement_attachment_video']) : ?>
                <div class="col-lg-12 text-center">
                    <a data-fancybox href="<?= get_image($announcement['announcement_attachment_path'], $announcement['announcement_attachment_video']) ?>">
                        <img src="<?= g('images_root') . 'video-placeholder.png' ?>" class="active" width="500" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                    </a>
                </div>
                <hr class="my-3" />
            <?php endif; ?>

            <?php if ($announcement['announcement_attachment']) : ?>
                <div class="col-lg-3">
                    <img src="<?= get_image($announcement['announcement_attachment_path'], $announcement['announcement_attachment']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            <?php endif; ?>

            <div class="<?= ($announcement['announcement_attachment']) ? 'col-lg-9' : 'col-lg-12' ?>">
                <span><?= RAW_ROLE_0 ?> | Comment <?= count($comment) ?> </span> |
                <span><?= date("F d, Y", strtotime($announcement['announcement_cretaedon'])) ?></span>
                <h3><?= $announcement['announcement_title'] ?? '...' ?></h3>
                <p><?= $announcement['announcement_desc'] ?? '...' ?></p>
            </div>

        </div>
        <?php $this->load->view('widgets/comment_widget.php'); ?>
    </div>
</section>

