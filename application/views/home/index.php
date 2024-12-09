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
    .invalid-tooltip {
        position: inherit !important;
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
        background-position: right 45px center !important;
    }
    .socail-linkss > a {
        height: 40px;
        width: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 2px solid;
        margin-right: 10px;
    }
    
    .socail-linkss > a:hover {
        background: #69178a;
        color: #fff;
        border-color: #69178a;
    }
</style>


<section class="boxsef-sec" id="home">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="baxast1">
                    <img src="<?= g('images_root') ?>rotoe.png" alt="">
                    <h3>Are you juggling <b>multiple tools</b> and platforms to run your business?</h3>
                    <div class="commts-igm">
                        <img src="<?= g('images_root') ?>commts.png" alt="">
                    </div>
                    <span>It's time for a <b>change.</b></span>
                </div>
            </div>
            <div class="col-md-5">
                <div class="baxast2">
                    <h3><span>AzAverze</span> is the ultimate <b>all-in-one solution</b> designed specifically for
                        small business entrepreneurs in the professional services sector.</h3>
                    <p>We bring together everything you need to <span>manage, market, and grow</span> your business,
                        <span>all in one centralized platform.</span>
                    </p>
                    <div class="text-end mt-5">
                        <a href="<?= l('home/subscription') ?>" class="btn-grdient btn-1">Get Started Today <i
                                class="fa-regular fa-arrow-right-long"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="features-secs">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Work with our best <span>features!</span></h2>
                <div class="rounded-iconns">
                    <svg width="300" height="200" viewBox="0 0 300 200">
                        <path id="curve" d="M 50 150 A 100 100 0 0 1 250 150" fill="transparent" />
                        <text width="500">
                            <textPath xlink:href="#curve" startOffset="72%" text-anchor="middle" font-size="6">
                                YOU COULD DO MORE!
                            </textPath>
                        </text>
                    </svg>
                    <img src="<?= g('images_root') ?>rouned-iconns.png" alt="">
                    <a href="<?= l('home/subscription') ?>" class="btn-1">Get Started Today</a>
                </div>
            </div>
            <div class="col-md-6 position-relative">
                <div class="rounded-parent">
                    <h4>provides financial account and identity verification</h4>
                    <div class="icon-wrapper">
                        <div class="rounded-icon">
                            <img src="<?= g('images_root') ?>avb1.webp" alt="">
                        </div>
                    </div>
                </div>
                <div class="rounded-parent rd2">
                    <h4>provides financial account and identity verification</h4>
                    <div class="icon-wrapper">
                        <div class="rounded-icon">
                            <img src="<?= g('images_root') ?>avb2.webp" alt="">
                        </div>
                    </div>
                </div>
                <div class="rounded-parent rd3">
                    <h4>provides financial account and identity verification</h4>
                    <div class="icon-wrapper">
                        <div class="rounded-icon">
                            <img src="<?= g('images_root') ?>avb4.webp" alt="">
                        </div>
                    </div>
                </div>
                <div class="rounded-parent rd4">
                    <h4>provides financial account and identity verification</h4>
                    <div class="icon-wrapper">
                        <div class="rounded-icon">
                            <img src="<?= g('images_root') ?>avb3.webp" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="why-secas">
    <div class="container">
        <div class="why-oxwa">
            <div class="row">
                <div class="col-md-6">
                    <h2>Why <b>AzAverze?</b></h2>
                    <div class="text-center">
                        <img src="<?= g('images_root') ?>why-shepre.png" alt="">
                    </div>
                </div>
                <div class="col-md-6">
                    <p>Running a small business is challenging enough without the hassle of managing multiple
                        subscriptions and tools. AzAverze integrates the most powerful business applications
                        directly into our platform, so you can focus on what matters—growing your business.</p>
                    <a href="<?= l('home/subscription') ?>">Get Started Today <i class="fa-light fa-arrow-right-long"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="lines--secc">
    <div class="splashes">
        <img src="<?= g('images_root') ?>splashes.png" alt="">
    </div>
    <div class="container">
        <div class="cont-eseca">
            <h2><span>What You Get</span> with AzAverze</h2>
            <p>A Comprehensive Suite of Business <br> Management Applications</p>
            <a href="<?= l('home/subscription') ?>" class="btn-1">Get Started Today <i class="fa-light fa-arrow-right-long"></i></a>
        </div>
        <div class="line-box-srw">
            <img src="<?= g('images_root') ?>line-cureved.png" alt="">
            <div class="line-voas">
                <img src="<?= g('images_root') ?>l-icon1.png" alt="">
                <div class="line-vont">
                    <h5>Direct-to-Customer Sales:</h5>
                    <p>Sell your services directly to customers through our seamless e-commerce functionality,
                        making it easier than ever to close deals.</p>
                </div>
            </div>
            <div class="line-voas lnn2">
                <img src="<?= g('images_root') ?>l-icon2.png" alt="">
                <div class="line-vont">
                    <h5>Customer Relationship Management (CRM):</h5>
                    <p>Build and maintain strong customer relationships with our integrated CRM tools, designed to
                        help you nurture leads and convert them into loyal clients.</p>
                </div>
            </div>
            <div class="line-voas lnn3">
                <img src="<?= g('images_root') ?>l-icon3.svg" alt="">
                <div class="line-vont">
                    <h5>Financial Verification with Plaid:</h5>
                    <p>Connect your financial accounts securely and verify identities with Plaid, making
                        transactions smooth and secure.</p>
                </div>
            </div>
            <div class="line-voas lnn4">
                <img src="<?= g('images_root') ?>l-icon4.png" alt="">
                <div class="line-vont">
                    <h5>Secure Document Management with Box:</h5>
                    <p>Store, organize, and access your important documents from anywhere. Box integration ensures
                        your files are safe and easy to find.</p>
                </div>
            </div>
            <div class="line-voas lnn5">
                <img src="<?= g('images_root') ?>l-icon5.png" alt="">
                <div class="line-vont">
                    <h5>Efficient Project Management with Monday.com:</h5>
                    <p>Keep your projects on track and your team aligned with Monday.com's intuitive project
                        management tools, directly accessible within AzAverze.</p>
                </div>
            </div>
            <div class="line-voas lnn6">
                <img src="<?= g('images_root') ?>l-icon6.png" alt="">
                <div class="line-vont">
                    <h5>Accounting Made Easy with QuickBooks:</h5>
                    <p>Manage your finances effortlessly with integrated QuickBooks. Track expenses, handle
                        invoicing, and keep your books balanced—all without leaving AzAverze.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="promotion-seca">
    <div class="container">
        <div class="container">
            <div class="text-center">
                <h2>Promote Your Services with <b>Powerful Marketing Tools</b></h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="bxfg-bott">
                        <img src="<?= g('images_root') ?>bx1.webp" alt="">
                        <h4>Upload Short Marketing Videos</h4>
                        <p>Capture attention and showcase your expertise with easy-to-create videos. AzAverze's
                            platform allows you to upload and share videos directly with your audience.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bxfg-bott">
                        <img src="<?= g('images_root') ?>bx2.jpg" alt="">
                        <h4>Host Engaging Webinars</h4>
                        <p>Connect with your audience in real-time through webinars. Educate, inspire, and convert
                            leads into clients with our integrated webinar hosting tools.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bxfg-bott">
                        <img src="<?= g('images_root') ?>bx3.jpg" alt="">
                        <h4>Promote Your Services</h4>
                        <p>Market your offerings directly on AzAverze, reaching your ideal clients with targeted
                            promotions that drive results.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="connected-seaa">
    <div class="container">
        <div class="contisa-scea">
            <img src="<?= g('images_root') ?>hand-shake.webp" alt="">
            <h2>Stay Connected and Ready to Close <span>Deals</span></h2>
            <p>AzAverze isn't just a platform—it's a community. Our social media functionality connects you with
                potential customers, while our e-commerce tools enable direct sales of your services. Stay engaged
                with your audience, understand their needs, and be ready to close deals—all from one platform.</p>
        </div>
    </div>
