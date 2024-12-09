<form action="javascript:;" class="updatePhoneForm" method="POST" novalidate>
    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
    <div class="row mb-4 phoneConfirm-content">
        <?php if (
                isset($this->user_data['signup_is_confirmed']) &&
                !$this->user_data['signup_is_confirmed'] &&
                $this->model_config->getConfigValueByVariable('email_confirmation') &&
                ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_EMAIL, TRUE))
            ): ?>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                    <label><i class="fa fa-circle-check"></i>&nbsp;<?= __('Phone number has been verified successfully.') ?></label>
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <label>Confirm your email address</label><br />
                    <a href="javascript:;"
                        class="btn btn-custom resend_confirmation">Re-send email confirmation <i class="fa fa-arrow-right text-white font-12"></i>
                    </a>
                </div>
        <?php elseif (
                (
                    isset($this->user_data['signup_is_phone_confirmed']) &&
                    !$this->user_data['signup_is_phone_confirmed'] &&
                    $this->model_config->getConfigValueByVariable('phone_verification') &&
                    ($this->userid && !$this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_PHONE, TRUE))
                )
            ) : ?>
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                    <label class="text-dark"><?= __('Confirm your phone number') ?></label>
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <input class="form-control phone font-12" name="signup[signup_phone]" type="text" id="phone" <?= (isset($this->user_data['signup_is_phone_confirmed']) && $this->user_data['signup_phone'] && $this->user_data['signup_is_phone_confirmed']) ? 'readonly' : '' ?> value="<?= isset($this->user_data['signup_phone']) ? $this->user_data['signup_phone'] : '' ?>" required />
                    <small class="phone-error text-default invalid-feedback"></small>
                    <div class="mt-4">
                        <button type="submit" class="btn btn-custom updatePhone"><?= __('Update number') ?></button>
                        <button type="button" class="btn btn-custom validateBtn" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>" <?= ($this->signup_info['signup_info_phone_verification_attempt'] >= (int) g('db.admin.verification_attempt_limit')) ? 'disabled' : ''; ?>>
                            <?= __('Validate phone') ?>
                        </button>
                        <br />
                        <span class="attempt-limit-content">
                            <small class="text-danger font-13 mt-4"><?= ((int) g('db.admin.verification_attempt_limit') - ($this->signup_info['signup_info_phone_verification_attempt'])) . ' ' . __('attempt(s) left') ?></small>
                        </span>
                        <br />
                        <a class="triggerOTPModal d-none" data-fancybox data-animation-duration="700" data-src="#otpModal" href="javascript:;"><?= __('Relaunch Otp Pop-up') ?>!</a>
                    </div>
                </div>
        <?php else: ?>
            <?php if($this->router->class == 'verification'): ?>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <a href="<?= l('home/premium') ?>"
                        class="btn btn-custom" id="proceedBtn">Proceed <i class="fa fa-arrow-right text-white font-12"></i>
                    </a>
                </div>
            <?php elseif($this->router->class == 'profile'): ?>
                <div class="row mb-4 phoneConfirm-content">
                    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                        <label class="text-dark"><?= (isset($this->user_data['signup_is_phone_confirmed']) && $this->user_data['signup_is_phone_confirmed']) ? __('Phone number verified') : __('Confirm your phone number') ?></label>
                    </div>
                    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                        <input class="form-control phone font-12" name="signup[signup_phone]" type="text" id="phone" <?= (isset($this->user_data['signup_is_phone_confirmed']) && $this->user_data['signup_phone'] && $this->user_data['signup_is_phone_confirmed']) ? 'readonly' : '' ?> value="<?= isset($this->user_data['signup_phone']) ? $this->user_data['signup_phone'] : '' ?>" required />
                        <small class="phone-error text-danger invalid-feedback"></small>
                        <?php if (isset($this->user_data['signup_is_phone_confirmed']) && !$this->user_data['signup_is_phone_confirmed']) : ?>
                            <div class="mt-4">
                                <button type="button" class="btn btn-custom validateBtn" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>" <?= ($this->signup_info['signup_info_phone_verification_attempt'] >= (int) g('db.admin.verification_attempt_limit')) ? 'disabled' : ''; ?>>
                                    <?= __('Validate phone') ?>
                                </button>
                                &nbsp; <span><?= __('OR') ?> &nbsp;</span>
                                <button type="submit" class="btn btn-custom updatePhone"><?= __('Update number') ?></button>
                                <a class="triggerOTPModal float-right d-none" data-fancybox data-animation-duration="700" data-src="#otpModal" href="javascript:;"><?= __('Relaunch Otp Pop-up') ?>!</a>
                                <span class="attempt-limit-content">
                                    <p class="text-danger font-12"><?= ((int) g('db.admin.verification_attempt_limit') - ($this->signup_info['signup_info_phone_verification_attempt'])) . ' ' . __('attempt(s) left') ?></p>
                                </span>
                            </div>
                        <?php else : ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</form>

