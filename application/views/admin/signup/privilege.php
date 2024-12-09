<?php global $config; ?>

<div class="inner-page-header">
    <h1><?= humanize($class_name) ?> <small>Settings</small></h1>
    <small>Adjust privilege(s) for <?= $this->model_signup->signupName($signup, FALSE) . ' (' . $signup['signup_email'] . ')' ?></small>
</div>

<div class="">
    <form id="privilege_form" action="javascript:;" method="POST" novalidate>
        <input type="hidden" name="_token" />
        <input type="hidden" name="signup_id" value="<?= $signup['signup_id'] ?>" />
        <?php foreach ($signup_bypass_privilege as $key => $value) : ?>
            <div class="form-group">
                <?php if($key != 6): ?>
                    <label>Bypass <?= $value['label'] ?> verificaition <span class="text-danger">*</span></label>
                <?php else: ?>
                    <label><?= $value['label'] ?> <span class="text-danger">*</span> <?php echo ($key == 6) && $signup['signup_is_verified'] ? '(user has already been verified)' : '' ?> </label>
                <?php endif; ?>
                <select class="form-select" name="signup_bypass_privilege[<?= $key ?>]" required <?php echo ($key == 6) && $signup['signup_is_verified'] ? 'disabled' : '' ?>>
                    <option value="">Select</option>
                    <option value="<?= STATUS_ACTIVE ?>" <?= $value['status'] ? 'selected' : '' ?>>Yes</option>
                    <option value="<?= STATUS_INACTIVE ?>" <?= !$value['status'] ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        <?php endforeach; ?>

        <div class="form-group">
            <button type="submit" class="btn" id="privilege_form_btn">Save</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('body').on('submit', '#privilege_form', function() {
            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }
            
            var privilege_form_btn = '#privilege_form_btn'
            
            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'signup/savePrivileges'

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
                        $(privilege_form_btn).attr('disabled', true)
                        $(privilege_form_btn).html('Saving ...')
                    },
                    complete: function() {
                        $(privilege_form_btn).attr('disabled', false)
                        $(privilege_form_btn).html('Save')
                    }
                })
    		}).then(
    		    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                    } else {
                        AdminToastr.error(response.txt)
                    }
    		    }
		    )
        })
    })
</script>