</section>
<section class="grains-mjor-secc">
    <div class="container">
        <div class="text-center">
            <h2><span>Major Gains</span> for a <b>Minor Investment</b></h2>
        </div>
        <div class="imges-thjty">
            <div class="rnd-boxsa">
                <img src="<?= g('images_root') ?>rnd1.webp" alt="">
            </div>
            <div class="rnd-boxsa as2">
                <img src="<?= g('images_root') ?>rnd2.webp" alt="">
            </div>
            <div class="rnd-boxsa as3">
                <img src="<?= g('images_root') ?>rnd3.webp" alt="">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Unlock the full potential of your business with AzAverze for just <span>$399.99/month. </span> No
                    hidden fees, no separate subscriptions—just a single investment in your success.</p>
            </div>
            <div class="col-md-6 text-end">
                <a href="<?= l('home/subscription') ?>" class="btn-grdient btn-1">Get Started Today <i
                        class="fa-regular fa-arrow-right-long"></i></a>
            </div>
        </div>
    </div>
</section>

<?php $this->load->view("widgets/membership/index"); ?>

<section class="bgdd-seca">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2>Ready to <b>Revolutionize</b> Your Business?</h2>
            </div>
            <div class="col-md-6 text-end">
                <p>Sign up now and take the first step towards a more efficient, connected, and profitable future with AzAverze.</p>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="<?= l('home/subscription') ?>" class="btn-1">Get Started Today</a>
        </div>
    </div>
