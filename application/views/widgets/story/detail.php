<section>
    <div class="container">
        <div class="row">
    
            <?php if ($story['story_video']) : ?>
                <div class="col-lg-12 text-center">
                    <a data-fancybox href="<?= get_image($story['story_image_path'], $story['story_video']) ?>">
                        <img src="<?= g('images_root') . 'video-placeholder.png' ?>" class="active" width="500" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                    </a>
                </div>
                <hr class="my-3" />
            <?php endif; ?>
    
            <?php if ($story['story_image']) : ?>
                <div class="col-lg-3">
                    <img src="<?= get_image($story['story_image_path'], $story['story_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            <?php endif; ?>
    
            <div class="<?= ($story['story_image']) ? 'col-lg-3' : 'col-lg-12' ?>">
                <span><?= $story['story_author'] ?? '...' ?> | Comment, <?= count($comment) ?> </span> | 
                <span><?= date("F d, Y", strtotime($story['story_createdon'])) ?></span>
                <h3><?= $story['story_title'] ?? '...' ?></h3>
                <p><?= $story['story_detail'] ?? '...' ?></p>
            </div>
        </div>

        <?php $this->load->view('widgets/comment_widget.php'); ?>

    </div>
</section>