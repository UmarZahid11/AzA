<div class="dashboard-content">
    <i class="fa-regular fa-lock"></i>
    <h4><?= __('Reset Password') ?></h4>
    <hr>
    <div class="create-profile-form">
        <div class="row">
            <form id="saveForm" action="javascript:void(0)" method="POST" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>" novalidate>
                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="profilelist">
                            <label><?= __('Old password') ?> <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="old_pass" placeholder="Enter old password" required minlength="6" />
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="profilelist">
                            <label><?= __('New password') ?> <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="new_pass" placeholder="Enter new password" required minlength="6" />
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="profilelist">
                            <label><?= __('Confirm new password') ?> <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="confirm_pass" placeholder="Re-type new password" required minlength="6" />
                        </div>
                    </div>

                </div>
                <div class="row mt-4">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                        <div class="profilelist">
                            <button class="btn btn-custom" type="submit" id="submitInfo"><?= __('Reset') ?></button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <!-- container -->
</div>

<a class="triggerOTPModal float-right d-none" data-fancybox data-animation-duration="700" data-src="#otpModal" href="javascript:;"><?= __('Relaunch Otp Pop-up') ?>!</a>

<?php if (isset($this->user_data['signup_is_phone_confirmed']) && $this->user_data['signup_is_phone_confirmed']) : ?>

    <div class="grid">

        <div style="display: none;" id="otpModal" class="animated-modal">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 mt-5 bgWhite">
                        <h4><?= __('Please enter the 4-digit verification code we sent via SMS') ?>:</h4>
                        <span><?= __('(we want to make sure it\'s you before moving forward)') ?>.</span>
                        <br />
                        <img src="<?= g('images_root') . 'phonelink-ring.gif' ?>" class="img-responsive phone-ring mt-3" />
                        <form action="javascript:;" class="mt-3 otpForm text-center" novalidate>
                            <input type="hidden" name="_token" />
                            <input class="otp" type="text" name="otp-1" oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1 required autocomplete="off" />
                            <input class="otp" type="text" name="otp-2" oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1 required autocomplete="off" />
                            <input class="otp" type="text" name="otp-3" oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 required autocomplete="off" />
                            <input class="otp" type="text" name="otp-4" oninput='digitValidate(this)' onkeyup='tabChange(4)' maxlength=1 required autocomplete="off" />
                            <hr class="mt-4">
                            <button type="submit" class="btn btn-custom" id="verifyBtn"><?= __('Verify') ?></button>
                            <!-- customBtn -->
                        </form>
                        <div>
                            <?= __('Didn\'t receive the code?') ?><br />
                            <a href="javascript:;" class="sendCodeBtn" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>"><?= __('Send code again') ?></a><br />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<script>

    let digitValidate = function(ele) {
        ele.value = ele.value.replace(/[^0-9]/g, '');
    }

    let tabChange = function(val) {
        let ele = document.querySelectorAll('.otp');
        if (ele[val - 1].value != '') {
            ele[val].focus()
        } else if (ele[val - 1].value == '') {
            ele[val - 2].focus()
        }
    }

    function sendOtp() {
        var url = base_url + 'dashboard/custom/sendOtp'
        jQuery.ajax({
            url: url,
            type: "POST",
            data: {id: $('#saveForm').data('id')},
            async: true,
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    swal({
                        title: "Success",
                        text: response.txt,
                        icon: "success",
                    }).then(() => {
                        $('.triggerOTPModal').removeClass('d-none')
                        $('.triggerOTPModal').click()
                    })
                } else {
                    swal("Error", response.txt, "error");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
            },
            beforeSend: function() {
                showLoader()
            },
            complete: function() {
                hideLoader()
            }
        });
    }

    async function verifyOtp() {
        //
        $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
        //
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: base_url + 'dashboard/custom/verifyOtp',
                type: "POST",
                data: $('.otpForm').serialize(),
                async: true,
                dataType: "json",
                success: function (response) {
                    resolve(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function () {
                    $('#verifyBtn').attr('disabled', true)
                    $('#verifyBtn').html('Verifying')
                },
                complete: function() {
                    $('#verifyBtn').attr('disabled', false) 
                    $('#verifyBtn').html('Verify')
                    $('.triggerOTPModal').addClass('d-none')
                }
            });
        });
    }
    
    async function updatePassword() {
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: "<?php echo l('dashboard/profile/update_password'); ?>",
                type: "POST",
                data: $("#saveForm").serialize(),
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() { 
                    $('#submitInfo').attr('disabled', true)
                    $('#submitInfo').html('Resetting') 
                },
                complete: function() { 
                    $('#submitInfo').attr('disabled', false) 
                    $('#submitInfo').html('Reset')
                }
            });
        });
    }

    $(document).ready(function() {
        
        $("#saveForm").submit(function() {
            if (!$('#saveForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#saveForm').addClass('was-validated');
                hideLoader()
                $('#saveForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#saveForm').removeClass('was-validated');
            }
    
            swal({
                title: "<?= __('Number Confirmation') ?>",
                text: "<?= (isset($this->user_data['signup_phone']) ? $this->user_data['signup_phone'] : '') . '\n' . 'Is your phone number above correct?' ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No, Edit') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    sendOtp()
                } else {
                    location.href= base_url + "dashboard/profile/setting"
                }
            })
        });
        
        // send code again btn
        $('body').on('click', '.sendCodeBtn', function() {
            sendOtp()
        })
        
        $('body').on('submit', '.otpForm', function() {
            if (!$('.otpForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.otpForm').addClass('was-validated');
                $('.otpForm').find(":invalid").first().focus();

                //
                $('.otpForm .otp').removeClass('danger');
                $('.otpForm').find(":invalid").first().addClass('danger');
                return false;
            } else {
                $('.otpForm .otp').removeClass('danger');
                $('.otpForm').removeClass('was-validated');
            }
    
            verifyOtp().then(
                function(response) {
                    if (response.status) {
                        $('.otpForm').each(function() {
                            this.reset();
                        });
                        
                        //
                        AdminToastr.success(response.txt)
                        $('.fancybox-close-small').click();
                        
                        updatePassword().then(
                            function(response) {
                                if (response.status) {
                                    $('#saveForm').each(function() {
                                        this.reset();
                                    });
                                    swal("Success", response.txt, "success");
                                } else {
                                    swal("Error", response.txt, "error");
                                }
                            }
                        )
                    } else {
                        AdminToastr.error(response.txt)
                    }                    
                }
            )
        })
    })

</script>