</section>
<?php if ($this->userid == 0): ?>
    <section class="caont-formsec" id="signup">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="signapbo">
                        <i class="fa-regular fa-arrow-right-long"></i>
                        <h2>Sign Up</h2>
                        <p>Get excited in a few clicks and get the latest update from the rising best business partner around the world!</p>
                        <a href="<?= l('login') ?>" class="btnajsss">
                            Already have an account? <br> Log In Instead
                        </a>
                    </div>
                </div>
                <div class="col-md-9 ps-5 pt-5">
    
                    <form class="login-form" id="signup-form" method="POST" action="javascript:;" novalidate autocomplete="">
    
                        <input type="hidden" name="_token" value="" />
                        <input type="hidden" name="redirect_url" value="<?= isset($_GET['redirect_url']) ? $_GET['redirect_url'] : '' ?>" />
                        <input type="hidden" name="signup[signup_vouched_token]" />
                        <input type="hidden" name="signup[signup_vouched_response]" />
                        <input type="hidden" name="signup[signup_is_verified]" />
    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-ofrm">
                                    <label>First name</label>
                                    <input type="text" name="signup[signup_firstname]" class="form-control firstname" required pattern="[a-z,A-Z]{3,100}" minlength="3" maxlength="100" />
                                    <div id="fnameValidationFeedback" class="invalid-tooltip">A valid first name is required with minimum length of 3.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-ofrm">
                                    <label>Last name</label>
                                    <input type="text" name="signup[signup_lastname]" class="form-control lastname" required pattern="[a-z,A-Z]{3,100}" minlength="3" maxlength="100" />
                                    <div id="lnameValidationFeedback" class="invalid-tooltip">A valid last name is required with minimum length of 3</div>
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <div class="field-ofrm">
                                    <label>M.I</label>
                                    <input type="text" name="signup[signup_middlename]" class="form-control middelname" required pattern="[a-z,A-Z]{3,100}" minlength="3" maxlength="100" />
                                    <div id="mnameValidationFeedback" class="invalid-tooltip">A valid middel name is required with minimum length of 3</div>
                                </div>
                            </div> -->
                            <div class="col-md-7">
                                <div class="field-ofrm">
                                    <label>E-mail Address</label>
                                    <input type="email" name="signup[signup_email]" class="form-control email" required maxlength="255" />
                                    <div id="emailValidationFeedback" class="invalid-tooltip">A valid email address is required</div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="field-ofrm">
                                    <label>Phone Number</label>
                                    <input type="tel" name="signup[signup_phone]" id="phone" class="form-control phone" required maxlength="30" />
                                    <div id="phoneValidationFeedback" class="invalid-tooltip">A valid phone number is required.</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="field-ofrm">
                                    <label>Present Address</label>
                                    <input type="text" name="signup[signup_address]" id="address" class="form-control address" required minlength="5" maxlength="100" />
                                    <div id="addressValidationFeedback" class="invalid-tooltip">A valid address is required.</div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="field-ofrm">
                                    <label>Nationality</label>
                                    <input type="text" name="signup[signup_nationality]" id="nationality" class="form-control nationality" required minlength="3" maxlength="100" />
                                    <div id="nationalityValidationFeedback" class="invalid-tooltip">A valid nationality is required.</div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="field-ofrm">
                                    <label>Sex</label>
                                    <input type="text" name="signup[signup_gender]" id="gender" class="form-control gender" required minlength="3" maxlength="100" />
                                    <div id="sexValidationFeedback" class="invalid-tooltip">A valid sex is required.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-ofrm">
                                    <label>Password</label>
                                    <div class="search-hd-box">
                                        <input type="password" name="signup[signup_password]" class="form-control password" required minlength="6" maxlength="255" />
                                        <a href="javascript:;" class="eye-patch">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                    <div id="passwordValidationFeedback" class="invalid-tooltip"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-ofrm">
                                    <label>Confirm Password</label>
                                    <div class="search-hd-box">
                                        <input type="password" name="cpassword" class="form-control cpassword" required minlength="6" maxlength="255" />
                                        <a href="javascript:;" class="eye-patch">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                    <div id="cpasswordValidationFeedback" class="invalid-tooltip">The confirmation password field is required.</div>
                                </div>
                            </div>
                            <div class="col-md-12 d-none">
                                <div class="field-ofrm">
                                    <label>Role</label>
                                    <select class="form-select" id="roleSelection" name="signup[signup_type]">
                                        <option value="<?= ROLE_1 ?>" data-role="<?= RAW_ROLE_1 ?>" selected><?= RAW_ROLE_1 ?></option>
                                        <option value="<?= ROLE_3 ?>" data-role="<?= RAW_ROLE_3 ?>"><?= RAW_ROLE_3 ?></option>
                                    </select>
                                </div>
    						</div>
                            <div class="col-md-7">
                                <div class="checkbox">
                                    <label class="d-flex">
                                        <input type="checkbox" class="terms-check" />
                                        <p>I agree with all <a href="<?= l('terms-and-conditions') ?>" target="_blank"><b>Terms and Conditionas</b></a> <br> and <a href="<?= l('privacy') ?>" target="_blank"><b>Privay Policies</b></a> in this site.</p>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <button type="submit" class="submit-form" id="signup-submit">Submit <i class="fa-solid fa-caret-right"></i></button>
                            </div>
                        </div>
                    </form>
                    <hr />
                    <div class="mt-3 text-center">
                        <a class="vouchedModalBtn btn-custom d-none" href="javascript:;" data-fancybox data-animation-duration="700" data-src="#vouchedModal" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Verify your identity with vouched.') ?>">
                            <?= __('Relaunch vouched') ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="grid">
        <div style="display: none; padding: 44px !important;width:100%;height:100%" id="vouchedModal" class="animated-modal">
            <h5><?= __('Verify your identity') ?>!</h5>
            <div id='vouched-element' style="height: 100%"></div>
            <button class="skipVerification btn btn-custom"><?= __('Skip verification') ?></button>
        </div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://static.vouched.id/widget/vouched-2.0.0.js"></script>
<?php endif; ?>

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
                        $('#signup-submit').html('Submit&nbsp;<i class="fa-solid fa-caret-right"></i>')
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