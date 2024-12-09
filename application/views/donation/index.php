<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Donations' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center">
            <a href="<?= l('donation/detail') ?>">Donate <?= g('db.admin.title') ?></a>
            <p>OR</p>
            <h2>Our Fundraisers Activites</h2>
        </div>
        <?php if(isset($fundraisings) && ($fundraisings)) : ?>
            <div class="row mt-4">
                <?php foreach($fundraisings as $fundraising) : ?>
                    <div class="col-12 col-md-4 mb-4">
                        <div class="card">
                            <a href="<?= l('donation/detail/') . $fundraising['fundraising_slug'] ?>">
                                <img src="<?= get_image($fundraising['fundraising_attachment_path'], $fundraising['fundraising_attachment']) ?>" class="card-img-top"  />
                            </a>
                            <div class="card-body">
                                <a href="<?= l('donation/detail/') . $fundraising['fundraising_slug'] ?>"><h3 class="card-title" data-toggle="tooltip" title="<?= $fundraising['fundraising_title'] ?>"><?= strip_string($fundraising['fundraising_title'], 24) ?></h3></a>
                                <p class="card-text"><?= strip_string($fundraising['fundraising_short_desc'], 100) ?></p>
                                <span class="badge bg-secondary"><?= price($this->model_donation->donationByActivity($fundraising['fundraising_id'])) ?> / <?= price($fundraising['fundraising_amount']) ?></span>
                                <a href="<?= l('donation/detail/') . $fundraising['fundraising_slug'] ?>" class="btn btn-custom">Donate Now</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-lg-12">

                    <nav aria-label="Page navigation example mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page <= 1) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('donation/index/') . $prev . '/' . $limit;
                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) {
                                                            echo 'active';
                                                        } ?>">
                                    <a class="page-link" href="<?= l('donation/index/') . $i . '/' . $limit; ?>"> <?= $i; ?> </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if ($page >= $totalPages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('donation/index/') . $next . '/' . $limit;
                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        <?php else : ?>
            <p class="text-center mt-5 mb-5">No fundraising activities available.</p>
        <?php endif; ?>
    </div>
</section>