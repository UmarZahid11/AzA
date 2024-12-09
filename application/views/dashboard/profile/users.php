<div class="dashboard-content total-theme">
    <div style="float:right;">
        <div class="side-large-text"><?php echo (isset($users) && is_array($users)) ? count($users) : '' ?></div>
    </div>
    <i class="fa-regular fa-file-chart-pie"></i>
    <h4><?= '"' . $reference_title . '"'; ?> <?= $type ?></h4>
    <hr>
    <table class="style-1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Company</th>
                <th><?= $type == FOLLOWER ? __('Following Since') : __('Follower Since') ?></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="listingArea">
            <?php if (isset($users) && is_array($users) && count($users) > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                <?php foreach ($users as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <a href="<?= l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] ?>">
                                    <img src="<?= get_user_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                </a>
                                <div>
                                    <a href="<?= l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] ?>">
                                        <?= $this->model_signup->profileName($value, FALSE) ?>
                                    </a>
                                    <p>
                                        <?= isset($value['signup_profession']) ? strip_string($value['signup_profession']) : '' ?>
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p><?= isset($value['signup_company']) ? $value['signup_company'] : NOT_AVAILABLE ?></p>
                        </td>
                        <td>
                            <p><small><?= isset($value['signup_follow_createdon']) ? date('d M, Y', strtotime($value['signup_follow_createdon'])) : '' ?></small></p>
                        </td>
                        <td class="listFollowBtn">
                            <!-- Viewing own profile in the list listing -->
                            <?php if ($this->userid != $value['signup_id']) : ?>
                                <!-- Viewing own follower/followee listing -->
                                <?php if ($this->userid == $reference_detail['signup_id']) : ?>
                                    <button class="btn btn-custom followBtn" type="btn" data-reference_id="<?= $value['signup_id'] ?>">
                                        <?= $this->model_signup_follow->isFollowing($value['signup_id'], $reference_detail['signup_id']) ? __('Unfollow') : __('Follow') ?>
                                    </button>
                                <?php else : ?>
                                    <button class="btn btn-custom followBtn" type="btn" data-reference_id="<?= $value['signup_id'] ?>">
                                        <?= $this->model_signup_follow->isFollowing($value['signup_id'], $this->userid) ? __('Unfollow') : __('Follow') ?>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td>
                        <?= __('Nothing to show here.') ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (isset($users_count) && $users_count > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
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
                                                                                            echo l('dashboard/profile/users/') . JWT::encode($reference_id, CI_ENCRYPTION_SECRET) . '/' . $type_num . '/' . $reference_type . '/' . $prev;
                                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('dashboard/profile/users/') . JWT::encode($reference_id, CI_ENCRYPTION_SECRET) . '/' . $type_num . '/' . $reference_type . '/' . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                            echo '#';
                                                                                        } else {
                                                                                            echo l('dashboard/profile/users/') . JWT::encode($reference_id, CI_ENCRYPTION_SECRET) . '/' . $type_num . '/' . $reference_type . '/' .  $next;
                                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    <?php endif; ?>
</div>
