<?php if (g('db.admin.phone_whatsapp') && 0) : ?>
    <?php $phone_whatsapp = preg_replace('/[^\dxX]/', '', g('db.admin.phone_whatsapp')); ?>
    <!--<a href="https://wa.me/<?= $phone_whatsapp ?>" class="float" target="_blank">-->
    <!--    <i class="fa fa-whatsapp my-float"></i>-->
    <!--</a>-->
<?php endif; ?>

<?php if (
    isset($this->user_data['signup_is_confirmed']) &&
    !$this->user_data['signup_is_confirmed'] &&
    $this->model_config->getConfigValueByVariable('email_confirmation') &&
    ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_EMAIL, TRUE))
): ?>
    <?php $this->load->view('widgets/email_confirmation_banner.php'); ?>
<?php else: ?>
    <?php $this->load->view('widgets/trial_banner.php'); ?>
<?php endif; ?>
