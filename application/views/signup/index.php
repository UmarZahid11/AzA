<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">

<style>
    /* specifically for vouched Popup */
    .fancybox-container {
        z-index: 999;
    }
    .intl-tel-input.allow-dropdown input, .intl-tel-input.allow-dropdown input[type="text"], .intl-tel-input.allow-dropdown input[type="tel"], .intl-tel-input.separate-dial-code input, .intl-tel-input.separate-dial-code input[type="text"], .intl-tel-input.separate-dial-code input[type="tel"] {
        background-color: transparent;
        height: 45px;
        border-radius: 6px;
        background-repeat: no-repeat;
    }
    .iti {
        width: 100%;
    }
    input#phone {
            background-repeat: no-repeat;
    }
    .phone::placeholder {
        color: white;
    }
    #roleSelection {
        height: 45px;
        height: 45px;
        background-color: transparent;
        border: 1px solid #fff;
        border-radius: 6px;
        padding: 13px 15px;
        color: #fff;
    }
    #roleSelection option {
        color: #000;
    }
    input.InputElement.is-empty.Input.Input--empty::placeholder {
        color: #fff;
    }
    .was-validated .form-control.cpassword:invalid, .was-validated .form-control.password:invalid,
    .was-validated .form-control.cpassword:valid, .was-validated .form-control.password:valid {
        background-position: right 35px center !important;
    }
</style>

<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Sign up' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="canvs-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5 p-0">
				<div class="banner-frm">
	                <form class="login-form" id="signup-form" method="POST" action="javascript:;" novalidate autocomplete="">

                        <input type="hidden" name="_token" value="" />
                        <input type="hidden" name="redirect_url" value="<?= isset($_GET['redirect_url']) ? $_GET['redirect_url'] : '' ?>" />
                        <input type="hidden" name="signup[signup_vouched_token]" />
                        <input type="hidden" name="signup[signup_vouched_response]" />
                        <input type="hidden" name="signup[signup_is_verified]" />

                        <?php if (isset($cms[0]['cms_page_content'])) : ?>
                            <h2><?= ($cms[0]['cms_page_title']) ?></h2>
                            <? // html_entity_decode($cms[0]['cms_page_content']) ?>
                        <?php else : ?>
                            <h2>Register Your Account</h2>
                            <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quod maiores temporibus doloremque illo ipsum? Nulla hic</p>
                        <?php endif; ?>

						<div class="inptfield">
							<label>First name</label>
	                        <input type="text" name="signup[signup_firstname]" class="form-control firstname inputForm" required pattern="[a-z,A-Z]{3,100}" minlength="3" maxlength="100" />
                            <div id="fnameValidationFeedback" class="invalid-tooltip">A valid first name is required with minimum length of 3.</div>
						</div>
						<div class="inptfield">
							<label>Last name</label>
                            <input type="text" name="signup[signup_lastname]" class="form-control lastname inputForm" required pattern="[a-z,A-Z]{3,100}" minlength="3" maxlength="100" />
                            <div id="lnameValidationFeedback" class="invalid-tooltip">A valid last name is required with minimum length of 3</div>
						</div>
						<div class="inptfield">
							<label>Email</label>
                            <input type="email" name="signup[signup_email]" class="form-control email inputForm" required maxlength="255" />
                            <div id="emailValidationFeedback" class="invalid-tooltip">A valid email address is required</div>
						</div>
						<div class="inptfield">
							<label>Password</label>
                            <div class="search-hd-box">
                                <input type="password" name="signup[signup_password]" class="form-control password inputForm" required minlength="6" maxlength="255" />
                                <div id="passwordValidationFeedback" class="invalid-tooltip"></div>
                                <a href="javascript:;" class="eye-patch">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
						</div>
						<div class="inptfield">
							<label>Retype password</label>
                            <div class="search-hd-box">
                                <input type="password" name="cpassword" class="form-control cpassword inputForm" required minlength="6" maxlength="255" />
                                <div id="cpasswordValidationFeedback" class="invalid-tooltip">The confirmation password field is required.</div>
                                <a href="javascript:;" class="eye-patch">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </div>
						</div>
						<div class="inptfield">
							<label>Cell/Mobile Phone</label>
                            <input type="tel" name="signup[signup_phone]" id="phone" class="form-control phone inputForm" required maxlength="30" />
                            <div id="phoneValidationFeedback" class="invalid-tooltip">A valid phone number is required.</div>
                        </div>

						<div class="inptfield">
							<label>Address</label>
                            <input type="text" name="signup[signup_address]" id="address" class="form-control address inputForm" required minlength="5" maxlength="100" />
                            <div id="addressValidationFeedback" class="invalid-tooltip">A valid address is required.</div>
						</div>

						<div class="inptfield d-none">
							<label>Role</label>
                            <select class="form-select inputForm" id="roleSelection" name="signup[signup_type]">
                                <option value="<?= ROLE_1 ?>" data-role="<?= RAW_ROLE_1 ?>" selected><?= RAW_ROLE_1 ?></option>
                                <option value="<?= ROLE_3 ?>" data-role="<?= RAW_ROLE_3 ?>"><?= RAW_ROLE_3 ?></option>
                            </select>
						</div>

						<div class="inptfield" id="cardField">
							<label>Credit or debit card</label>
    	                    <div id="card-element" class="form-control inputForm">
                            </div>
                            <div id="cardValidationFeedback" class="invalid-tooltip"></div>
						</div>

	                    <div class="reg-frombtm" data-toggle="tooltip" data-bs-placement="right" title="Please agree to our terms and conditions.">
                            <label>
                                <input type="checkbox" class="terms-check" />
                                <span>By creating an account, You agree to our <a href="<?= l('terms-and-conditions') ?>" target="_blank"><u>Terms &amp; Conditions</u></a></span>
                            </label>
                        </div>
                        <button type="submit" class="btn-dark-nn" id="signup-submit">Create an account</button>

						<div class="mt-3 text-center">
							<a href="<?= l('login') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>">Already have an account?</a>
						</div>
                        <hr />
                        <div class="mt-3 text-center">
                            <a class="vouchedModalBtn d-none" href="javascript:;" data-fancybox data-animation-duration="700" data-src="#vouchedModal"><?= __('Relaunch vouched') ?>&nbsp;<span class="fa fa-question-circle" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Verify your identity with vouched.') ?>"></span></a>
                        </div>

                    </form>
                </div>
            </div>
			<!-- <div class="col-md-7 p-0">
			    <div class="banner-img">
			        <img src="<?= base_url() ?>assets/front_assets/images/signupimg.png" alt="Aza banner image" />
			    </div>
			</div> -->

        </div>
    </div>
    <div class="grid">
        <div style="display: none; padding: 44px !important;width:100%;height:100%" id="vouchedModal" class="animated-modal">
            <h5><?= __('Verify your identity') ?>!</h5>
            <div id='vouched-element' style="height: 100%"></div>
            <button class="skipVerification btn btn-custom"><?= __('Skip verification') ?></button>
        </div>
    </div>
