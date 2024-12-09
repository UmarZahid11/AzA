<style>
    .banner-frm {
        height: calc(100vh - 360px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
</style>
<section class="canvs-sec">
	<div class="container">
		<div class="col-md-12 p-0">
			<div class="banner-frm">

                <?php if (
                    isset($this->user_data['signup_is_confirmed']) &&
                    !$this->user_data['signup_is_confirmed'] &&
                    $this->model_config->getConfigValueByVariable('email_confirmation') &&
                    ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_EMAIL, TRUE))
                ): ?>
                        <h2>Verify your Email</h2>
                <?php elseif (
                        isset($this->user_data['signup_is_phone_confirmed']) &&
                        !$this->user_data['signup_is_phone_confirmed'] &&
                        $this->model_config->getConfigValueByVariable('phone_verification') &&
                        ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_PHONE, TRUE))
                    ) : ?>
                        <h2>Verify your phone number</h2>
                <?php else: ?>
                    <h2>You are good to go!</h2>
                <?php endif; ?>

                <?php $this->load->view('widgets/verification/form.php'); ?>
                
            </div>
        </div>
    </div>
</section>

<script>

    $(document).ready(function() {
        $('body').on('click', '#proceedBtn', function() {
            $('#proceedBtn').html('Proceeding&nbsp;<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
        })
    })
</script>
