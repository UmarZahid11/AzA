<script src="https://static.vouched.id/widget/vouched-2.0.0.js"></script>
<script>

    var state = false;
    function toggleEye() {
    	if(state) {
     		document.getElementById("password").setAttribute("type","password");
    		document.getElementById("open").style.display= 'none';
     		document.getElementById("close").style.display= 'block';
    		state = false;
    	} else {
    		document.getElementById("password").setAttribute("type","text");
     		document.getElementById("open").style.display= 'block';
     		document.getElementById("close").style.display= 'none';
    		state = true;
    	}
    }

    /**
     * Method submitSignupFormAfterVerification
     *
     * @return void
     */
    async function submitSignupFormAfterVerification(event) {

        if (!$('.login-form')[0].checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            $('.login-form').addClass('was-validated');
            $('.login-form').find(":invalid").first().focus();
            return response;
        } else {
            $('.login-form').removeClass('was-validated');
        }

        var data = $('.login-form').serialize();
        var url = base_url + 'login/do_login';

        return new Promise((resolve, reject) => {
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
                    $('#login-submit').attr('disabled', true)
                    $('#login-submit').html('<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
                },
                complete: function() {
                    $('#login-submit').attr('disabled', false)
                    $('#login-submit').html('Login')
                }
            })
		})
    }
    
    function vouchedReverify(response) {
        // init_vouched()
        var vouched = Vouched({
            // specify reverification job
            type: 'reverify',
            showProgressBar: true,
            reverificationParameters: {
                // reference the source job by its id
                jobId: response.jobId
            },
            // sandbox: '<?//= VOUCHED_SANDBOX_ENV ?>',

            appId: '<?= VOUCHED_PUBLIC_KEY ?>',
            // your webhook for POST verification processing
            // callbackURL: 'https://website.com/webhook',

            // mobile handoff
            // crossDevice: true,
            // crossDeviceQRCode: true,
            // crossDeviceSMS: true,
            onInit: ({
                token,
                job
            }) => {
                // console.log('initialization');
                // console.log(token);
                // console.log(job);
            },

            // called when the reverification is completed.
            onReverify: (job) => {
                // token used to query jobs
                // console.log("Reverification complete", {
                //     token: job.token
                // });

                // job.token
                // An alternative way to update your system based on the
                // results of the job. Your backend could perform the following:
                // 1. query jobs with the token
                // 2. store relevant job information such as the id and
                //    success property into the user's profile
                // fetch(`/yourapi/idv?job_token=${job.token}`);

                // Redirect to the next page based on the job success
                if (job.result.success) {
                    $('.fancybox-close-small').trigger('click');

                    // set reverified flag to 1 on success ..
                    $('input[name=signup_reverified]').val(1);

                    // submit login form again this time without the need to reverify ..
                    var response = submitSignupFormAfterVerification(event)
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        if (response.redirect_url) {
                            location.assign(response.redirect_url);
                        }
                    } else {
                        AdminToastr.error(response.txt);
                    }
                } else {
                    $('.fancybox-close-small').trigger('click');
                    swal("Error", "Identity verification failed!", "error");
                    vouched.unmount("#vouched-element");
                }
            },
            // theme must be 'avant' for reverification
            theme: {
                name: 'avant',
            },
        });
        vouched.mount("#vouched-element");
        $('.vouchedModalBtn').trigger('click')
        $('.vouchedModalBtn').removeClass('d-none')
    }
    
    $(document).ready(function() {

        $('.login-form').on('submit', function(event) {
            event.preventDefault();
            submitSignupFormAfterVerification(event).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        if (response.reverify) {
                            vouchedReverify(response)
                        } else if (response.redirect_url) {
                            location.assign(response.redirect_url);
                        } else {
                            location.reload()
                        }
                    } else {
                        AdminToastr.error(response.txt ?? '<?= ERROR_MESSAGE ?>');
                    }                
                }
            );
        })
    })

    $(document).ready(function() {

        if (localStorage.email != null) {
            $('.rememberMe').prop('checked', true);
        }

        const rmCheck = document.getElementsByClassName("rememberMe"),
            emailInput = document.getElementById("email");

        if (localStorage.checkbox && localStorage.checkbox !== "" && localStorage.email !== undefined) {
            // rmCheck.setAttribute("checked", "checked");
            $('.rememberMe').prop('checked', true);
            emailInput.value = localStorage.email;
        } else {
            // rmCheck.removeAttribute("checked");
            $('.rememberMe').prop('checked', false);
            emailInput.value = "";
        }

        $('.rememberMe').on('click', function() {
            lsRememberMe();
        })

        function lsRememberMe() {
            if ($('.rememberMe').is(':checked') && emailInput.value !== "" && emailInput.value !== undefined) {
                localStorage.email = emailInput.value;
                localStorage.checkbox = $('.rememberMe').is(':checked');
            } else {
                localStorage.email = "";
                localStorage.checkbox = "";
            }
        }
    })

    $(document).ready(function() {
        $('.forget_form').submit(function() {
            if (!$('.forget_form')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.forget_form').addClass('was-validated');
                return false;
            } else {
                $('.forget_form').removeClass('was-validated');
            }

            var forgetFormBtn = '#forget_form_btn'
            var data = $('.forget_form').serialize();
            var url = base_url + 'login/forgot';

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
                        $(forgetFormBtn).attr('disabled', true)
                        $(forgetFormBtn).html('Submitting ...')
                    },
                    complete: function() {
                        $(forgetFormBtn).attr('disabled', false)
                        $(forgetFormBtn).html('Submit')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $('.fancybox-close-small').click();
                    	$('.forget_form').each(function(){
                    		this.reset();
                    	});	
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })
    })
</script>