<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">

<style>
    section.affilate-sec {
        padding: 70px 0;
    }

    section.affilate-sec h2 {
        font-size: 36px;
        font-weight: 500;
    }

    section.affilate-sec ul {
        list-style: disc;
        padding-left: 20px;
    }

    .foirl-wrp label {
        display: block;
        font-size: 16px;
    }

    .foirl-wrp input {
        width: 100%;
        height: 40px;
        margin: 0 0 20px;
        /*border: 0;*/
        padding-left: 15px;
        background: #f7f7f7;
    }

    section.affilate-sec button {
        /*padding: 8px 22px;*/
    }

    section.affilate-sec button:hover {
        /*padding: 8px 22px;*/
    }


    .foirl-wrp label span, .form-group label span {
        color: red;
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .iti {
        width: 100%;
    }
    .form-group input {
        width: 100%;
        height: 45px;
        background: transparent;
        color: #000;
        border: 1px solid #8204aa;
        border-radius: 6px;
        padding: 15px 15px;
        margin-bottom: 15px;
        font-family: 'Montserrat';
        font-weight: 500;
    }
</style>

<section class="affilate-sec">
    <div class="container">
        <h2>Become a New Affiliate Today - Complete the Form Below</h2>
        <ul>
            <!--<li>Earn $100.00 for each customer you activate</li>-->
            <!--<li>Win Great Weekly/Monthly Prizes</li>-->
            <!--<li>FREE Tickets to our Semi-Annual Conference &amp; Party</li>-->
            <!--<li>Get paid Every 2 Weeks</li>-->
            <li>No Experience Needed/All Training Provided.</li>
            <li>Enroll Today for FREE &amp; Start Earning Tomorrow.</li>
        </ul>
        <p>Fields marked with an <span class="text-danger">*</span> are required</p>
        
        <form method="POST" id="affiliateForm" action="javascript:;" novalidate>
            <input type="hidden" name="_token" />
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Name <span>*</span></label>
                        <input type="text" name="signup[signup_firstname]" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name <span>*</span></label>
                        <input type="text" name="signup[signup_lastname]" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Email <span>*</span></label>
                        <input type="email" name="signup[signup_email]" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Password <span>*</span></label>
                        <input type="password" name="signup[signup_password]" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Confirm Password <span>*</span></label>
                        <input type="password" name="cpassword" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Address <span class=>*</span></label>
                        <input type="text" name="signup_address" class="form-control" autocomplete="street-address" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>City <span>*</span>
                        </label>
                        <input type="text" name="signup[signup_city]" class="form-control" maxlength="255" autocomplete="address-level2" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>US State <span>*</span>
                        </label>
                        <input type="text" name="signup[signup_state]" class="form-control" maxlength="255" autocomplete="address-level1" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Zip <span>*</span></label>
                        <input type="number" name="signup[signup_zip]" class="form-control" maxlength="255" autocomplete="postal-code" required />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cell Phone <span>*</span></label>
                        <input type="tel" name="signup[signup_phone]" id="signup_phone" class="form-control" maxlength="255" required />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Website</label>
                        <input type="url" name="signup[signup_website]" class="form-control" maxlength="255" />
                    </div>
                </div>
                <div class="col-md-6 offset-3">
                    <button type="submit" class="btn btn-primary loginBtn w-100" id="affiliateFormBtn">Submit</button>
                </div>
            </div>
        </form>
    </div>
</section>

<script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.21/web.js"></script>
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

<script>
    
    // intlTelInput
    const input = document.querySelector("#signup_phone");
    const telInput = intlTelInput(input, {
        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
        initialCountry: 'us',
        separateDialCode: false,
        nationalMode: false,
        autoHideDialCode: true,
    });
    // intlTelInput
    
    const script = document.getElementById('search-js');
    script.onload = () => {
        const collection = mapboxsearch.autofill({
            accessToken: 'pk.eyJ1IjoibGl2ZWVsZWN0cmljYWx1ayIsImEiOiJjbHdhcW43anAwZzhmMmxzMjlpemFjMmJ3In0.ngAznUmi_oaNoNjrtCHHNA',
            name: 'signup_address'
        });
    };

    async function validateSignupServer() {

        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))

        var data = new FormData(document.getElementById("affiliateForm"));
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
                    $('#affiliateFormBtn').attr('disabled', true)
                    $('#affiliateFormBtn').html('Processing ...')
                },
                complete: function() {
                    $('#affiliateFormBtn').attr('disabled', false)
                    $('#affiliateFormBtn').html('Submit')
                }
            });
        });
    }
    
    /**
     * Method signupFormSubmit
     *
     */
    function signupFormSubmit() {

        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById("affiliateForm"));
        var url = base_url + 'affiliate/save';

        new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: 'json',
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
                	$('#affiliateForm').each(function(){
                		this.reset();
                	});	
                }
            }
        )
    }

    $(document).ready(function() {
      
        // affiliateForm
        // affiliateFormBtn
        $('#affiliateForm').on('submit', function() {
            if (!$('#affiliateForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#affiliateForm').addClass('was-validated');
                $('#affiliateForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#affiliateForm').removeClass('was-validated');
            }
    
            if ($('#signup_phone').val() == "" || !($.trim($('#signup_phone').val())) || !telInput.isValidNumber()) {
                $('#signup_phone').addClass('force-invalid');
                toastr.error('The entered phone number is invalid!');
                return false;
            } else {
                $('#signup_phone').removeClass('force-invalid');
            }

            if ($('.cpassword').val() != $('.password').val()) {
                AdminToastr.error("The password and confirm password do not match!");
                return false;
            }
            
            validateSignupServer().then(
                function(response) {
                    if(!response.status) {
                        toastr.error(response.txt)
                    } else {
                        signupFormSubmit()
                    }
                }
            )
        })
      
    })

</script>