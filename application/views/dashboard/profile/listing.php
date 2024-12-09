<div class="dashboard-content">
    <div class="row">
        <div class="col-md-6">
            <?php if ($type == RAW_ROLE_3) : ?>
                <i class="fa-regular fa-buildings"></i>
            <?php else : ?>
                <i class="fa-regular fa-users"></i>
            <?php endif; ?>
            <h4><?= ($type ? $type : 'User') . ' ' . __('Listing') ?></h4>
        </div>
        
        <div class="offset-2 col-md-4">
            <div class="search-box-table">
                <i class="fa-regular fa-magnifying-glass"></i>
                <form class="userSearchForm">
                    <input type="text" class="form-control h-75" name="search" placeholder="Search <?= $type ? $type : 'users' ?>" value="<?= isset($search) ? $search : '' ?>" maxlength="255" />
                </form>
            </div>
        </div>
    </div>
    <hr />
    <div class="organation-listing mt-4">
        <div class="row">
            <?php if (isset($organization) && count($organization) > 0) : ?>
                <?php foreach ($organization as $key => $value) : ?>
                    <div class="col-lg-4 col-md-4 mb-5">
                        <div class="organiztion-list">
                            <div class="icon-container">
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <?php if ($type == RAW_ROLE_3) : ?>
                                        <?php if($value['signup_company_image']): ?>
                                            <img src="<?= get_image($value['signup_company_image_path'], $value['signup_company_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                                        <?php elseif($value['signup_logo_image']): ?>
                                            <img src="<?= get_user_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                        <?php else: ?>
                                            <img src="<?= g('images_root') . 'user.png' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <img src="<?= g('images_root') . 'user.png' ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                                    <?php endif; ?>
                                <?php else : ?>
                                    <img src="<?= g('images_root') . 'logo.png' ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                                <?php endif; ?>

                                <!-- Online Status -->
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <?php if (isset($value['signup_info_isonline']) && $value['signup_info_isonline']) : ?>
                                        <div class="status-circle" style="<?= (isset($value['signup_info_isonline']) && $value['signup_info_isonline']) ? 'background-color: green;' : ''; ?>right: 8px !important;">
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <!-- Online Status -->

                            </div>

                            <div>
                                <p class="mb-0">
                                    <?php if ($type == RAW_ROLE_3) : ?>
                                        <a href="<?= l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] ?>">
                                    <?php else : ?>
                                        <a href="javascript:;">
                                    <?php endif; ?>

                                    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                        <?php if ($type == RAW_ROLE_3) : ?>
                                            <?= $this->model_signup->listingName($value, false) ?>
                                        <?php else : ?>
                                            <?= $this->model_signup->listingName($value, false) ?>
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <?= $this->model_signup->listingName($value) ?>
                                    <?php endif; ?>

                                    </a>
                                    <?php if ((isset($value['signup_is_verified']) && isset($value['signup_vouched_token']) && $value['signup_is_verified'] && $value['signup_vouched_token']) || $this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_VERIFICATION)) : ?>
                                        <span class="text-custom" data-toggle="tooltip" data-bs-placement="top" title="Verified account"><i class="fa fa-circle-check"></i></span>
                                    <?php endif; ?>
                                    <?php $data['connectionLevel'] = $this->model_signup_follow->connectionLevel($value['signup_id'], $this->userid); ?>
                                    <?php $this->load->view('widgets/connection_level', $data) ?>
                                </p>

                                <div class="star-rate">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <?php if ($i <= $this->model_review->reviewAvg($value['signup_id'], REVIEW_TYPE_SIGNUP)) : ?>
                                            <i class="fa fa-star"></i>
                                        <?php elseif ($this->model_review->reviewAvg($value['signup_id'], REVIEW_TYPE_SIGNUP) == ($i - 0.5)) : ?>
                                            <i class="fa fa-star-half-o"></i>
                                        <?php else : ?>
                                            <i class="fa fa-star-o"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <small class="ps-4">
                                        <?php echo $this->model_review->reviewCount($value['signup_id'], REVIEW_TYPE_SIGNUP) ?> <?= __('Reviews') ?>
                                    </small>
                                </div>

                            </div>
                        </div>

                        <?php if ($type == RAW_ROLE_3) : ?>
                            <div class="link-srt">
                                <a href="<?= l('dashboard/job/listing/1/' . PER_PAGE .'/') . $value['signup_id'] ?>"><?= __('Open jobs') ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <?php if (isset($organization_count) && ($organization_count) > 0) : ?>
                    <div class="row mt-4">
                        <div class="col-lg-12">

                            <nav aria-label="Page navigation example mt-5">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php if ($page <= 1) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                                        echo '#';
                                                                                                    } else {
                                                                                                        echo l('dashboard/profile/listing/') . JWT::encode($signup_type, CI_ENCRYPTION_SECRET) . '/' . $prev . '?search=' . (isset($search) ? $search : '');
                                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                                    </li>

                                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                        <li class="page-item <?php if ($page == $i) {
                                                                    echo 'active';
                                                                } ?>">
                                            <a class="page-link" href="<?= l('dashboard/profile/listing/') . JWT::encode($signup_type, CI_ENCRYPTION_SECRET) . '/' . $i . '?search=' . (isset($search) ? $search : ''); ?>"> <?= $i; ?> </a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item <?php if ($page >= $totalPages) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                                        echo '#';
                                                                                                    } else {
                                                                                                        echo l('dashboard/profile/listing/') . JWT::encode($signup_type, CI_ENCRYPTION_SECRET) . '/' . $next . '?search=' . (isset($search) ? $search : '');
                                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                <?php endif; ?>

            <?php else : ?>
                <?= __('No ' . ($type ?? 'User') . ' available yet!') ?>
            <?php endif; ?>
        </div>
    </div>
</div>