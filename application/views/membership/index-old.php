<!-- banner start -->

<!--<section class="banner inner-banner">-->

<!--    <div class="container">-->

<!--        <div class="row justify-content-center">-->

<!--            <div class="col-lg-6">-->
<!--                <div class="banner-cont inner-banner-text wow fadeInLeft">-->
<!--                    <h1>-->
<!--                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?>-->
<!--                    </h1>-->
<!--                </div>-->
<!--            </div>-->

<!--            <div class="col-lg-6">-->
<!--                <div class="inner-banner">-->
<!--                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--                </div>-->
<!--            </div>-->

<!--        </div>-->

<!--    </div>-->

<!--</section>-->

<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?></h2>
            </div>
            
        </div>
    </div>
    
</section>

<!-- banner end -->

<section class="member-ship-sec">

    <div class="container">

        <?php if (isset($membership) && count($membership) > 0) : ?>

            <div class="row head-ss">

                <div class="col-4"></div>

                <?php foreach ($membership as $key => $value) : ?>

                    <div class="col-4">

                        <?php if (isset($value['membership_id'])) : ?>

                            <b><?= $value['membership_title'] ?? '...'; ?></b>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <?php foreach ($membership_attribute as $key => $value) : ?>

            <div class="row">

                <div class="col-4">

                    <?php echo $value['membership_attribute_name']; ?>

                </div>

                <?php foreach ($membership as $key_membership => $value_membership) : ?>

                    <div class="col-4">
                        <p>
                            <?php echo $this->model_membership_pivot->pivot_value($value_membership['membership_id'], $value['membership_attribute_id']); ?>
                        </p>
                    </div>

                <?php endforeach; ?>

            </div>

        <?php endforeach; ?>

        <!-- <ul class="nav nav-tabs row" id="myTab" role="tablist"> -->

            <!-- <li class="nav-item col-md-4" role="presentation">

                <a class="nav-link active" id="month-tab" data-bs-toggle="tab" href="#month" role="tab" aria-controls="month" aria-selected="true">
                    <?//= __(SUBSCRIPTION_INTERVAL_TITLE_1) ?>
                </a>

            </li> -->


            <!-- <li class="nav-item col-md-4" role="presentation">

                <a class="nav-link" id="quater-tab" data-bs-toggle="tab" href="#quater" role="tab" aria-controls="quater" aria-selected="true">
                    <?//= __(SUBSCRIPTION_INTERVAL_TITLE_2) ?>
                </a>

            </li>

            <li class="nav-item col-md-4" role="presentation">

                <a class="nav-link" id="year-tab" data-bs-toggle="tab" href="#year" role="tab" aria-controls="year" aria-selected="true">
                    <?//= __(SUBSCRIPTION_INTERVAL_TITLE_3) ?>
                </a>

            </li> -->

        <!-- </ul> -->

        <div>
            <label>
                <input type="checkbox" class="terms-check" name="subscription-check" />
                <span>
                    By clicking this checkbox agree to our 
                    <a href="<?= l('terms-and-conditions') ?>" target="_blank">
                        <u>Terms &amp; Conditions</u>
                    </a>
                     before purchasing this subscription.
                </span>
            </label>
        </div>

        <div class="tab-content" id="myTabContent">

            <div class="tab-pane fade show active" id="month" role="tabpanel" aria-labelledby="month-tab">

                <?php echo $this->model_membership->displayStripeButtons(SUBSCRIPTION_INTERVAL_1); ?>
                
                <?php echo $this->model_membership->displayPaypalButtons(SUBSCRIPTION_INTERVAL_1); ?>

            </div>

            <!-- <div class="tab-pane fade" id="quater" role="tabpanel" aria-labelledby="quater-tab">

                <?php //echo $this->model_membership->displayStripeButtons(SUBSCRIPTION_INTERVAL_2); ?>

            </div>

            <div class="tab-pane fade" id="year" role="tabpanel" aria-labelledby="year-tab">

                <?php //echo $this->model_membership->displayStripeButtons(SUBSCRIPTION_INTERVAL_3); ?>

            </div> -->

        </div>
        
        <?php if($this->userid == 0 || (isset($this->user_data['signup_type']) && $this->user_data['signup_type'] != ROLE_3)): ?>
            <span>
                <?= 'Try' . ' <b><a href="'.l('membership/payment/') . ROLE_3 . '/' . JWT::encode(SUBSCRIPTION_INTERVAL_1).'">' . RAW_ROLE_3 . '</a></b> ' . (g('db.admin.trial_days') ?? STRIPE_TRIAL_PERIOD_DAYS) . ' ' . 'days free, Then'  . ' <b>' . price($this->model_membership_pivot->raw_pivot_value(ROLE_3, COST_ATTRIBUTE)) . '</b> ' . 'per month' ?>
            </span>
        <?php endif; ?>

    </div>

</section>







<script>
    $(document).ready(function() {
        $(window).scrollTop($(".member-ship-sec").offset().top);
        
        if($('input[name=subscription-check]').is(':checked')) {
            $('.btn-mem').removeClass('disabled')
        } else {
            $('.btn-mem').addClass('disabled')
        }
        
        $('input[name=subscription-check]').on('change', function() {
            if($('input[name=subscription-check]').is(':checked')) {
                $('.btn-mem').removeClass('disabled')
            } else {
                $('.btn-mem').addClass('disabled')
            }
        })

        $('body').on('click', '.btn-mem', function() {
            $('.btn-mem').addClass('disabled')
            $('.btn-mem').html('Processing ...')
        })
    })
</script>