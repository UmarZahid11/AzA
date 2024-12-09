<div class="dashboard-content">
    <a class="float-right" href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
        View invoices
    </a>
    <i class="fa-regular fa-calendar"></i>
    <h4><?= __('Subscription') ?></h4>
    <hr />
    <div class="container banner-frm">
        <?php $this->load->view("widgets/subscription/index"); ?>
    </div>
</div>
