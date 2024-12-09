<section class="">
    <div class="container">
        <div class="row">

            <?php if ($blog['blog_video']) : ?>
                <div class="col-lg-12 text-center">
                    <a data-fancybox href="<?= get_image($blog['blog_image_path'], $blog['blog_video']) ?>">
                        <img src="<?= g('images_root') . 'video-placeholder.png' ?>" class="active" width="500" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                    </a>
                </div>
                <hr class="my-3" />
            <?php endif; ?>

            <?php if ($blog['blog_image']) : ?>
                <div class="col-lg-3">
                    <img src="<?= get_image($blog['blog_image_path'], $blog['blog_image']) ?>" alt="" width="300" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            <?php endif; ?>

            <div class="<?= ($blog['blog_image']) ? 'col-lg-9' : 'col-lg-12' ?>">
                <span><?= $blog['blog_author'] ?? '...' ?> | Comment: <?= count($comment) ?> </span> |
                <span><?= date("F d, Y", strtotime($blog['blog_createdon'])) ?></span>
                <h3><?= $blog['blog_title'] ?? '...' ?></h3>

                <?php if(isset($tags) && !empty($tags)) : ?>
                    <?php foreach($tags as $key => $tag): ?>
                        <span class="badge bg-custom"><?= $tag['tag_name'] ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <p class="mt-2">
            <?= $blog['blog_detail'] ?? '...' ?>
        </p>

        <?php $this->load->view('widgets/comment_widget.php'); ?>

    </div>
</section>