</section>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://static.vouched.id/widget/vouched-2.0.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

<script>
    function mount_stripe(card) {
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        $('#cardField').show()
        return true;
    }

    function unmount_stripe(card) {
        card.unmount();
        $('#cardField').hide()
        return false;
    }

    $(document).ready(function() {

        // STRIPE
        // Create a Stripe client.
        var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

        // Create an instance of Elements.
        var elements = stripe.elements();

        // is mounted
        var is_mounted;

        // Custom styling can be passed to options when creating an Element.
        // (Note that this demo uses a wider set of styles than the guide below.)
        var style = {
            base: {
                color: '#000',
                lineHeight: '18px',
                fontSmoothing: 'antialiased',
                fontSize: '14px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {
            style: style
        });

        // Mount stripe based on role selected on name=signup[signup_type]
        if($('select[name="signup[signup_type]"]').find(':selected').attr('data-role') == '<?= RAW_ROLE_3 ?>') {
            is_mounted = mount_stripe(card)
        } else if($('select[name="signup[signup_type]"]').find(':selected').attr('data-role') == '<?= RAW_ROLE_1 ?>') {
            is_mounted = unmount_stripe(card)
        }

        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('cardValidationFeedback');
            if (event.error) {
                displayError.style.display = 'block';
                displayError.textContent = event.error.message;
            } else {
                displayError.style.display = 'none';
                displayError.textContent = '';
            }
        });
        // STRIPE

        // PHONE MASK START //
        // intlTelInput
        const input = document.querySelector("#phone");
        const telInput = intlTelInput(input, {
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
            initialCountry: 'us',
            separateDialCode: false,
            nationalMode: false,
            autoHideDialCode: true,
        });

        input.addEventListener("countrychange", function() {
            var selectCountryData = (telInput.getSelectedCountryData())
            $('#phone').val(selectCountryData.dialCode)
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
        // PHONE MASK END //

        async function appendToken(formId) {
            return new Promise((resolve, reject) => {
                if(is_mounted) {
                    var success;

                    stripe.createToken(card).then(function(result) {
                        var errorElement = document.getElementById('cardValidationFeedback');
                        if (result.error) {
                            errorElement.style.display = 'block';
                            errorElement.textContent = result.error.message;
                            resolve(false)
                        } else {
                            const form = document.getElementById(formId);
                            const hiddenInput = document.createElement('input');
                            hiddenInput.setAttribute('type', 'hidden');
                            hiddenInput.setAttribute('name', 'stripeToken');
                            hiddenInput.setAttribute('value', result.token.id);
                            form.appendChild(hiddenInput);
                            resolve(true)
                        }
                    });
                } else {
                    resolve(true)
                }
            })
        }
        
        async function validateSignupServer() {
            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = new FormData(document.getElementById("signup-form"));
            var url = base_url + 'signup/validateSignup';
            return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    enctype: 'multipart/form-data',
                    async: true,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function () {
                        $('#signup-submit').attr('disabled', true)
                        $('#signup-submit').html('Processing ...')
                    },
                    complete: function() {
                        $('#signup-submit').attr('disabled', false)
                        $('#signup-submit').html('Create an account')
                    }
                });
            });
        }

        //
        $('.phone').on('keyup keydown change focus', function() {
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                $('#phoneValidationFeedback').show();
                $('.phone').addClass('force-invalid');
            } else {
                $('#phoneValidationFeedback').hide();
                $('.phone').removeClass('force-invalid');
            }
        })

        //
        $('.password').on('keyup keydown change focus', function() {
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=(.*[0-9]))(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{6,100}$/; 

            if(!passwordRegex.test($('.password').val())) {
                $('.password').addClass('force-invalid');
                $('#passwordValidationFeedback').show();
                $('#passwordValidationFeedback').html("Enter a strong password with letters, special characters and numbers.");
            } else {
                $('.password').removeClass('force-invalid');
                $('#passwordValidationFeedback').hide();
                if ($('.cpassword').val() && $('.cpassword').val() != $('.password').val()) {
                    $('.password').addClass('force-invalid');
                    $('#passwordValidationFeedback').show();
                    $('#passwordValidationFeedback').html("Mismatched password.");
                } else {
                    $('.password').removeClass('force-invalid');
                    $('#passwordValidationFeedback').hide();
                    $('.cpassword').removeClass('force-invalid');
                    $('#cpasswordValidationFeedback').hide();
                }
            }
        })

        $('.cpassword').on('keyup keydown change focus', function() {
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=(.*[0-9]))(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{6,100}$/; 

            if(!passwordRegex.test($('.cpassword').val())) {
                $('.cpassword').addClass('force-invalid');
                $('#cpasswordValidationFeedback').show();
                $('#cpasswordValidationFeedback').html("Enter a strong password with letters, special characters and numbers.");
            } else {
                $('.cpassword').removeClass('force-invalid');
                $('#cpasswordValidationFeedback').hide();

                if ($('.cpassword').val() != $('.password').val()) {
                    $('.cpassword').addClass('force-invalid');
                    $('#cpasswordValidationFeedback').show();
                    $('#cpasswordValidationFeedback').html("Mismatched password.");
                } else {
                    $('.cpassword').removeClass('force-invalid');
                    $('#cpasswordValidationFeedback').hide();
                    $('.password').removeClass('force-invalid');
                    $('#passwordValidationFeedback').hide();
                }
            }
        })

        //
        $(function() {
            $("#address").autocomplete({
                source: function(request, response) {
                    $.getJSON(base_url + 'job/mapbox', {
                            _token: '<?= $this->csrf_token ?>',
                            term: request.term
                        },
                        response);
                },
                select: function(event, ui) {
                    event.preventDefault();
                    $("#address").val(ui.item.id);
                }
            });
        });

        // TERMS CHECKBOX CHECK!
        if ($('.terms-check').is(':checked')) {
            $('#signup-submit').attr('disabled', false)
        } else {
            $('#signup-submit').attr('disabled', true)
        }

        $('.terms-check').on('change', function() {
            if ($(this).is(':checked')) {
                $('#signup-submit').attr('disabled', false)
            } else {
                if ($('.reg-frombtm').length > 0) {
                    $('.reg-frombtm').tooltip('show')
                }
                $('#signup-submit').attr('disabled', true)
            }
        })

        /**
         * Method validateSignupForm
         *
         */
        async function validateSignupForm() {
            //
            var passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=(.*[0-9]))(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{6,100}$/; 

            return new Promise((resolve, reject) => {

                // class name (form is for signup)
                if (!$('#signup-form')[0].checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                    $('#signup-form').addClass('was-validated');
                    $('#signup-form').find(":invalid").first().focus();
                    resolve(false);
                } else {
                    $('#signup-form').removeClass('was-validated');
                }
                
                if(!passwordRegex.test($('.password').val())) {
                    $('.password').addClass('force-invalid');
                    $('#passwordValidationFeedback').show();
                    $('#passwordValidationFeedback').html("Enter a strong password with letters, special characters and numbers.");
                    resolve(false)
                } else {
                    $('.password').removeClass('force-invalid');
                    $('#passwordValidationFeedback').hide();
                    //
                    if ($('.cpassword').val() != $('.password').val()) {
                        $('.password').addClass('force-invalid');
                        $('#passwordValidationFeedback').show();
                        $('#passwordValidationFeedback').html("Mismatched password.");
                        resolve(false)
                    } else {
                        $('.password').removeClass('force-invalid');
                        $('#passwordValidationFeedback').hide();
                    }
                }

                if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                    $('.phone').addClass('force-invalid');
                    $('#phoneValidationFeedback').show();
                    resolve(false);
                } else {
                    $('#phoneValidationFeedback').hide();
                    $('.phone').removeClass('force-invalid');
                }

                resolve(true)
            })
        }

        /**
         * Method signupFormSubmit
         *
         */
        function signupFormSubmit() {

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = new FormData(document.getElementById("signup-form"));
            var url = base_url + 'signup/save_signup';
            var type = 'json'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    dataType: type,
                    async: true,
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        AdminToastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                    },
                    beforeSend: function() {
                        showLoader()
                    },
                    complete: function() {
                        hideLoader()
                    }
                });
            }).then(
                function(response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt);
                        if (response.redirect_url != undefined) {
                            location.href = response.redirect_url;
                        } else {
                            location.href = base_url;
                        }
                    }
                }
            )
        }

        // submit
        $('#signup-form').submit(function() {
            var formId = 'signup-form';

            validateSignupForm().then(
                function(validated) {
                    if(validated) {
                        appendToken(formId).then(
                            function(tokenAppended) {
                                if(tokenAppended) {
                                    validateSignupServer().then(
                                        function(response) {
                                            if(!response.status) {

                                                //
                                                // toastr.error(response.txt)

                                                //
                                                jsonResponse = response.error
                                                if (jsonResponse['signup[signup_phone]']) {
                                                    $('.phone').focus()
                                                    $('.phone').addClass('force-invalid');
                                                    $('#phoneValidationFeedback').html(jsonResponse['signup[signup_phone]']);
                                                    $('#phoneValidationFeedback').show();
                                                } else {
                                                    $('.phone').removeClass('force-invalid');
                                                    $('#phoneValidationFeedback').hide();
                                                }
                                                if(jsonResponse['signup[signup_email]']) {
                                                    $('.email').focus()
                                                    $('.email').addClass('force-invalid');
                                                    $('#emailValidationFeedback').html(jsonResponse['signup[signup_email]']);
                                                    $('#emailValidationFeedback').show();
                                                } else {
                                                    $('.email').removeClass('force-invalid');
                                                    $('#emailValidationFeedback').hide();
                                                }

                                            } else {
                                                // init_vouched()
                                                var vouched = Vouched({
                                                    showProgressBar: true,
                                                    // Optional verification properties.
                                                    verification: {
                                                        // verify the user's information
                                                        firstName: $('.firstname').val(),
                                                        lastName: $('.lastname').val(),
                                                        // used for the crosscheck feature
                                                        email: $('.email').val(),
                                                        phone: $('.phone').val()
                                                    },
                                                    liveness: 'straight',
                                                    //sandbox: '<?//= VOUCHED_SANDBOX_ENV ?>',
                        
                                                    appId: '<?= VOUCHED_PUBLIC_KEY ?>',
                                                    // your webhook for POST verification processing
                                                    // callbackURL: 'VOUCHED_CALLBACK_URL',
                        
                                                    // mobile handoff
                                                    // crossDevice: true,
                                                    // crossDeviceQRCode: true,
                                                    // crossDeviceSMS: true,
                                                    enableCrossCheck: true,
                                                    enableDarkWeb: true,
                                                    enablePhysicalAddress: true,
                                                    enableIPAddress: true,
                        
                                                    // called when the verification is completed.
                                                    onDone: (job) => {
                                                        // console.log("Scanning complete", {
                                                        //     job: job
                                                        // });
                                                        // token used to query jobs
                                                        // console.log("Scanning complete", {
                                                        //     token: job.token
                                                        // });
                        
                                                        // job.token
                                                        $('input[name="signup[signup_vouched_token]"]').val(job.token)
                                                        $('input[name="signup[signup_vouched_response]"]').val(JSON.stringify(job))
                                                        $('input[name="signup[signup_is_verified]"]').val(1)
                        
                                                        // An alternative way to update your system based on the
                                                        // results of the job. Your backend could perform the following:
                                                        // 1. query jobs with the token
                                                        // 2. store relevant job information such as the id and
                                                        //    success property into the user's profile
                                                        // fetch(`/yourapi/idv?job_token=${job.token}`);
                        
                                                        // Redirect to the next page based on the job success
                                                        if (job.result.success) {
                                                            $('.skipVerification').html('Submit verification')
                                                        } else {
                                                            $('.fancybox-close-small').trigger('click');
                                                            swal("Error", "Identity verification failed!", "error");
                                                            vouched.unmount("#vouched-element");
                                                            // window.location.replace("https://localhost/aza-life/vouched/index");
                                                        }
                                                    },
                        
                                                    // theme
                                                    theme: {
                                                        name: 'avant',
                                                    },
                                                });
                                                vouched.mount("#vouched-element");
                        
                                                $('.vouchedModalBtn').trigger('click')
                                                $('.vouchedModalBtn').removeClass('d-none')                                                
                                            }
                                        }
                                    )
                                }
                            }
                        )
                    }
                }
            )
        });

        //
        $('.skipVerification').on('click', function() {
            var formId = 'signup-form';

            $('.fancybox-close-small').trigger('click');
            validateSignupForm().then(
                function(validated) {
                    if(validated) {
                        appendToken(formId).then(
                            function(tokenAppended) {
                                if(tokenAppended) {
                                    validateSignupServer().then(
                                        function(response) {
                                            if(!response.status) {
                                                toastr.error(response.txt)
                                            } else {
                                                signupFormSubmit()
                                            }
                                        }
                                    )
                                }
                            }
                        )
                    }
                }
            )
        })
        
        //
        $('.eye-patch').on('click', function() {
            $(this).find('i').toggleClass('fa-eye')
            $(this).find('i').toggleClass('fa-eye-slash')
            if ($(this).find('i').hasClass('fa-eye-slash')) {
                $(this).parent().find('input[type=password]').attr('type', 'text')
            } else {
                $(this).parent().find('input[type=text]').attr('type', 'password')
            }
        })

        // Mount stripe based on role selected on name=signup[signup_type]
        $('select[name="signup[signup_type]"]').on('change', function() {
            console.log($(this).find(':selected').attr('data-role'))
            if($(this).find(':selected').attr('data-role') == '<?= RAW_ROLE_1 ?>') {
                is_mounted = unmount_stripe(card)
            } else if($(this).find(':selected').attr('data-role') == '<?= RAW_ROLE_3 ?>') {
                is_mounted = mount_stripe(card)
            }
        })
    })
</script>