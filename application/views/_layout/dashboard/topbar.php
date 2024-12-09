<?php

$param = array();
$seen_param = array();
$all_param = array();

$seen_param['where']['notification_seen'] = 0;
$all_param['where']['notification_signup_id'] = $seen_param['where']['notification_signup_id'] = $param['where']['notification_signup_id'] = $this->userid;
$param['order'] = 'notification_id DESC';
$param['limit'] = PER_PAGE;
$param['joins'] = array(
    0 => array(
        'table' => 'signup',
        'joint' => 'signup.signup_id = notification.notification_from',
        'type'  => 'left'
    ),
    1 => array(
        'table' => 'signup_company',
        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
        'type'  => 'left'
    )
);
$top_notifications = $this->model_notification->find_all_active($param);
//
$seen_notifications = $this->model_notification->find_all_active($seen_param);
//
$all_notifications = $this->model_notification->find_all_active($all_param);
?>

<?php

$param = array();
$seen_param = array();
$all_param = array();

$param['joins'] = array(
    0 => array(
        'table' => 'signup',
        'joint' => 'signup.signup_id = chat.chat_signup1',
        'type'  => 'both'
    ),
    1 => array(
        'table' => 'signup_company',
        'joint' => 'signup_company.signup_company_signup_id = signup.signup_id',
        'type'  => 'left'
    )
);
// $param['order'] = 'chat_updatedon DESC';
$param['order'] = 'chat_id DESC';
$param['limit'] = PER_PAGE;
$seen_param['where']['chat_seen'] = 0;
$param['where']['chat_signup2'] = $seen_param['where']['chat_signup2'] = $all_param['where']['chat_signup2'] = $this->userid;

$top_chat = $this->model_chat->find_all_active($param);
//
$seen_chat = $this->model_chat->find_all_active($seen_param);
//
$all_chat = $this->model_chat->find_all_active($all_param);

// $unseen_chat_query = 'SELECT * FROM `fb_chat`
//     INNER JOIN fb_signup ON ((fb_chat.chat_signup1 = fb_signup.signup_id AND chat_signup1 != ' . $this->userid . ') OR fb_chat.chat_signup2 = fb_signup.signup_id AND chat_signup2 != ' . $this->userid . ')
//     INNER JOIN fb_signup_info ON (fb_signup.signup_id = fb_signup_info.signup_info_signup_id)
//     WHERE (`chat_signup1` = ' . $this->userid . ' OR `chat_signup2` = ' . $this->userid . ') AND `chat_reference_type` = "' . CHAT_REFERENCE_MESSAGE . '" AND `fb_chat`.`chat_seen` = 1 AND `fb_chat`.`chat_status` = 1';
// $unseen_chat_count = $this->db->query($unseen_chat_query)->num_rows();

?>

