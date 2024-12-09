    <style>
        #profile {
            flex: 15%;
            display: block;
            position: relative;
            /* margin: 5% 2% 0 10%; */
            width: 100%;
            height: 100%;
        }
    </style>

    <div class="dashboard-content">
        <?php if ($type == RAW_ROLE_3) : ?>
            <i class="fa-regular fa-buildings"></i>
        <?php else : ?>
            <i class="fa-regular fa-users"></i>
        <?php endif; ?>
        <h4><?= __(($type ? $type : 'User') . ' Profile') ?> </h4>
        <hr />

        <div class="organization-profile">
            <div class="followCountArea">
                <span class="dropdown float-right">
                    <button><i class="fa fa-bars"></i></button>
                    <label>
                        <input type="checkbox" class="cursor-pointer" />
                        <ul>
                            <?php if (isset($user['signup_id']) && $user['signup_id'] == $this->userid) : ?>
                                <li>
                                    <a href="<?= l('dashboard/payment/method') ?>">
                                        Saved cards
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($user['signup_id']) && $user['signup_id'] != $this->userid) : ?>
                                <?php if (($this->model_signup->hasPremiumPermission())) : ?>
                                    <li>
                                        <a href="javascript:;" class="followBtn" data-reference_id="<?= $user['signup_id'] ?>">
                                            <!-- <i class="fa fa-user-o me-1"></i> -->
                                            <?= $this->model_signup_follow->isFollowing($user['signup_id'], $this->userid) ? __('Unfollow') : __('Follow') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= l('dashboard/message') ?>">
                                            <!-- <i class="fa fa-paper-plane-o me-1"></i>  -->
                                            <?= __('Chat') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= l('dashboard/endorsement/listing/1/' . PER_PAGE . '/' . JWT::encode($user['signup_id']) . '/1') ?>" data-toggle="tooltip" data-bs-placement="top" title="View and send endorsements to <?= '`' . $this->model_signup->profileName($user, FALSE) . '`' ?>">
                                            <!-- <i class="fa fa-comments me-1"></i>  -->
                                            <?= __('Endorsements') ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <li>
                                    <a href="<?= l('dashboard/profile/testimonial/' . JWT::encode($user['signup_id'], CI_ENCRYPTION_SECRET)) ?>" data-toggle="tooltip" data-bs-placement="top" title="All testimonials added by <?= '`' . $this->model_signup->profileName($user, FALSE) . '`' ?>">
                                        <!-- <i class="fa fa-quote-left me-1"></i>  -->
                                        <?= __('Testimonials') . ' by "' . $this->model_signup->profileName($user, FALSE) . '"' ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($this->model_signup->hasPremiumPermission() && $this->userid != $user['signup_id']) : ?>
                                <li>
                                    <a href="<?= l('dashboard/account/testimonial/listing/1/' . PER_PAGE . '/' . JWT::encode($user['signup_id'], CI_ENCRYPTION_SECRET) . '/1') ?>" data-toggle="tooltip" data-bs-placement="top" title="View and send testimonials to <?= '`' . $this->model_signup->profileName($user, FALSE) . '`' ?>">
                                        <!-- <i class="fa fa-quote-left me-1"></i>  -->
                                        <?= __('Send Testimonials') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </label>
                </span>

                <h4>
                    <?=
                    __('About') . ($this->model_signup->hasRole(ROLE_1) ? '' : ': ' . $this->model_signup->profileName($user, FALSE)) .
                        (((isset($user['signup_is_verified']) && isset($user['signup_vouched_token']) && $user['signup_is_verified'] && $user['signup_vouched_token']) || $this->model_signup_bypass_privilege->get($user['signup_id'], PRIVILEGE_TYPE_VERIFICATION)) ? ' <span class="text-custom" data-toggle="tooltip" data-bs-placement="top" title="Verified account"><i class="fa fa-circle-check"></i></span>' : ' ');
                    ?>
                    <?php $data['connectionLevel'] = $this->model_signup_follow->connectionLevel($user['signup_id'], $this->userid); ?>
                    <?php $this->load->view('widgets/connection_level', $data) ?>
                    <small data-toggle="tooltip" title="
                        <?php switch ($user['signup_privacy']) {
                            case 'private':
                                echo 'This is a private profile';
                                break;
                            case 'public':
                                echo 'This is a public profile';
                                break;
                        }
                        ?>">
                            <?php switch ($user['signup_privacy']) {
                                case 'private':
                                    echo '• <i class="fa fa-user-lock"></i>';
                                    break;
                                case 'public':
                                    echo '• <i class="fa fa-globe"></i>';
                                    break;
                            }
                            ?>
                    </small>
                </h4>

                <p>
                    <a href="<?= l('dashboard/profile/users/') . JWT::encode($user['signup_id'], CI_ENCRYPTION_SECRET) . '/' . TYPE_FOLLOWER ?>" target="_blank">
                        <?= $follower_count . ' ' . __('follower') ?>
                    </a>
                    &centerdot;
                    <a href="<?= l('dashboard/profile/users/') . JWT::encode($user['signup_id'], CI_ENCRYPTION_SECRET) . '/' . TYPE_FOLLOWEE ?>" target="_blank">
                        <?= $followee_count . ' ' . __('following'); ?>
                    </a>
                </p>
            </div>

            <?php if ($this->model_signup->canView($this->userid, $user['signup_id'])) : ?>
                <div id="container">
                    <div id="profile">
                        <div id="image">
                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <?php if ($type == RAW_ROLE_3) : ?>
                                    <?php if ($user['signup_company_image']) : ?>
                                        <img src="<?= get_image($user['signup_company_image_path'], $user['signup_company_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                                    <?php elseif ($user['signup_logo_image']) : ?>
                                        <img src="<?= get_user_image($user['signup_logo_image_path'], $user['signup_logo_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" id="profile-photo" />
                                    <?php else : ?>
                                        <img src="<?= g('images_root') . 'user.png' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php elseif ((int) $user['signup_id'] === $this->userid) : ?>
                                <?php if ($type == RAW_ROLE_1 . ' User') : ?>
                                    <!-- ' User' string added for ease of readability -->
                                    <img src="<?= get_image($user['signup_logo_image_path'], $user['signup_logo_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                                <?php elseif ($type == RAW_ROLE_3) : ?>
                                    <?php if ($user['signup_company_image']) : ?>
                                        <img src="<?= get_image($user['signup_company_image_path'], $user['signup_company_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                                    <?php elseif ($user['signup_logo_image']) : ?>
                                        <img src="<?= get_user_image($user['signup_logo_image_path'], $user['signup_logo_image']) ?>" class="rounded-circle" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" id="profile-photo" />
                                    <?php else : ?>
                                        <img src="<?= g('images_root') . 'user.png' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <img data-toggle="tooltip" data-bs-placement="left" title="<?= __('Upgrade your subscription to view the company logo.') ?>" src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" id="profile-photo" />
                            <?php endif; ?>
                        </div>
                        <hr style="width: 97%;" />

                        <p id="name">

                            <?php if ($this->userid === (int) $user['signup_id']) : ?>
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <?= isset($user['signup_company_representative_name']) && $user['signup_company_representative_name'] ? $user['signup_company_representative_name'] : '' ?>
                                <?php else : ?>
                                    <?= $this->model_signup->profileName($user, false) ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <?php if (($this->model_signup->hasPremiumPermission()) && isset($user)) : ?>
                                    <?= $this->model_signup->profileName($user, false) ?>
                                <?php else : ?>
                                    <span class="profileShowUpgrade" data-toggle="tooltip" data-bs-placement="left" title="<?= __('Upgrade your subscription to view fullname of the representative.') ?>">
                                        <?= $this->model_signup->profileName($user) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($type == RAW_ROLE_3) : ?>
                                <span class="font-10" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Company representative designation.') ?>">
                                    <?= isset($user['signup_company_representative_designation']) && $user['signup_company_representative_designation'] ? $user['signup_company_representative_designation'] : '' ?>
                                </span>
                                <span class="font-10" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Profession.') ?>">
                                    <?= isset($user['signup_profession']) && $user['signup_profession'] ? $user['signup_profession'] : '' ?>
                                </span>
                            <?php endif; ?>

                            <?= $user['signup_company_hiring'] ? '<label class="text-primary">#Hiring</label>' : '' ?>
                            <?= $user['signup_company_open_to_opportunity'] ? '<label class="text-primary">#Open_to_opportunities</label><br />' : '' ?>
                            <span id="email">
                                <a href="<?= isset($user['signup_email']) ? 'mailto:' . $user['signup_email'] : '...' ?>"><?= isset($user['signup_email']) ? $user['signup_email'] : '...' ?></a>
                            </span>

                        </p>

                        <p id="designation">

                            <!-- <br /> -->
                            <span id="college">
                                <?= isset($user['signup_company']) && $user['signup_company'] ? ($user['signup_company'] . isset($user['signup_company_type']) && $user['signup_company_type'] ? ' (' . $user['signup_company_type'] . ')' : '') : '' ?>
                            </span>
                            <span id="college">
                                <?= isset($user['signup_address']) && $user['signup_address'] ? $user['signup_address'] : '' ?>
                            </span>
                        </p>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <div id="social-links">
                                <?php if ($type == RAW_ROLE_3) : ?>
                                    <?php if ($user['signup_facebook']) : ?>
                                        <a href="<?= $user['signup_facebook'] ?>" target="_blank">
                                            <i class="fab fa-facebook-f stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_twitter']) : ?>
                                        <a href="<?= $user['signup_twitter'] ?>" target="_blank">
                                            <i class="fab fa-twitter stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_youtube']) : ?>
                                        <a href="<?= $user['signup_youtube'] ?>" target="_blank">
                                            <i class="fab fa-youtube stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_vimeo']) : ?>
                                        <a href="<?= $user['signup_vimeo'] ?>" target="_blank">
                                            <i class="fab fa-vimeo stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_linkedin']) : ?>
                                        <a href="<?= $user['signup_linkedin'] ?>" target="_blank">
                                            <i class="fab fa-linkedin-in stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_github']) : ?>
                                        <a href="<?= $user['signup_github'] ?>" target="_blank">
                                            <i class="fab fa-github stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div id="social-links">
                                <?php if ($type == RAW_ROLE_3) : ?>
                                    <?php if ($user['signup_company_facebook']) : ?>
                                        <a href="<?= $user['signup_company_facebook'] ?>" target="_blank">
                                            <i class="fab fa-facebook-f stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_company_twitter']) : ?>
                                        <a href="<?= $user['signup_company_twitter'] ?>" target="_blank">
                                            <i class="fab fa-twitter stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_company_youtube']) : ?>
                                        <a href="<?= $user['signup_company_youtube'] ?>" target="_blank">
                                            <i class="fab fa-youtube stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_company_vimeo']) : ?>
                                        <a href="<?= $user['signup_company_vimeo'] ?>" target="_blank">
                                            <i class="fab fa-vimeo stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_company_linkedin']) : ?>
                                        <a href="<?= $user['signup_company_linkedin'] ?>" target="_blank">
                                            <i class="fab fa-linkedin-in stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($user['signup_company_website']) : ?>
                                        <a href="<?= $user['signup_company_website'] ?>" target="_blank">
                                            <i class="fab fa-globe stroke-transparent"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <div class="contac-organ">
                                <?php if ($type == RAW_ROLE_3) : ?>
                                    <hr style="width: 97%;" />
                                    <?php if (isset($user['signup_email']) && ($user['signup_email'])) : ?>
                                        <a href="<?= isset($user['signup_email']) ? 'mailto:' . $user['signup_email'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Email">
                                            <i class="fa-regular fa-envelope"></i> <?= isset($user['signup_email']) ? $user['signup_email'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_address']) && ($user['signup_address'])) : ?>
                                        <a href="<?= isset($user['signup_address']) ? 'https://maps.google.com/?q=' . $user['signup_address'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Address">
                                            <i class="fa-regular fa-location-dot"></i> <?= isset($user['signup_address']) ? $user['signup_address'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_phone']) && ($user['signup_phone'])) : ?>
                                        <a href="<?= isset($user['signup_phone']) ? 'tel:' . $user['signup_phone'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Phone">
                                            <i class="fa-regular fa-phone"></i><?= isset($user['signup_phone']) ? $user['signup_phone'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_gender']) && ($user['signup_gender'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Gender">
                                            <i class="fa-regular fa-user"></i><?= isset($user['signup_gender']) ? ucfirst($user['signup_gender']) : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_birthday']) && ($user['signup_birthday'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Date of birth">
                                            <i class="fa-regular fa-calendar"></i><?= ($user['signup_birthday']) ?>
                                        </a>
                                    <?php endif; ?>

                                    <hr style="width: 97%;" />

                                    <?php if (isset($user['signup_company_representative_email']) && ($user['signup_company_representative_email'])) : ?>
                                        <a href="<?= isset($user['signup_company_representative_email']) ? 'mailto:' . $user['signup_company_representative_email'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Contact email">
                                            <i class="fa-regular fa-envelope"></i> <?= isset($user['signup_company_representative_email']) ? $user['signup_company_representative_email'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_location']) && ($user['signup_company_location'])) : ?>
                                        <a href="<?= isset($user['signup_company_location']) ? 'https://maps.google.com/?q=' . $user['signup_company_location'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Company location">
                                            <i class="fa-regular fa-location-dot"></i> <?= isset($user['signup_company_location']) ? $user['signup_company_location'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_representative_phone']) && ($user['signup_company_representative_phone'])) : ?>
                                        <a href="<?= isset($user['signup_company_representative_phone']) ? 'tel:' . $user['signup_company_representative_phone'] : '...' ?>" data-toggle="tooltip" data-bs-placement="left" title="Contact phone">
                                            <i class="	fas fa-phone-volume"></i><?= isset($user['signup_company_representative_phone']) ? $user['signup_company_representative_phone'] : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_founded']) && ($user['signup_company_founded'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Founded">
                                            <i class="fa-regular fa-calendar"></i><?= isset($user['signup_company_founded']) ? ucfirst($user['signup_company_founded']) : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_revenue']) && ($user['signup_company_revenue'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Revenue">
                                            <i class="fa-regular fa-money"></i><?= isset($user['signup_company_revenue']) ? ucfirst($user['signup_company_revenue']) : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_industry']) && ($user['signup_company_industry'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Industry">
                                            <i class="fa-regular fa-industry"></i><?= isset($user['signup_company_industry']) ? ucfirst($user['signup_company_industry']) : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if (isset($user['signup_company_size']) && ($user['signup_company_size'])) : ?>
                                        <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Company size">
                                            <i class="fa-regular fa-users"></i><?= isset($user['signup_company_size']) ? ucfirst($user['signup_company_size']) : '...' ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php if (isset($user['signup_language']) && FALSE !== @unserialize($user['signup_language'])) : ?>
                                    <a href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="Language profeciency">
                                        <i class="fa-regular fa-language"></i>
                                        <?php foreach (unserialize($user['signup_language']) as $key => $argv) : ?>
                                            <?= $argv . (array_key_last(unserialize($user['signup_language'])) ? '.' : ','); ?>
                                        <?php endforeach; ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <hr style="width: 97%;" />
                        <?php endif; ?>

                        <div id="about">
                            <p style="display:inline;">About</p>
                        </div>
                        <p id="year-graduation">
                            <?= $user['signup_about_me'] ? (strlen($user['signup_about_me']) > 500 ? strip_string($user['signup_about_me'], 500) . '<a data-fancybox data-animation-duration="700" data-src="#aboutModal" href="javascript:;" class="btn"><i class="fa fa-ellipsis-h"></i></a>' : $user['signup_about_me']) : NA; ?>
                        </p>
                        <?php if (strlen($user['signup_about_me']) > 500) : ?>
                            <div class="grid">
                                <div style="display: none;" id="aboutModal" class="animated-modal">
                                    <?= ($user['signup_about_me']); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <hr style="width: 97%;" />

                        <div id="about">
                            <p style="display:inline;">Skills</p>
                        </div>
                        <p id="year-graduation">
                            <?= $user['signup_skill'] ? $user['signup_skill'] : NA; ?>
                        </p>

                        <hr style="width: 97%;" />

                        <div id="about">
                            <p style="display:inline;">Recognitions (Awards & Honors)</p>
                        </div>
                        <p id="year-graduation">
                            <?= $user['signup_recognition'] ? $user['signup_recognition'] : NA; ?>
                        </p>

                    </div>

                    <?php if (isset($signup_credentials) && count($signup_credentials) > 0) : ?>
                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <div id="info-cards">
                                <?php foreach ($signup_credentials as $key => $value) : ?>
                                    <div class="card">
                                        <p>
                                            <?php switch ($key) {
                                                case SIGNUP_CREDENTIAL_EXPERIENCE:
                                                    echo '<i class="fas fa-briefcase stroke-transparent"></i>';
                                                    break;
                                                case SIGNUP_CREDENTIAL_EDUCATION:
                                                    echo '<i class="fas fa-graduation-cap stroke-transparent"></i>';
                                                    break;
                                                case SIGNUP_CREDENTIAL_LICENSE:
                                                    echo '<i class="fas fa-id-card stroke-transparent"></i>';
                                                    break;
                                                case SIGNUP_CREDENTIAL_CERTIFICATE:
                                                    echo '<i class="fas fa-certificate stroke-transparent"></i>';
                                                    break;
                                                case SIGNUP_CREDENTIAL_PUBLICATION:
                                                    echo '<i class="fas fa-quote-left stroke-transparent"></i>';
                                                    break;
                                            }
                                            ?>
                                            &nbsp;&nbsp;&nbsp;<?= ucfirst($key) ?>
                                        </p>
                                        <?php if (isset($value) && count($value) > 0) : ?>
                                            <ul>
                                                <?php foreach ($value as $key_ => $value_) : ?>
                                                    <li>
                                                        <span class="tags">
                                                            <?php switch (true) {
                                                                case ($value_['signup_credential_designation']):
                                                                    echo ucfirst(($value_['signup_credential_designation']));
                                                                    break;
                                                                case ($value_['signup_credential_name']):
                                                                    echo '<a href="' . $value_['signup_credential_url'] . '">' . ucfirst(($value_['signup_credential_name'])) . '</a>';
                                                                    break;
                                                                case ($value_['signup_credential_program']):
                                                                    echo ucfirst(($value_['signup_credential_program']));
                                                                    break;
                                                                case $value_['signup_credential_attachment']:
                                                                    echo 'View certificate <a href="' . get_image($value_['signup_credential_attachment_path'], $value_['signup_credential_attachment']) . '" target="_blank"><i class="fa fa-link"></i></a>';
                                                                    break;
                                                            }
                                                            ?>
                                                            <br />
                                                            <span>
                                                                <?= isset($value_['signup_credential_qualification']) && $value_['signup_credential_qualification'] ? $value_['signup_credential_qualification'] . ' | ' : '' ?>
                                                                <?= isset($value_['signup_credential_company']) && $value_['signup_credential_company'] ? $value_['signup_credential_company'] : '' ?>
                                                                <?= isset($value_['signup_credential_organization']) && $value_['signup_credential_organization'] ? $value_['signup_credential_organization'] : '' ?>
                                                                <span><?=
                                                                        validateDate($value_['signup_credential_start_date'], 'Y-m-d H:i:s') ?
                                                                            (' | ' . (date('M d, Y', strtotime($value_['signup_credential_start_date'])) .
                                                                                (validateDate($value_['signup_credential_end_date'], 'Y-m-d H:i:s') ? (' ' . (date('M d, Y', strtotime($value_['signup_credential_end_date'])))) : ' - present'))) : '';
                                                                        ?>
                                                                </span>
                                                                <span data-toggle="tooltip" data-bs-placement="top" title="<?php switch ($key) {
                                                                                                                                case SIGNUP_CREDENTIAL_EDUCATION:
                                                                                                                                    echo 'Dates at University/College';
                                                                                                                                    break;
                                                                                                                                case SIGNUP_CREDENTIAL_LICENSE:
                                                                                                                                    echo 'Date of validity of license';
                                                                                                                                    break;
                                                                                                                                case SIGNUP_CREDENTIAL_CERTIFICATE:
                                                                                                                                    echo 'Date certificate obtained';
                                                                                                                                    break;
                                                                                                                            } ?>"><?= validateDate($value_['signup_credential_date'], 'Y-m-d H:i:s') ? ' | ' . date('M d, Y', strtotime($value_['signup_credential_date'])) : ''; ?></span>
                                                            </span>
                                                        </span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php else : ?>
                                            <?= 'Professional record unavailable'; ?>
                                        <?php endif; ?>
                                        <?php if ($this->userid == $user['signup_id']) : ?>
                                            <a href="<?= l('dashboard/profile/create') ?>">+ Add <?= $key ?></a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="card" style="width: 67.5%;">
                                <p>Professional Record</p>
                                <small>
                                    <?= UPGRADE_MEMBERSHIP_DESCRIPTION; ?>
                                </small>
                            </div>
                        <?php endif; ?>
                    <?php else : ?>
                        <div class="card" style="width: 67.5%;">
                            <small>
                                <?= 'Professional record unavailable.'; ?>
                            </small>
                            <?php if ($this->userid == $user['signup_id']) : ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <hr />

            <div class="row">
                <?php if($this->userid == (int) $user['signup_id'] && 0): ?>
                    <div class="col-md-5">
                        <h4 data-toggle="tooltip" data-bs-placement="left" title="<?= __("All user analytics will be shown below.") ?>"><?= __('Profile Views') ?></h4>
                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <canvas id="myChart" width="300" height="200"></canvas>
                        <?php else : ?>
                            <?= __('Analytics unavailable.') ?>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <?php if (in_array($type, [RAW_ROLE_3])) : ?>
                        <div class="col-md-<?php echo ($this->userid == (int) $user['signup_id'] && 0) ? '7' : '12' ?>">
                            <h4 data-toggle="tooltip" data-bs-placement="left" title="<?= __("All user availability slots will be shown below.") ?>"><?= __("Availability Details") ?></h4>
                            <div id="calendar"></div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php $this->load->view('widgets/review_widget.php'); ?>

    </div>

    <a data-fancybox data-animation-duration="700" data-src="#animatedProfileViewsModal" href="javascript:;" class="profileViewsModal">
    </a>

    <div class="grid">
        <div style="display: none;" id="animatedProfileViewsModal" class="animated-modal">
            <div class="">
                <!-- modal-dialog -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            <div class="who_react_modal">
                                <h4><?= __('Profile Views') ?></h4>
                            </div>
                        </h4>
                    </div>
                    <!-- set data -->
                    <div class="modal-body profile-view-body">
                    </div>
                    <!-- set data -->
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger modal -->
    <a class="dynamoMeetingModalBtn d-none" data-fancybox data-animation-duration="700" data-src="#dynamoMeetingModal" href="javascript:;"></a>
    <!-- Modal -->
    <div class="grid">
        <div style="display: none; padding: 44px !important;" id="dynamoMeetingModal" class="animated-modal">
            <div class="modal-body dynamoMeetingModalBody">
                <ul class="nav nav-tabs">
                    <li class="active w-100 text-center">
                        <a data-toggle="tab" href="#meetingTab">Schedule Meeting</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div id="meetingTab" class="tab-pane show fade in active">
                        <form class="slotAvailabilityForm" action="javascript:;" method="POST" novalidate>
                            <input type="hidden" name="signup_availability[signup_availability_requester_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_availability[signup_availability_type]" value="SLOT_LOCKED" />

                            <!-- dynamo -->
                            <input type="hidden" name="signup_availability_id" />
                            <input type="hidden" name="start" />
                            <input type="hidden" name="end" />

                            <small class="fromtimeSlot"></small><br />
                            <small class="totimeSlot"></small>

                            <div class="form-group">
                                <label class="">Meeting Purpose <span class="text-danger">*</span></label>
                                <input class="form-control font-13" placeholder="Enter purpose of the meeting" name="signup_availability[signup_availability_purpose]" value="" maxlength="250" required />
                            </div>
                            <button class="btn btn-custom" type="submit">Save</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script src="https://www.chartjs.org/samples/2.6.0/utils.js"></script>

    <script>
        /**
         * Method eventDialog
         *
         * @return void
         */
        function eventDialog(type, eventObj) {
            var current_email = '<?= $this->user_data['signup_email'] ?>';
            $.dialog({
                backgroundDismiss: true,
                title: type == 'slot' ? 'Slot Detail' : 'Meeting Detail',
                content: 'Purpose: ' + (type == 'slot' ? eventObj.title : eventObj.extendedProps.purpose) + '.<br/>' +
                    'From: <b>' + eventObj.startStr + '</b><br/>' +
                    'To: ' + '<b>' + eventObj.endStr + '</b>' +
                    (
                        (eventObj.extendedProps.start_url && eventObj.extendedProps.join_url) ?
                        (
                            (eventObj.extendedProps.email == current_email) ?
                            (
                                (eventObj.extendedProps.current_status == 0) ?
                                '<br/><a class="btn btn-custom" target="_blank" href="' + eventObj.extendedProps.start_url + '">Start meeting</a>' : ''
                            ) :
                            (
                                (eventObj.extendedProps.current_status != 2) ?
                                '<br/><a target="_blank" class="btn btn-custom" href="' + eventObj.extendedProps.join_url + '">Join meeting</a>' : ''
                            )
                        ) :
                        ('')
                    ) + '<br/>' +
                    'Status: ' + ((eventObj.extendedProps.current_status == 1) ? 'Started' : ((eventObj.extendedProps.current_status == 2) ? 'Ended' : 'Pending')) + '<br/>' +
                    ((eventObj.extendedProps.current_status != 0) ?
                        ('Recording: ' + (
                            (Array.isArray(eventObj.extendedProps.meeting_recording.recording_files)) ?
                            (
                                '<a href="' + eventObj.extendedProps.meeting_recording.recording_files[0].play_url + '" target="_blank" class="btn btn-main"><i class="fa fa-play"></i></a><br/>' +
                                'Password: ' + eventObj.extendedProps.meeting_recording.password

                            ) : (eventObj.extendedProps.meeting_recording.message) ? eventObj.extendedProps.meeting_recording.message : 'This recording does not exist yet.')) : ''),
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('calendar') != null)
                var calendarEl = document.getElementById('calendar');

            try {
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    locale: '<?= ($this->session->has_userdata('site_lang_code') && $this->session->userdata('site_lang_code')) ? $this->session->userdata('site_lang_code') : 'en' ?>',
                    selectable: (<?= ($this->userid === (int) $user['signup_id']) ? 'true' : 'false' ?>),
                    eventColor: '#014e96',
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    selectMirror: true,
                    dateClick: function(info) {},
                    unselectAuto: true,
                    select: function(info) {},
                    eventClick: function(info) {
                        var eventObj = info.event;
                        var type = eventObj.backgroundColor == "#2c3e50" ? 'slot' : 'meeting'
                        var profile_email = '<?= $user['signup_email'] ?>';
                        var current_email = '<?= $this->user_data['signup_email'] ?>';

                        if (type == "slot") {
                            if (<?= ($this->userid != $user['signup_id'] && $this->model_signup_follow->isFollowing($user['signup_id'], $this->userid) ? 1 : 0) ?> && info.view.type != 'dayGridMonth' && (eventObj.startStr > new Date().toISOString())) {
                                // ajax save event
                                $('.dynamoMeetingModal-dialog').show()
                                $("input[name=signup_availability_id]").val(eventObj.id)
                                $("input[name=start]").val(eventObj.startStr)
                                $("input[name=end]").val(eventObj.endStr)
                                $('.fromtimeSlot').html('From: ' + moment(info.startStr).format('MMMM D YYYY, h:mm a'))
                                $('.totimeSlot').html('To: ' + moment(info.endStr).format('MMMM D YYYY, h:mm a'))
                                $('.dynamoMeetingModalBtn').trigger('click')
                            } else {
                                eventDialog(type, eventObj)
                            }
                        } else if (type == 'meeting') {
                            eventDialog(type, eventObj)
                        } else if (<?= $this->userid == $user['signup_id'] ? 1 : 0 ?>) {
                            swal({
                                title: "<?= __('Delete this slot?') ?>",
                                text: 'Remove your availability? \n' +
                                    'From: ' + eventObj.startStr + '\n' +
                                    'To: ' + eventObj.endStr,
                                icon: "warning",
                                className: "text-center",
                                buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
                            }).
                            then((isConfirm) => {
                                if (isConfirm) {

                                    var data = {
                                        'id': eventObj.id,
                                    }
                                    var url = base_url + 'dashboard/custom/delete_availability_slot'

                                    new Promise((resolve, reject) => {
                                        jQuery.ajax({
                                            url: url,
                                            type: "POST",
                                            data: data,
                                            async: true,
                                            dataType: "json",
                                            success: function(response) {
                                                resolve(response)
                                            },
                                            error: function (jqXHR, textStatus, errorThrown) {
                                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                                            },
                                            beforeSend: function() {
                                                showLoader()
                                            },
                                            complete: function() {
                                                hideLoader()
                                            }
                                        })
                            		}).then(
                            		    function(response) {
                		                    if (response.status) {
                                                swal({
                                                    title: "Success",
                                                    text: response.txt,
                                                    icon: "success",
                                                }).then(() => {
                                                    calendar.getEventById(eventObj.id).remove()
                                                })
                                            } else {
                                                swal("Error", response.txt, "error");
                                            }
                                        }
                                    )
                                } else {
                                    swal("Cancelled", "Action aborted", "error");
                                }
                            })
                        } else {
                            eventDialog(type, eventObj)
                        }
                    },
                    selectOverlap: function(event) {
                        return !event.block;
                    },
                    editable: true,
                    events: <?= (isset($availability_slots) && count($availability_slots) > 0) ? json_encode($availability_slots) : "[]" ?>,
                    eventSourceFailure: function(errorObj) {
                        console.log(errorObj)
                    },
                });

                calendar.render();
            } catch (e) {
                console.log(e)
            }

            $('body').on('submit', '.slotAvailabilityForm', function() {
                if (!$(this)[0].checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    $(this).addClass('was-validated');
                    $(this).find(":invalid").first().focus();
                    return false;
                } else {
                    $(this).removeClass('was-validated');
                }

                var data = $(this).serialize()
                var url = base_url + 'dashboard/custom/save_availability_slot'

                new Promise((resolve, reject) => {
                    jQuery.ajax({
                        url: url,
                        type: "POST",
                        data: data,
                        async: true,
                        dataType: "json",
                        success: function(response) {
                            resolve(response)
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        },
                        beforeSend: function() {
                            showLoader()
                        },
                        complete: function() {
                            hideLoader()
                        }
                    })
        		}).then(
        		    function(response) {                
        		        if (response.status) {
                            $('.fancybox-close-small').trigger('click')
                            swal({
                                title: "Success",
                                text: response.txt,
                                icon: "success",
                            }).then(() => {
                                calendar.removeAllEvents()
                                calendar.addEventSource(response.slots)
                            })
                        } else {
                            $('.fancybox-close-small').trigger('click')
                            $.dialog({
                                backgroundDismiss: true,
                                title: 'Error',
                                content: response.txt,
                            })
                        }
        		    }
    		    )
            })
        });
    </script>

    <script>
        var xValues = <?= (isset($analytics) && is_array($analytics) && count($analytics) > 0) ? json_encode(array_keys($analytics)) : '[]' ?>;
        var yValues = <?= (isset($analytics) && is_array($analytics) && count($analytics) > 0) ? json_encode(array_values($analytics)) : '[]' ?>;

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function get_profile_viewers(data) {

            var url = base_url + 'dashboard/custom/get_profile_viewers'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('.profile-view-body').html('')
                    },
                    complete: function() { }
                })
    		}).then(
    		    function(response) {
                    if (response.status) {
                        var reactionData = response.data;
                        if (reactionData.length > 0) {
                            $('.profile-view-body').removeClass('encodeSVGLoader')
                            $('.profile-view-body').append(response.html)
                        } else {
                            $('.who_react_modal').html('Profile Views')
                            $('.profile-view-body').removeClass('encodeSVGLoader')
                            $('.profile-view-body').append('No viewers yet.');
                        }
                    } else {
                        $('.who_react_modal').html('Profile Views')
                        $('.profile-view-body').removeClass('encodeSVGLoader')
                        $('.profile-view-body').append('No viewers yet.');
                    }
    		    }
		    )
        }

        try {
            if (document.getElementById('myChart')) {
                new Chart("myChart", {
                    type: "line",
                    data: {
                        labels: xValues,
                        datasets: [{
                            label: "Profile View(s)",
                            fill: false,
                            lineTension: 0.5,
                            backgroundColor: "#815354",
                            borderColor: "#8204aa",
                            data: yValues
                        }]
                    },
                    options: {
                        onClick: (e, activeEls) => {
                            let index = activeEls[0]._index;
                            var date = activeEls[0]['_chart'].config.data.labels[index];
                            var views = activeEls[0]["_chart"].config.data.datasets[0].data[index];
                            var user_id = <?= $user['signup_id'] ?>

                            var data = {
                                user_id: user_id,
                                date: date,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            }
                            $('.profileViewsModal').trigger('click')
                            get_profile_viewers(data);

                            return false;
                        },
                        locale: '<?= ($this->session->has_userdata('site_lang_code') && $this->session->userdata('site_lang_code')) ? $this->session->userdata('site_lang_code') : 'en' ?>',
                        legend: {
                            display: false
                        },
                        hover: {
                            onHover: function(e) {
                                var point = this.getElementAtEvent(e);
                                if (point.length) e.target.style.cursor = 'pointer';
                                else e.target.style.cursor = 'default';
                            }
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                            }],
                            yAxes: [{
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Views',
                                },
                                ticks: {
                                    min: 0,
                                    beginAtZero: true,
                                    fixedStepSize: 1,
                                }
                            }],
                        }
                    }
                });
            }
        } catch (e) {
            console.log(e)
        }
    </script>

    <!-- NOT USED -->
    <script>
        if (document.getElementById('line-chart')) {

            new Chart(document.getElementById("line-chart"), {
                type: 'line',
                data: {
                    labels: [2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025, 2026, 2027],
                    datasets: [{
                        data: [0, 86, 114, 106, 106, 107, 111, 133, 221, 783, 2478],
                        label: "1 ⭐",
                        borderColor: "#3e95cd",
                        fill: false
                    }, {
                        data: [282, 350, 411, 502, 635, 809, 947, 1402, 3700, 5267],
                        label: "2 ⭐",
                        borderColor: "#8e5ea2",
                        fill: false
                    }, {
                        data: [168, 170, 178, 190, 203, 276, 408, 547, 675, 734],
                        label: "3 ⭐",
                        borderColor: "#3cba9f",
                        fill: false
                    }, {
                        data: [40, 20, 10, 16, 24, 38, 74, 167, 508, 784],
                        label: "4 ⭐",
                        borderColor: "#e8c3b9",
                        fill: false
                    }, {
                        data: [6, 3, 2, 2, 7, 26, 82, 172, 312, 433],
                        label: "5 ⭐",
                        borderColor: "#c45850",
                        fill: false
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'Ratings'
                    }
                }
            });
        }

        $(document).ready(function() {
            if ($('.profileShowUpgrade').length > 0) {
                $('.profileShowUpgrade').tooltip('show')
                setTimeout(function() {
                    $('.profileShowUpgrade').tooltip('hide')
                }, 3000)
            }
        })
    </script>
    <!-- NOT USED -->

    <!-- NOT USED -->
    <script>
        if (document.getElementById('speedChart')) {
            var speedCanvas = document.getElementById("speedChart");

            Chart.defaults.global.defaultFontFamily = "roboto";
            Chart.defaults.global.defaultFontSize = 14;

            var dataFirst = {
                label: "Profile Views",
                data: <?= (isset($analytics) && is_array($analytics) && count($analytics) > 0) ? json_encode(array_values($analytics)) : '[]' ?>,
                lineTension: 1,
                fill: false,
                borderColor: '#80060a'
            };

            var speedData = {
                labels: <?= (isset($analytics) && is_array($analytics) && count($analytics) > 0) ? json_encode(array_keys($analytics)) : '[]' ?>,
                datasets: [dataFirst]
            };

            var chartOptions = {
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart with Tick Configuration'
                    }
                },
                responsive: true,
                radius: 5,
                hitRadius: 30,
                hoverRadius: 12,
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        boxWidth: 0,
                        fontColor: 'black'
                    },
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        min: 0,
                        scaleLabel: {
                            display: true,
                            labelString: 'Views'
                        },
                        ticks: {
                            beginAtZero: true,
                            fixedStepSize: 1,
                        }
                    }]
                },
            };

            var lineChart = new Chart(speedCanvas, {
                type: 'line',
                data: speedData,
                options: chartOptions
            });
        }
    </script>
    <!-- NOT USED -->