<?php if (isset($this->user_data['signup_is_phone_confirmed']) && !$this->user_data['signup_is_phone_confirmed']) : ?>

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
                            <button type="submit" class="btn btn-custom" id="otpFormBtn"><?= __('Verify') ?></button>
                            <!-- customBtn -->
                        </form>
                        <div>
                            <?= __('Didn\'t receive the code?') ?><br />
                            <a href="javascript:;" class="sendCodeBtn" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>"><?= __('Send code again') ?></a><br />
                            <a href="javascript:;" class="changeNumberBtn"><?= __('Change phone number') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

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

    $(document).ready(function() {

        // PHONE MASK //
        // intlTelInput
        const input = document.querySelector("#phone");
        const telInput = intlTelInput(input, {
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
            initialCountry: 'us',
            separateDialCode: false,
            nationalMode: false,
            autoHideDialCode: true,
        });

        /**
         * Method dynamicMask
         *
         * @param {string} placeholder
         *
         * @return void
         */
        function dynamicMask(placeholder) {
            if (placeholder != "" && placeholder != undefined) {
                var dynamoMask = placeholder.replace(/[0-9]/g, 0);
                $('#phone').mask(dynamoMask)
            } else {
                // call after 0.1 s
                setTimeout(function() {
                    var placeholder = $("#phone").attr('placeholder')
                    dynamicMask(placeholder)
                }, 100)
            }
        }

        // dyanmic mask on load
        var placeholder = $("#phone").attr('placeholder')
        dynamicMask(placeholder);

        // dyanmic mask on change
        $('#phone').on("countrychange", function(event) {
            var placeholder = $("#phone").attr('placeholder')
            dynamicMask(placeholder);
        })
        // PHONE MASK //

        var current_phone = '<?= isset($this->user_data['signup_phone']) ? $this->user_data['signup_phone'] : "" ?>';
        if(current_phone == $('.phone').val()) {
            $('.validateBtn').attr('disabled', false)
            $('.updatePhone').attr('disabled', true)
        } else {
            $('.validateBtn').attr('disabled', true)
            $('.updatePhone').attr('disabled', false)
        }

        //
        $('.phone').on('keyup keydown change focus', function() {
            if(current_phone == $('.phone').val()) {
                $('.validateBtn').attr('disabled', false)
                $('.updatePhone').attr('disabled', true)
            } else {
                $('.validateBtn').attr('disabled', true)
                $('.updatePhone').attr('disabled', false)
            }

            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                $('.phone-error').html("A valid Phone number is required!");
                error = true;

                $('.phone-error').addClass('show-invalid-feedback');
                $('.phone').addClass('force-invalid');
            } else {
                $('.phone-error').removeClass('show-invalid-feedback');
                $('.phone').removeClass('force-invalid');
            }
        })

        //
        $('body').on('submit', '.updatePhoneForm', function() {
            var error = false
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                $('.phone-error').html("A valid Phone number is required!");
                error = true;

                $('.phone-error').addClass('show-invalid-feedback');
                $('.phone').addClass('force-invalid');
            } else {
                $('.phone-error').html("");
                $('.phone').removeClass('force-invalid');
            }

            if (!$('.updatePhoneForm')[0].checkValidity() || error == true) {
                event.preventDefault()
                event.stopPropagation()
                $('.updatePhoneForm').addClass('was-validated');
                $('.updatePhoneForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.updatePhoneForm').removeClass('was-validated');
            }
    
            var updatePhone = '.updatePhone'
            var data = $(this).serialize();
            var url = base_url + 'dashboard/profile/update';
            
            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function (response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function () {
                        $(updatePhone).attr('disabled', true)
                        $(updatePhone).html('Updating ...')
                    },
                    complete: function() {
                        $(updatePhone).attr('disabled', false)
                        $(updatePhone).html('Update number')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            )
        })

        //
        $('body').on('click', '.validateBtn', function() {
            var error = false
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                $('.phone-error').html("A valid Phone number is required!");
                error = true;

                $('.phone-error').addClass('show-invalid-feedback');
                $('.phone').addClass('force-invalid');
            } else {
                $('.phone-error').html("");
                $('.phone').removeClass('force-invalid');
            }
            if (!$('.updatePhoneForm')[0].checkValidity() || error == true) {
                event.preventDefault()
                event.stopPropagation()
                $('.updatePhoneForm').addClass('was-validated');
                $('.updatePhoneForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.updatePhoneForm').removeClass('was-validated');
            }

            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/custom/validate_phone'
            if($('#phone').val()) {
                swal({
                    title: "<?= __('Number Confirmation') ?>",
                    text: $('#phone').val() + "<?='\n' . 'Is your phone number above correct?' ?>",
                    icon: "warning",
                    className: "text-center",
                    buttons: ["<?= __('Edit') ?>", "<?= __('Yes') ?>"],
                }).
                then((isConfirm) => {

                    if (isConfirm) {
                        
                        new Promise((resolve, reject) => {
                            $.ajax({
                                type: "POST",
                                url: url,
                                data: data,
                                dataType: 'JSON',
                                async: true,
                                success: function(response) {
                                    resolve(response)
                                },
                                complete: function(xhr, txt) {
                                    hideLoader()
                                },
                                beforeSend: function() {
                                    showLoader()
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                                }
                            });
                        }).then(
                            function(response) {
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
                                if (response.refresh) {
                                    $(".attempt-limit-content").load(location.href + " .attempt-limit-content>*", "");
                                }
                            }
                        )
                    } else {
                        $('#phone').focus();
                    }
                })
            } else {
                swal({
                    title: "Error",
                    text: "Enter a valid phone number",
                    icon: "error",
                }).then(() => {
                    $('#phone').focus();
                })
            }
        })

        //
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

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize();
            var url = base_url + 'dashboard/custom/validatePhoneOtp'
            
            new Promise((resolve, reject) => {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    dataType: 'JSON',
                    async: true,
                    success: function(response) {
                        resolve(response)
                    },
                    complete: function(xhr, txt) {
                        $('#otpFormBtn').attr('disabled', false)
                        $('#otpFormBtn').html('Verify')
                    },
                    beforeSend: function() {
                        $('#otpFormBtn').attr('disabled', true)
                        $('#otpFormBtn').html('Verifying ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $('.otpForm').each(function() {
                            this.reset();
                        });
                        AdminToastr.success(response.txt)
                        $('.fancybox-close-small').click();
                        $(".banner-frm").load(location.href + " .banner-frm>*", "");
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
            $('.triggerOTPModal').addClass('d-none')
        })

        // send code again btn
        $('body').on('click', '.sendCodeBtn', function() {
            var data = {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/custom/validate_phone'
            
            if($('#phone').val()) {
                new Promise((resolve, reject) => {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        dataType: 'JSON',
                        async: true,
                        success: function(response) {
                            resolve(response)
                        },
                        complete: function(xhr, txt) {
                            $('.sendCodeBtn').html('Send code again')
                        },
                        beforeSend: function() {
                            $('.sendCodeBtn').html('Sending ...')
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        }
                    });
                }).then(
                    function(response) {
                        if (response.status) {
                            AdminToastr.success(response.txt)
                        } else {
                            AdminToastr.error(response.txt)
                        }
                    }
                )
            } else {
                swal({
                    title: "Error",
                    text: "Enter a valid phone number",
                    icon: "error",
                }).then(() => {
                    $('#phone').focus();
                })
            }
        })

        //
        $('body').on('click', '.changeNumberBtn', function() {
            $('.fancybox-close-small').click()
            $('#phone').focus();
        })
    })
</script>