<div class="top-header">

    <div class="search-box">
        <a href="" class="menu-toggle-btn">
            <i class="fa-light fa-bars-staggered"></i>
        </a>
        <form class="search" method="POST" action="javascript:;">
            <div class="search-hd-box">
                <button><i class="fa-light fa-magnifying-glass"></i></button>
                <input type="text" class="autoComplete" name="searchDataKeyword" placeholder="Search" autocomplete="off" value="<?= isset($search) ? ($search ?? '') : '' ?>" />
            </div>
        </form>
        <div class="search_result"></div>
    </div>

    <div class="right-options">

        <?php if (
            (
            $this->model_signup->hasRole(ROLE_1)
            ||  (
                    (isset($this->user_data['signup_is_stripe_connected']) && !$this->user_data['signup_is_stripe_connected'] && $this->model_signup->hasPremiumPermission())
                    || (isset($this->user_data['signup_is_phone_confirmed']) && !$this->user_data['signup_is_phone_confirmed'])
                    || (isset($this->user_data['signup_is_confirmed']) && !$this->user_data['signup_is_confirmed'])
                    || (isset($this->user_data['signup_is_employment_verified']) && !$this->user_data['signup_is_employment_verified'])
                    || (!$this->user_data['signup_company_id'])
                    // || (!$this->user_data['signup_paypal_email'])
                )
            )
            && !$this->model_signup->hasRole(ROLE_0)
        ) : ?>
            <div class="profile-opt pull-right p-4">
                <a href="javascript:;" class="text-custom">
                    <i class="fa fa-alarm-exclamation"></i>&nbsp;<?= __('Account action required') ?>!
                    &nbsp;<i class="fa-solid fa-caret-down"></i>
                </a>
                <div class="drop-profile extra-w-300">
                    <?php if (isset($this->user_data['signup_is_stripe_connected']) && !$this->user_data['signup_is_stripe_connected'] && $this->model_signup->hasPremiumPermission()) : ?>
                        <a href="<?= l('dashboard/home/stripe/accounts') ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __("Connect to stripe to receive payments") ?>"><i class="fa fa-link text-custom"></i>&nbsp;<?= __('Connect your account with stripe') ?></a>
                    <?php endif; ?>
                    <?php if ($this->model_signup->hasRole(ROLE_1)) : ?>
                        <a href="<?= l('membership') ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __("You are currently a ") . $this->model_signup->getRawRole() . " member, you can upgrade your account to an " . RAW_ROLE_3 . "." ?>"><i class="fa fa-arrow-up text-custom"></i>&nbsp;<?php echo ERROR_MESSAGE_SUBSCRIPTION ?></a>
                    <?php endif; ?>
                    <?php if ((isset($this->user_data['signup_is_confirmed']) && !$this->user_data['signup_is_confirmed'])) : ?>
                        <a class="resend_confirmation" href="javascript:;" data-toggle="tooltip" data-bs-placement="left" title="<?= __(EMAIL_CONFIRMATION_TEXT) ?>" data-text="Re-send email confirmation">
                            <i class="fa fa-envelope text-custom"></i>&nbsp;<?= __('Re-send email confirmation') ?>
                        </a>
                    <?php endif; ?>
                    <?php if ((isset($this->user_data['signup_is_phone_confirmed']) && !$this->user_data['signup_is_phone_confirmed'])) : ?>
                        <a href="<?= l('dashboard/profile/setting') ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __("Verify your phone number.") ?>"><i class="fa fa-phone text-custom"></i>&nbsp;<?= __('Confirm your phone number') ?></a>
                    <?php endif; ?>
                    <?php if ((isset($this->user_data['signup_is_employment_verified']) && !$this->user_data['signup_is_employment_verified'])) : ?>
                        <a href="<?= l('plaid/link/' . PLAID_TYPE_INCOME . '/' . PLAID_BANK_INCOME) ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __("Confirm your employment by connecting with your bank (applicable for USA and Canada only).") ?>"><i class="fa fa-tasks text-custom"></i>&nbsp;<?= __('Confirm your employment') ?></a>
                    <?php endif; ?>
                    <?php if (!$this->user_data['signup_company_id']) : ?>
                        <a href="<?= l('dashboard/company/create') ?>">
                            <i class="fa fa-buildings text-custom"></i>&nbsp;Create company profile
                        </a>
                    <?php endif; ?>
                    <?php if ((!$this->user_data['signup_paypal_email'])) : ?>
                        <!--<a href="<?= l('dashboard/profile/create') ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __("Connect to stripe to receive payments through Paypal") ?>">-->
                        <!--    <i class="fa fa-paypal text-custom"></i>&nbsp;<?= __('Add your Paypal account') ?>-->
                        <!--</a>-->
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="notification-wrap" id="top-notification">
            <input type="hidden" name="seen_notifications" value="<?= count($seen_notifications); ?>" />
            <a href="javascript:;" id="top-notification-anchor" open-box="false" data-toggle="tooltip" title="Notifications" data-bs-placement="left">
                <i class="fa-regular fa-bell" aria-hidden="true"></i>
                <span><?= ((count($seen_notifications) > 9) ? '9+' : count($seen_notifications)) ?></span>
            </a>
            <div class="notification-drp">
                <div class="notify-head">
                    <?= __('Notification'); ?>
                    <span> (<?= count($seen_notifications) ?>) </span>
                </div>
                <?php if (isset($top_notifications) && count($top_notifications) > 0) : ?>
                    <?php foreach ($top_notifications as $key => $value) : ?>

                        <?php if (isset($value['notification_reference_id'])) : ?>
                            <a href="<?= $this->model_notification->notificationRedirection($value) ?>">
                        <?php else : ?>
                            <a href="javascript:;">
                        <?php endif; ?>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <img src="<?= $this->model_signup->profileImage($value) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                        <?php else : ?>
                            <img src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                        <?php endif; ?>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <?php echo $this->model_signup->profileName($value, false); ?>
                        <?php else : ?>
                            <?php echo $this->model_signup->profileName($value); ?>
                        <?php endif; ?>

                        <?= isset($value['notification_comment']) ? $value['notification_comment'] : '' ?>
                        <?= isset($value['notification_comment2']) ? $value['notification_comment2'] : '' ?>
                        </a>

                    <?php endforeach; ?>

                    <div class="text-center mt-3 mb-3">
                        <a href="<?= l('dashboard/notification') ?>"><?= __('See all') ?></a>
                    </div>
                <?php else : ?>
                    <div class="text-center mt-3">
                        <?= __('No notifications yet') ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="notification-wrap" id="top-message">
            <input type="hidden" name="seen_chat" value="<?= count($seen_chat); ?>" />
            <a href="javascript:;" id="top-message-anchor" open-box="false" data-toggle="tooltip" title="Emails & messages" data-bs-placement="bottom">
                <i class="fa-regular fa-envelope"></i><span><?= ((count($seen_chat) > 9) ? '9+' : count($seen_chat)) ?></span>
            </a>
            <div class="notification-drp">
                <div class="notify-head">
                    <span>
                        <a class="text-white" href="<?= l('dashboard/home/inbox') ?>"><?= __('Emails'); ?></a> |
                        <a class="text-white" href="<?= l('dashboard/message') ?>"><?= __('Messages'); ?></a>
                    </span>
                    <span> (<?= count($seen_chat) ?>) </span>
                </div>
                <?php if (isset($top_chat) && count($top_chat) > 0) : ?>
                    <?php foreach ($top_chat as $key => $value) : ?>

                        <a class="font-13" href="
                        <?php
                        switch ($value['chat_reference_type']) {
                            case CHAT_REFERENCE_EMAIL:
                                echo l('dashboard/home/message/details/') . $value['chat_id'];
                                break;
                            case CHAT_REFERENCE_MESSAGE:
                                echo l('dashboard/message/index/') . JWT::encode($value['chat_id']);
                                break;
                        }
                        ?>">

                            <?php $chat_signup = ''; ?>

                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <img src="<?= $this->model_signup->profileImage($value) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                            <?php else : ?>
                                <img src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                            <?php endif; ?>

                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <?php $chat_signup .= $this->model_signup->profileName($value, false); ?>
                            <?php else : ?>
                                <?php $chat_signup .= $this->model_signup->profileName($value); ?>
                            <?php endif; ?>

                            <?php
                                echo ucfirst($value['chat_reference_type']) . ' ' . 'from: ' . $chat_signup;
                            ?>
                        </a>

                    <?php endforeach; ?>
                    <?php if (count($all_notifications) > 5) : ?>
                        <div class="text-center mt-3 mb-3">
                            <a href="<?= l('dashboard/home/inbox') ?>"><?= __('View all') ?></a>
                        </div>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="text-center mt-3">
                        <?= __('No notifications yet') ?>.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="notification-wrap2" data-toggle="tooltip" title="Shopping cart" data-bs-placement="bottom">
            <a href="<?= l('dashboard/order/cart') ?>">
                <i class="fa-regular fa-shopping-bag" aria-hidden="true"></i>
                <span><?= count($this->cart->contents()) ?></span>
            </a>
        </div>

        <!--<div class="notification-wrap2" data-toggle="tooltip" title="Messages" data-bs-placement="right">-->
            <!--<a href="<?= l('dashboard/message') ?>">-->
                <!--<i class="fa-regular fa-paper-plane" aria-hidden="true"></i>-->
                <!--<span><?//= $unseen_chat_count ?></span>-->
            <!--</a>-->
        <!--</div>-->

        <!-- GOOGLE TRANSLATOR -->
        <div id="google_translate_element"></div>

        <div class="profile-opt">
            <a href="javascript:;">
                <div class="icon-container">
                    <?php if ((isset($this->user_data['signup_logo_image']) && $this->user_data['signup_logo_image']) || (isset($this->user_data['signup_company_image']) && $this->user_data['signup_company_image'])) : ?>
                        <img src="<?php echo $this->model_signup->profileImage($this->user_data) ?>" alt="profile-user" class="rounded-circle thumb-xs" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                    <?php else : ?>
                        <img src="<?= g('images_root') . 'user.png' ?>" alt="profile-user" class="rounded-circle thumb-xs" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                    <?php endif; ?>
                    <div class="status-circle" style="<?= (isset($this->signup_info['signup_info_isonline']) && $this->signup_info['signup_info_isonline']) ? 'background-color: green' : ''; ?>">
                    </div>
                </div>
                <span>
                    <b>
                        <?php echo $this->model_signup->profileName($this->user_data, false) ?>
                        <?php if ((isset($this->user_data['signup_is_verified']) && isset($this->user_data['signup_vouched_token']) && $this->user_data['signup_is_verified'] && $this->user_data['signup_vouched_token']) || $this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_VERIFICATION)) : ?>
                            <span class="text-custom" data-toggle="tooltip" data-bs-placement="top" title="Verified account"><i class="fa fa-circle-check"></i></span>
                        <?php endif; ?>
                    </b>
                    <?= __($this->model_signup->getRawRole()) ?>
                </span>
                <i class="fa-regular fa-caret-down"></i>
            </a>
            <div class="drop-profile">
                <p class="d-block text-center border-bottom-1 pb-1 mb-1"><?php echo $this->user_data['signup_email']; ?></p>
                <a href="<?= l('dashboard/profile/detail/') . JWT::encode($this->userid, CI_ENCRYPTION_SECRET) . '/' . $this->model_signup->getRoleId() ?>"><i class="fa-regular fa-user"></i><?= __('My profile') ?></a>
                <a href="<?= l('dashboard/profile/create') ?>"><i class="fa-regular fa-user-edit"></i><?= __('Edit profile') ?></a>
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <a href="<?= l('dashboard/company/create') ?>"><i class="fa-regular fa-edit"></i><?= __('Edit company profile') ?></a>
                <?php endif; ?>
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <a href="<?= l('dashboard/profile/testimonial') ?>"><i class="fa-regular fa-quote-left"></i><?= __('Testimonials') ?></a>
                <?php endif; ?>
                <a href="<?= l('dashboard/profile/reset-password') ?>"><i class="fa-regular fa-lock"></i><?= __('Reset password') ?></a>
                <a href="<?= l('dashboard/profile/subscription') ?>"><i class="fa-regular fa-calendar"></i><?= __('My Subscription') ?></a>
                <a href="<?= l('dashboard/profile/promotions') ?>"><i class="fa-regular fa-gift"></i><?= __('Promotion offers') ?></a>
                <a href="<?= l('dashboard/profile/setting') ?>"><i class="fa-regular fa-cog"></i><?= __('Setting') ?></a>
                <a href="<?= l('logout') ?>"><i class="fa-regular fa-arrow-right-from-bracket"></i><?= __('Logout') ?></a>
                <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>
                    <hr class="m-0" />
                    <a href="<?= l('oauth2') ?>" data-toggle="tooltip" data-bs-placement="left" title="Expiry: <?= g('db.admin.token_expire_time') ? date('d M, Y H:i a', strtotime(g('db.admin.token_expire_time'))) : ''; ?>">
                        <svg style="width:20px;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <title>Zoom</title>
                            <path d="M4.585 13.607l-.27-.012H1.886l3.236-3.237-.013-.27a.815.815 0 00-.796-.796l-.27-.013H0l.014.27c.034.438.353.77.794.796l.27.013h2.43L.268 13.595l.014.269c.015.433.362.78.795.796l.27.013h4.046l-.014-.27c-.036-.443-.35-.767-.795-.795zm3.238-4.328h-.004a2.696 2.697 0 10.003 0zm1.141 3.841a1.619 1.619 0 11-2.289-2.288 1.619 1.619 0 012.289 2.288zM21.84 9.28a2.158 2.158 0 00-1.615.73 2.153 2.153 0 00-1.619-.732 2.148 2.148 0 00-1.208.37c-.21-.233-.68-.37-.949-.37v5.395l.27-.013c.45-.03.778-.349.796-.796l.013-.27v-1.889l.014-.27c.01-.202.04-.382.132-.54a1.078 1.079 0 011.473-.393 1.078 1.079 0 01.393.392c.093.16.12.34.132.54l.014.271v1.889l.013.269a.83.83 0 00.795.796l.27.013v-2.967l.012-.27c.01-.2.04-.384.134-.543.3-.514.96-.69 1.473-.39a1.078 1.079 0 01.393.393c.092.16.12.343.13.54l.015.27v1.889l.013.269c.028.443.35.77.796.796l.27.013v-3.237a2.158 2.158 0 00-2.16-2.156zm-10.263.788a2.697 2.698 0 103.811 3.816 2.697 2.698 0 00-3.811-3.816zm3.05 3.052a1.619 1.619 0 11-2.289-2.29 1.619 1.619 0 012.289 2.29z" />
                        </svg>
                        &nbsp;<?= __('Authorize Zoom') ?>
                    </a>
                    <a href="<?= l('box') ?>" data-toggle="tooltip" data-bs-placement="left" title="Expiry: <?= g('db.admin.expiry_time') ? date('d M, Y H:i a', strtotime(g('db.admin.expiry_time'))) : ''; ?>">
                        <svg style="width:20px;" class="logo-box" id="Layer_1" viewBox="0 0 40 21.6" xmlns="http://www.w3.org/2000/svg">
                            <path class="box-logo-svg" d="M39.7 19.2c.5.7.4 1.6-.2 2.1-.7.5-1.7.4-2.2-.2l-3.5-4.5-3.4 4.4c-.5.7-1.5.7-2.2.2-.7-.5-.8-1.4-.3-2.1l4-5.2-4-5.2c-.5-.7-.3-1.7.3-2.2.7-.5 1.7-.3 2.2.3l3.4 4.5L37.3 7c.5-.7 1.4-.8 2.2-.3.7.5.7 1.5.2 2.2L35.8 14l3.9 5.2zm-18.2-.6c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c-.1 2.6-2.2 4.6-4.7 4.6zm-13.8 0c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c0 2.6-2.1 4.6-4.7 4.6zM21.5 6.4c-2.9 0-5.5 1.6-6.8 4-1.3-2.4-3.9-4-6.9-4-1.8 0-3.4.6-4.7 1.5V1.5C3.1.7 2.4 0 1.6 0 .7 0 0 .7 0 1.5v12.6c.1 4.2 3.5 7.5 7.7 7.5 3 0 5.6-1.7 6.9-4.1 1.3 2.4 3.9 4.1 6.8 4.1 4.3 0 7.8-3.4 7.8-7.7.1-4.1-3.4-7.5-7.7-7.5z"></path>
                        </svg>
                        &nbsp;<?= __('Authorize Box') ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>