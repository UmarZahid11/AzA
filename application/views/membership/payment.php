<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Payment' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="member-ship-sec">
    
    <div class="container">
        <?php 
            if(!$free_membership) {
                if($membership_updated) {
                    // redirect to result success
                    redirect(l('membership/result/' . JWT::encode($membership['membership_id']) . '/' . ORDER_SUCCESS . '/' . $this->user_data['signup_session_id']));
                } else {
                    switch($merchant) {
                        case STRIPE:
                            if(isset($merchant_session) && $merchant_session && isset($merchant_session->url) && $merchant_session->url != NULL) {
                                redirect($merchant_session->url);
                            } else {
                                echo '<label class="text-danger">Oops! An error occurred while processing your payment request!</label><br/>';
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
                }
            } else {
                redirect(l('membership/result/' . JWT::encode($membership['membership_id']) . '/' . ORDER_SUCCESS . '/' . JWT::encode($order['order_id']) . '/' . FREE));
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