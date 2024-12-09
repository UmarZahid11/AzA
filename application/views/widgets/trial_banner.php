<?php
$trial_days = (g('db.admin.trial_days') ?? STRIPE_TRIAL_PERIOD_DAYS);
$showBtn = true;
$showBanner = (g('db.admin.enable_subscription_trial') && g('db.admin.show_trial_banner')) ? 0 : 0;
$trial_banner = g('db.admin.trial_banner') ?? 'Free for customers, Entrepreneurs enjoy {days} days free trial and your entrepreneur subscription will automatically begin when your trial ends!';

if ($this->userid) {
    switch (true) {
        case ($this->user_data['signup_type'] == ROLE_1 && strtotime($this->user_data['signup_trial_expiry']) < strtotime(date('Y-m-d H:i:s'))):
            break;
		case ($this->user_data['signup_type'] == ROLE_1 && strtotime($this->user_data['signup_trial_expiry']) > strtotime(date('Y-m-d H:i:s'))):
            $period_end = $this->user_data['signup_trial_expiry'];
            $date1 = new DateTime("now");
            $date2 = new DateTime(date('Y-m-d H:i:s', strtotime($period_end)));
            $trial_days = $date1->diff($date2)->days;
            //
            $trial_banner = g('db.admin.trial_end_banner') ?? 'Your trial ends in {days} days!';
            break;
        case ($this->model_signup->hasRole(ROLE_3) && $this->user_data['signup_subscription_status'] == SUBSCRIPTION_TRIAL && strtotime($this->user_data['signup_trial_expiry']) > strtotime(date('Y-m-d H:i:s'))):
            $showBtn = false;
            //
            // $period_end = $this->user_data['signup_subscription_current_period_end'];
            $period_end = $this->user_data['signup_trial_expiry'];
            $date1 = new DateTime("now");
            $date2 = new DateTime(date('Y-m-d H:i:s', strtotime($period_end)));
            $trial_days = $date1->diff($date2)->days;
            //
            $trial_banner = g('db.admin.trial_end_banner') ?? 'Your trial ends in {days} days!';
            break;
        default:
            $showBanner = 0;
    }
}

$banner_text = str_replace('{days}', $trial_days, $trial_banner);
$showBtn = (($this->router->class == 'membership') || !$showBtn) ? 0 : 1;
?>

<script>
    $(function() {

        function appendBanner(bannerText, showBtn = false) {

            bannerHtml = '\
                <div class= "trial-banner text-center">\
                    <p class="banner-text">' + bannerText + '</p>\
                ';
            if (showBtn) {
                bannerHtml += '<a href="' + base_url + 'membership' + '" class="btn btn-primary btn-icon banner-button">\
                        See details\
                    </a>\
                ';
            }

            bannerHtml += '\
                <span class="toggler-close"><i class="fa fa-close text-white"></i></span>\
            </div>\
            ';
            $("body").prepend(bannerHtml)
        }

        var bannerText = '<?= $banner_text ?>';
        var showBanner = <?= $showBanner ?>;

        if (showBanner) {
            showBtn = <?= $showBtn ?>;
            appendBanner(bannerText, showBtn);
        }

        $(".trial-banner .toggler-close").on("click", function() {
            $(".trial-banner").slideUp(300);
        });
    });
</script>