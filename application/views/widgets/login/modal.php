<div class="grid">
    <div style="display: none; padding: 44px !important;" id="animatedModal" class="animated-modal">
        <h4><?= __('Forgot Password') ?>?</h4>
        <form class="row g-3 needs-validation forget_form" action="javascript:;" novalidate method="POST">
            <div class="col-md-12">
                <label for="validationCustom01" class="form-label"><?= __('Enter your email') ?></label>
                <input type="email" name="signup[signup_email]" class="form-control" id="validationCustom01" placeholder="email@domain.com" required />
                <div class="valid-feedback">
                    <?= __('Looks good') ?>!
                </div>
                <div class="invalid-feedback">
                    <?= __('A valid email is required') ?>
                </div>
            </div>
            <div class="col-12">
                <button class="btn-dark-nn" type="submit" id="forget_form_btn"><?= __('Submit') ?></button>
            </div>
        </form>
    </div>
</div>

<div class="grid">
    <div style="display: none; padding: 44px !important;width:100%;height:100%" id="vouchedModal" class="animated-modal">
        <h5><?= __('Reverify your identity') ?>!</h5>
        <div id='vouched-element' style="height: 100%"></div>
    </div>
</div>