<section class="">

    <div class="container">

        <div class="row">

            <?php if ($news['news_video']) : ?>
                <div class="col-lg-12 text-center">
                    <a data-fancybox href="<?= get_image($news['news_attachment_path'], $news['news_video']) ?>">
                        <img src="<?= g('images_root') . 'video-placeholder.png' ?>" class="active" width="500" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                    </a>
                </div>
                <hr class="my-3" />
            <?php endif; ?>

            <?php if ($news['news_attachment']) : ?>
                <div class="col-lg-3">
                    <img src="<?= get_image($news['news_attachment_path'], $news['news_attachment']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            <?php endif; ?>

            <div class="<?= ($news['news_attachment']) ? 'col-lg-3' : 'col-lg-12' ?>">

                <span><?= $news['news_author'] ?? '...' ?> | Comment: <?= count($comment) ?> <?php if($news['news_url']): ?>| <a href="<?= $news['news_url'] ?>" target="_blank">See complete details <i class="fa fa-external-link"></i></a><?php endif; ?></span> |

                <span><?= date("F d, Y", strtotime($news['news_createdon'])) ?></span>

                <h3><?= $news['news_title'] ?? '...' ?></h3>

                <?php if(isset($tags) && !empty($tags)) : ?>
                    <?php foreach($tags as $key => $tag): ?>
                        <span class="badge bg-custom"><?= $tag['tag_name'] ?></span>
                    <?php endforeach; ?>
                <?php endif; ?>

                <p><?= $news['news_short_desc'] ?? '' ?></p>

            </div>
        </div>

        <p class="mt-2">
            <?= $news['news_desc'] ?? '...' ?>
        </p>

        <?php $this->load->view('widgets/comment_widget.php'); ?>

    </div>

</section>

