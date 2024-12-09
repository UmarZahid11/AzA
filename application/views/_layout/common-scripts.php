<script>
    function registerSocial() {

        var data = {
            'username': '<?= isset($this->user_data['signup_email']) ? (clean(str_replace('.', '_', filter_var(explode('@', $this->user_data['signup_email'])[0], FILTER_SANITIZE_STRING))) . rand(10, 100)) : '' ?>',
            'email': '<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : '' ?>',
            'password': '<?= isset($this->user_data['signup_password']) ? $this->user_data['signup_password'] : '' ?>',
            'confirm_password': '<?= isset($this->user_data['signup_password']) ? $this->user_data['signup_password'] : '' ?>',
        }

        // register
        var url = base_url_other + 'requests.php?f=register';

        new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: 'json',
                success: function (response) {
                    resolve(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
            });
        }).then(
            function(response) {
                if (response.status == 200) {
                    
                    var location = response.location
                    
                    // save user_id for future sync purpose
                    var data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'signup[signup_social_id]': response.user_id
                    }
                    var url = base_url + 'dashboard/profile/update'
                    
                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            async: true,
                            dataType: 'json',
                            success: function (response) {
                                resolve(response)
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                        });
                    }).then(
                        function(response) {
                            if (response.status) {
                                document.location.href = location;
                            } else {
                                document.location.href = location;
                            }
                        }
                    )
                } else {
                    window.location.href = window.location.href;
                }
            }
        )
    }
    
    function gotoSocial() {
        if (!$('#socialForum').data('disabled')) {
            var data = {
                'username': '<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : '' ?>',
                'password': '<?= isset($this->user_data) ? ((isset($this->user_data['signup_password_updated']) && $this->user_data['signup_password_updated']) ? $this->user_data['signup_previous_password'] : (isset($this->user_data['signup_password']) ? $this->user_data['signup_password'] : '')) : '' ?>',
            }
            // login
            var url = base_url_other + 'requests.php?f=login'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: 'json',
                    success: function (response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        registerSocial()
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                });
            }).then(
                function(response) {
                    if (response.status == 200) {
                        // window.location.href = response.location
                        // setTimeout(function(){document.location.href = response.location;},250);
                        location.assign(response.location);
                    } else {
                        registerSocial()
                    }
                }
            )
        }
    }
    
    function setTimezone() {
        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
        
        // Timezone difference in minutes such as 330 or -360 or 0
        var data = {timezone_offset_minutes : timezone_offset_minutes};
        var url = base_url + 'home/setTimezone'
        AjaxRequest.asyncRequest(url, data, false).then(
            function(response) {
                console.log(response)
            }
        )
    }
    
    setTimezone();
    
    $(document).ready(function() {
        
        // social redirect
        const queryString = window.location.search;
        const params = new URLSearchParams(queryString);
        const action = params.get('action');
        
        if(action == 'social') {
            setTimeout(function() {
                gotoSocial();
            }, 1000)
        }

        // social redirect
        $('#socialForum').on('click', function () {
            gotoSocial()
        })

        //
        $('body').on('click', '.resend_confirmation', function(e) {
            e.preventDefault()
            var data = {};
            var url = base_url + 'login/resend_confirmation';

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
                        $('.resend_confirmation').html('Sending confirmation email&nbsp;<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="15" />')
                    },
                    complete: function() {
                        $('.resend_confirmation').html($('.resend_confirmation').data('text'))
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        });
        
        $('body').on('click', '.delete_job', function() {
            
            var data = {
                id: $(this).data('id'),
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            var url = base_url + 'dashboard/job/delete'
            
            swal({
                title: "Are you sure?",
                text: "You are about to delete this job!",
                icon: "warning",
                buttons: ["Cancel", "Ok"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
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
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
            		}).then(
            		    function(response) {
                            if (response.status) {
                                swal("", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                            } else {
                                swal("", response.txt, "error");
                            }
                        }
                    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        });

        $('.privcestrp').on('click', function() {
            $('.privcestrp').removeClass('selected');
            $(this).addClass('selected')
        });
    });

</script>
