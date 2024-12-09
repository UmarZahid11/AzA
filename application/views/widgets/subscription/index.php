<?php if(!$signup_subscription_response && $this->model_signup->hasPremiumPermission()) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 12C19.5 16.1421 16.1421 19.5 12 19.5C7.85786 19.5 4.5 16.1421 4.5 12C4.5 7.85786 7.85786 4.5 12 4.5C16.1421 4.5 19.5 7.85786 19.5 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM11.25 13.5V8.25H12.75V13.5H11.25ZM11.25 15.75V14.25H12.75V15.75H11.25Z" fill="#080341"/>
        </svg>
        The system has failed to fetch your subscription details
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row mt-3">
    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
        <label><?= __('Active subscription') ?></label>
    </div>
    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
        <p><?= $this->model_membership->membership_by_pk($this->model_signup->getRoleId()) ?></p>
    </div>
</div>

<?php if($signup_subscription_response && isset($signup_subscription_response->plan) && $signup_subscription_response->plan) : ?>
    <div class="row mt-3">
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <label><?= __('Subscription cost') ?></label>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <p><?= price($signup_subscription_response->plan->amount / 100) . __(' per ') . ($signup_subscription_response->plan->interval) ?> </p> 
        </div>
    </div>
<?php else: ?>
    <div class="row mt-3">
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <label><?= __('Subscription cost') ?></label>
        </div>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <p><?= price($this->model_membership_pivot->raw_pivot_value((int) $this->user_data['signup_membership_id'], COST_ATTRIBUTE)) ?> </p> 
        </div>
    </div>
<?php endif; ?>

<?php if (isset($this->user_data['signup_membership_status']) && $this->user_data['signup_membership_status'] == SUBSCRIPTION_ACTIVE && $this->user_data['signup_type'] != ROLE_1) : ?>

    <?php if($signup_subscription_response && isset($signup_subscription_response->cancel_at) && $signup_subscription_response->cancel_at) : ?>
        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <label><?= __('Cancel at') ?></label>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <p><?= date('d M, Y', $signup_subscription_response->cancel_at) ?></p>
            </div>
        </div>  
    <?php elseif($signup_subscription_response && isset($signup_subscription_response->current_period_end) && $signup_subscription_response->current_period_end): ?>
        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <label><?= __('Next invoice') ?></label>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <p><?= date('d M Y', $signup_subscription_response->current_period_end) ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if($signup_subscription_response && isset($signup_subscription_response->cancel_at_period_end)) : ?>
        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <label><?= __('Subscription will be cancled at period end') ?></label>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <p><?= $signup_subscription_response->cancel_at_period_end ? 'Yes' : 'No' ?></p>
            </div>
        </div>  
    <?php endif; ?>

    <?php if($signup_subscription_response && isset($signup_subscription_response->status)) : ?>

        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <label><?= __('Status') ?></label>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <p><?= ucfirst($signup_subscription_response->status) ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if($signup_subscription_response && isset($signup_subscription_response->trial_end)) : ?>
        <div class="row mt-3">
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <label><?= __('End of trial') ?></label>
            </div>
            <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                <p><?= date('d M, Y', $signup_subscription_response->trial_end) ?></p>
            </div>
        </div>  
    <?php endif; ?>

    <?php if($signup_subscription_response && isset($signup_subscription_response->cancel_at_period_end) && !$signup_subscription_response->cancel_at_period_end) : ?>
        <?php if ($signup_subscription_response->id) : ?>
            <div class="row mt-3">
                <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                    <label><?= __('Cancel your subscription') ?></label>
                </div>
                <?php if($signup_subscription_response): ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                        <button class="btn btn-danger cancelSubscription" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Cancel your current subscription? Note: There won\'t be any refunds.') ?>"><?= __('Cancel subscription') ?></button>
                    </div>
                <?php else: ?>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                        <button class="btn btn-danger forceCancelSubscription" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Cancel your current subscription? Note: There won\'t be any refunds.') ?>"><?= __('Force cancel subscription') ?></button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="col-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
            <button class="btn btn-danger forceCancelSubscription" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Cancel your current subscription? Note: There won\'t be any refunds.') ?>"><?= __('Force cancel subscription') ?></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<script>

    $(document).ready(function() {
        $('.cancelBtn').click(function() {
            $('.fancybox-close-small').click()
        });

        //
        $('body').on('click', '.cancelSubscription', function() {
            swal({
                title: "<?= __('Are you sure?') ?>",
                text: "<?= __('You are about to cancel your subscription!') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Ok') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    location.href = '<?= l('dashboard/profile/cancel_subscription') ?>';
                } else {
                    swal("<?= __('Cancelled') ?>", "<?= __('Action aborted') ?>", "error");
                }
            })
        });

        //
        $('body').on('click', '.forceCancelSubscription', function() {
            swal({
                title: "<?= __('Are you sure?') ?>",
                text: "<?= __('You are about to cancel your subscription!') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Ok') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    location.href = '<?= l('dashboard/profile/force_cancel_subscription') ?>';
                } else {
                    swal("<?= __('Cancelled') ?>", "<?= __('Action aborted') ?>", "error");
                }
            })
        });
    })

</script>