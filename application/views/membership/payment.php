<!-- banner start -->

<section class="banner inner-banner">

    <!-- <img src="<?= g('images_root') ?>inner-banner.jpg" alt="">

<div class="baner-cnt"> -->

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription Payment' ?>

                    </h1>

                </div>

            </div>
            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

    </div>

    <!-- </div> -->

</section>

<!-- banner end -->

<section class="member-ship-sec">
    
    <div class="container">
        <?php 
            switch($merchant) {
                case STRIPE:
                    if(isset($merchant_session) && $merchant_session && isset($merchant_session->url) && $merchant_session->url != NULL) {
                        redirect($merchant_session->url);      
                    } else {
                        echo '<label class="text-danger">Oops! An error occurred while processing your payment request!</label><br/>';
                        if($error) {
                            echo 'Message: '. $errorMessage;
                        }
                    }
                    break;
                case PAYPAL:
                    echo '<div class="row">';
                    echo '<div class="col-md-6 offset-3">';
                    echo '<div id="paypal-button-container"></div>';
                    echo '</div>';
                    echo '</div>';
                    break;
            }
        ?>
    </div>

</section>

<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENTID ?>&vault=true&intent=subscription&disable-funding=paylater,credit,card"></script>

<script>
    if($('#paypal-button-container').length) {
        paypal.Buttons({
            createSubscription: function(data, actions) {
                return actions.subscription.create({
                    'plan_id': '<?= (isset($merchant_session) && $merchant_session && property_exists($merchant_session, 'id')) ? $merchant_session->id : '' ?>'
                });
            },
            onApprove: function(data, actions) {
                console.log(data)
                location.href = '<?= base_url() . 'membership/result/' . $membership['membership_id'] . '/' .  ORDER_SUCCESS . '/' ?>' + data.subscriptionID + '<?= '/' . PAYPAL ?>';
            }
        }).render('#paypal-button-container');
    }
</script>