<style>
    .dealwrapper { background: #ffffff; border-radius: 8px; -webkit-box-shadow: 0px 0px 50px rgba(0,0,0,0.15); -moz-box-shadow: 0px 0px 50px rgba(0,0,0,0.15); box-shadow: 0px 0px 50px rgba(0,0,0,0.15); position: relative;}
    .list-group h4 { font-size: 18px; margin-top: 6px;  margin-bottom: 10px;}
    .list-group p { font-size: 13px; line-height: 1.4; margin-bottom: 10px; font-style: italic;}
    .list-group-item { border: 1px solid rgba(221, 221, 221, 0.25);}

    .ribbon-wrapper { width: 88px; height: 88px; overflow: hidden; position: absolute; top: -3px; right: -3px; z-index: 3;}
    .ribbon-tag { text-align: center; -webkit-transform: rotate(45deg); -moz-transform: rotate(45deg); -ms-transform: rotate(45deg); -o-transform: rotate(45deg); position: relative; padding: 6px 0; left: -4px; top: 15px; width: 120px; color: #ffffff; -webkit-box-shadow: 0px 0px 3px rgba(0,0,0,0.3); -moz-box-shadow: 0px 0px 3px rgba(0,0,0,0.3); box-shadow: 0px 0px 3px rgba(0,0,0,0.3); text-shadow: rgba(255,255,255,0.5) 0px 1px 0px; background: #8204aa; }

    .ribbon-tag:before, .ribbon-tag:after { content: ""; border-top: 3px solid #8204aa; border-left: 3px solid transparent; border-right: 3px solid transparent; position:absolute; bottom: -3px;}
    .ribbon-tag:before { left: 0;}
    .ribbon-tag:after { right: 0;}
    .list-group-item.active {     background-color: #e99cff; border-color: #e99cff; }
</style>

<div class="dashboard-content">
    <a href="<?= l('dashboard/profile/subscription') ?>" class="float-right">My subscription</a>
    <i class="fa-regular fa-gift"></i>
    <h4>Promotion</h4>
    <hr />
    <div class="organation-listing mt-4">
        <div class="container">
            <div class="row">
               
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php if(isset($promotions) && !empty($promotions)) : ?>
                        <div class="dealwrapper purple">
                            <?php foreach($promotions as $key => $promotion) : ?>
                                <div class="ribbon-wrapper">
                                    <div class="ribbon-tag">
                                        <?php if(!$promotion['signup_promotion_status']) : ?>
                                            Expired!
                                        <?php else: ?>
                                            <?= price($promotion['signup_promotion_price']) ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="<?= (!$promotion['signup_promotion_status']) ? 'javascript:;' : ($promotion['signup_promotion_type'] == 'discount' ? 'javascript:;' : $promotion['signup_promotion_url']) ?>" data-id="<?= $promotion['signup_promotion_id'] ?>" class="list-group-item active" id="<?= $promotion['signup_promotion_type'] == 'promotion' ? '' : 'availPromotionDiscount' ?>">
                                        <h4 class="list-group-item-heading"><?= $promotion['signup_promotion_title'] ?><p>Avail subscription for <?= price($promotion['signup_promotion_price']) ?> per <?= SUBSCRIPTION_INTERVAL_TYPE ?> </p></h4>                                       
                                        <p class="list-group-item-text"><?= $promotion['signup_promotion_description'] ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span>All promotions offers will be shown here.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#availPromotionDiscount').on('click', function() {
            swal({
                title: "<?= __('Avail this offer?') ?>",
                text: 'Are you sure to avail this promotional offer?',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {

                    var data = {'_token': $('meta[name="csrf-token"]').attr('content'), 'id': $(this).data('id')}
                    var url = base_url + 'dashboard/profile/availPromotion'

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
                                    location.href = base_url + "dashboard/profile/subscription"
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
        })
    })
</script>