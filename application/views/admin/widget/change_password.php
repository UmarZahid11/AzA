<? global $config;
?>
<form class="cmxform form-horizontal tasi-form" id="" method="POST"
      action="<?= $config['base_url'] . "admin/" . "signup/update-password"?>">
    <div class="form-body">

        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            You have some form errors. Please check below.
        </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button>
            Your form validation is successful!
        </div>

        <div class="form-group ">
            <label class="control-label col-md-2 ">
                Password<span class="required">* </span>
            </label>
            <div class="col-md-3">
                <input class=" form-control " id="signup-signnup_firstname" name="signup[signup_password]" type="password" value="" required="">
                <input type="hidden" name="signup[signup_id]" value="<?= $form_data['signup']['signup_id'] ?>">
                <input type="hidden" name="signup[signup_password_old]" value="<?=$form_data['signup']['signup_password']?>">
            </div>
        </div>

    </div>

    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
                <button type="submit" name="submit" value="SaveNEdit" class="btn green">Save and Continue</button>
            </div>
        </div>
    </div>

</form>