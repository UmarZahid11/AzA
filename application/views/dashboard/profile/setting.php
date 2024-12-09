<div class="dashboard-content">
    <i class="fa-regular fa-cog"></i>
    <h4><?= __('Settings') ?></h4>
    <hr />
    <div class="container banner-frm">
        <h4><?= __('Account Action') ?></h4>
        
        <?php $this->load->view('widgets/verification/form.php'); ?>

        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
            <form action="javascript:;" class="identityVerificationForm" method="POST">
                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                <input type="hidden" name="signup[signup_vouched_token]" />
                <input type="hidden" name="signup[signup_vouched_response]" />
                <input type="hidden" name="signup[signup_is_verified]" />
                <div class="row mb-4 vouched-content">
                    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                        <label><?= __('Identity verification') ?></label>
                    </div>
                    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                        <?php if ($this->user_data['signup_is_verified'] && $this->user_data['signup_vouched_token']) : ?>
                            <?= __('Verified') ?>&nbsp;<span class="text-custom" data-toggle="tooltip" data-bs-placement="top" title="Verified"><i class="fa fa-circle-check"></i></span>
                        <?php else : ?>
                            <a class="vouchedModalBtn d-none" href="javascript:;" data-fancybox data-animation-duration="700" data-src="#vouchedModal"><?= __('Relaunch Vouched') ?>&nbsp;<span class="fa fa-question-circle" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Verify your identity with vouched.') ?>"></span></a>
                            <button class="btn btn-custom vouchedBtn" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_VERIFICATION) ? __('You have already been verified by the admin') : __('Identity verification required')) ?>.">
                                <?php echo __('Verify my identity') . ' ' . ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_VERIFICATION) ? '(optional)' : ''); ?>
                            </button>
                            <div class="grid">
                                <div style="display: none; padding: 44px !important;width:100%;height:100%" id="vouchedModal" class="animated-modal">
                                    <h5><?= __('Verify your identity') ?>!</h5>
                                    <div id='vouched-element' style="height: 100%"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        <?php endif; ?>

        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
            <div class="row mt-5 rateDiv">
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                    <label><?= __('Set hourly rates ($)') ?></label>
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <button class="btn btn-custom rateModalBtn" data-fancybox data-animation-duration="700" data-src="#rateModal" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Update per hour rates for your work.') ?>"><?= __('Update my rates') ?></button>
                </div>
            </div>

            <div class="row mt-5 rateDiv">
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                    <label><?= __('Change your availability hours') ?></label>
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <button class="btn btn-custom" data-fancybox data-animation-duration="700" data-src="#availabilityModal" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Update your availability time.') ?>">
                        <?= __('Update my availability') ?>
                    </button>
                </div>
            </div>
            <!--<div class="row mt-5">-->
            <!--    <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">-->
            <!--        <label><?//= __('Specify weeks, days and date available for work') ?></label>-->
            <!--    </div>-->
            <!--    <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">-->
            <!--        <div id="calendar"></div>-->
            <!--    </div>-->
            <!--</div>-->
        <?php endif; ?>

        <hr />
        <h4><?= __('Danger zone') ?></h4>
        <form action="javascript:;" class="deleteAccountForm" method="POST" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row mt-2">
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3 col-xxl-3">
                    <label><?= __('Delete account') ?></label>
                </div>
                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9 col-xxl-9">
                    <button class="btn btn-danger delete_account" type="button" data-id="<?= isset($this->userid) ? $this->userid : 0 ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Delete your account permanently. This action is irreversible!') ?>">
                        <?= __('Delete account permanently') ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <!-- container -->
</div>

<!-- MODALS START -->

<?php if ($this->model_signup->hasPremiumPermission()) : ?>
    <div class="grid">

        <div style="display: none;" id="rateModal" class="animated-modal">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 mt-5 bgWhite">
                        <h4><?= __('Change rate') ?></h4>
                        <span><?= __('You can change rate by changing the amount below') ?></span>
                        <br />
                        <form action="javascript:;" class="mt-3 rateForm text-center" novalidate>
                            <input type="hidden" name="_token" />
                            <div class="form-group has-search input-group">
                                <span class="fa fa-dollar form-control-feedback"></span>
                                <input type="number" step="0.01" class="form-control" name="signup_info[signup_info_hourly_rate]" min="0" placeholder="Enter work rate per hour" value="<?= isset($this->signup_info['signup_info_hourly_rate']) ? $this->signup_info['signup_info_hourly_rate'] : 0 ?>" />
                                <div class="input-group-append">
                                    <p class="perhour"> <?= __('/ hr') ?></p>
                                </div>
                            </div>
                            <hr class="mt-4">
                            <button type="submit" class="btn btn-custom w-100" id="rateFormBtn"><?= __('Save') ?></button>
                            <!-- customBtn -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="grid">

        <div style="display: none; width:1100px" id="availabilityModal" class="animated-modal">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 mt-5 bgWhite">
                        <h4><?= __('Change availability') ?></h4>
                        <span><?= __('Are you available to take on new work?') ?></span>
                    </div>
                    <div class="col-sm-6 mt-5 bgWhite2">
                        <form action="javascript:;" class="availabilityForm" novalidate>
                            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                            <h4><?= __('I am currently') ?></h4>
                            <input type="hidden" name="signup_info[signup_info_availablity_status]" value="<?= (isset($this->signup_info['signup_info_availablity_status']) && $this->signup_info['signup_info_availablity_status']) ? 1 : 0 ?>" />
                            <input class="availabilityToggle" name="signup_info_availablity_status" type="checkbox" <?= isset($this->signup_info['signup_info_availablity_status']) ? ($this->signup_info['signup_info_availablity_status'] ? 'checked' : '') : '' ?> data-toggle="toggle" data-on="Available" data-off="Not Available" data-width="200" data-height="40" />
                            <hr />
                            <ul class="text-left">
                                <li>
                                    <label>
                                        <input type="radio" name="signup_info[signup_info_work_availability]" class="form-check-input" value="More than 30 hrs/week" <?= isset($this->signup_info['signup_info_work_availability']) && $this->signup_info['signup_info_work_availability'] == 'More than 30 hrs/week' ? 'checked' : '' ?> required />
                                        <?= __('More than 30 hrs/week') ?>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="signup_info[signup_info_work_availability]" class="form-check-input" value="Less than 30 hrs/week" <?= isset($this->signup_info['signup_info_work_availability']) && $this->signup_info['signup_info_work_availability'] == 'Less than 30 hrs/week' ? 'checked' : '' ?> required />
                                        <?= __('Less than 30 hrs/week') ?>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="signup_info[signup_info_work_availability]" class="form-check-input" value="As needed - open to offers" <?= isset($this->signup_info['signup_info_work_availability']) && $this->signup_info['signup_info_work_availability'] == 'As needed - open to offers' ? 'checked' : '' ?> required />
                                        <?= __('As needed - open to offers') ?>
                                    </label>
                                </li>
                            </ul>
                            <hr class="mt-4">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-custom w-100 cancelBtn"><?= __('Cancel') ?></button>
                                    <!-- customBtn -->
                                </div>
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-custom w-100" id="availabilityFormBtn"><?= __('Save') ?></button>
                                    <!-- customBtn -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php endif; ?>

<!-- MODALS END -->

<script src="https://static.vouched.id/widget/vouched-2.0.0.js"></script>

<script>

    $(document).ready(function() {

        if (<?= (isset($_GET['verify']) && $_GET['verify'] == 'false') ? 1 : 0 ?>) {
            if ($('.vouchedBtn').length > 0) {
                $('.vouchedBtn').tooltip('show')
            }
            swal("Verification error", '<?= ERROR_MESSAGE_VERIFICATION ?>', "error");
        }

        if (window.location.href.indexOf("#rateModal") != -1) {
            $('.rateModalBtn').click();
        }

        // availability toggle
        if ($('.availabilityToggle').prop('checked')) {
            $('input[name="signup_info[signup_info_availablity_status]"]').val(<?= STATUS_ACTIVE ?>)
            $('input[name="signup_info[signup_info_work_availability]"]').attr('disabled', false)
        } else {
            $('input[name="signup_info[signup_info_availablity_status]"]').val(<?= STATUS_INACTIVE ?>)
            $('input[name="signup_info[signup_info_work_availability]"]').attr('disabled', true)
        }
        $('.availabilityToggle').change(function() {
            if ($(this).prop('checked')) {
                $('input[name="signup_info[signup_info_availablity_status]"]').val(<?= STATUS_ACTIVE ?>)
                $('input[name="signup_info[signup_info_work_availability]"]').attr('disabled', false)
            } else {
                $('input[name="signup_info[signup_info_availablity_status]"]').val(<?= STATUS_INACTIVE ?>)
                $('input[name="signup_info[signup_info_work_availability]"]').attr('disabled', true)
            }
        })

        //
        $('body').on('click', '.vouchedBtn', function() {
            // init_vouched()
            var vouched = Vouched({
                showProgressBar: true,
                // Optional verification properties.
                verification: {
                    // verify the user's information
                    firstName: '<?= $this->user_data['signup_firstname'] ?>',
                    lastName: '<?= $this->user_data['signup_lastname'] ?>',
                    // used for the crosscheck feature
                    email: '<?= $this->user_data['signup_email'] ?>',
                    phone: '<?= $this->user_data['signup_phone'] ?>'
                },
                liveness: 'straight',
                // sandbox: '<?//= VOUCHED_SANDBOX_ENV ?>',

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
                    // token used to query jobs
                    // console.log("Scanning complete", {
                    //     token: job.token
                    // });

                    // An alternative way to update your system based on the
                    // results of the job. Your backend could perform the following:
                    // 1. query jobs with the token
                    // 2. store relevant job information such as the id and
                    //    success property into the user's profile
                    // fetch(`/yourapi/idv?job_token=${job.token}`);

                    // Redirect to the next page based on the job success
                    if (job.result.success) {

                        $('.fancybox-close-small').trigger('click');
                        $('input[name="signup[signup_vouched_token]"]').val(job.token)
                        $('input[name="signup[signup_vouched_response]"]').val(JSON.stringify(job))
                        $('input[name="signup[signup_is_verified]"]').val(1)

                        var data = $('.identityVerificationForm').serialize();
                        var url = base_url + 'dashboard/profile/update'

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
                            })
            			}).then(
            			    function(response) {
                                if (response.status) {
                                    AdminToastr.success('Verification successful.')
                                    $(".vouched-content").load(location.href + " .vouched-content>*", "");
                                } else {
                                    swal("Error", response.txt, "error");
                                }
                            }
                        )
                    } else {
                        $('.fancybox-close-small').trigger('click');
                        swal("Error", "Identity verification failed!", "error");
                        vouched.unmount("#vouched-element");
                    }
                },

                // theme
                theme: {
                    name: 'avant',
                },
            });
            vouched.mount("#vouched-element");
            $('.vouchedModalBtn').trigger('click')
        })

        $('body').on('submit', '.rateForm', function() {
        
            var rateFormBtn = '#rateFormBtn'
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = $(this).serialize();
            var url = base_url + 'dashboard/profile/update_info';
    
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
                        $(rateFormBtn).attr('disabled', false)
                        $(rateFormBtn).html('Save')
                    },
                    beforeSend: function() {
                        $(rateFormBtn).attr('disabled', true)
                        $(rateFormBtn).html('Saving ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                        $('.fancybox-close-small').click()
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        })
    
        $('body').on('submit', '.availabilityForm', function() {
            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }
    
            var availabilityFormBtn = '#availabilityFormBtn'
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = $(this).serialize();
            var url = base_url + 'dashboard/profile/update_info';
    
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
                        $(availabilityFormBtn).attr('disabled', false)
                        $(availabilityFormBtn).html('Save')
                    },
                    beforeSend: function() {
                        $(availabilityFormBtn).attr('disabled', true)
                        $(availabilityFormBtn).html('Saving ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                        $('.fancybox-close-small').click()
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        })
    
        $('.cancelBtn').click(function() {
            $('.fancybox-close-small').click()
        })
        
        //
        $('body').on('click', '.delete_account', function() {
            
            var deleteAccount = '.delete_account'
            var data = {
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/profile/delete'

            swal({
                title: "<?= __('Are you sure?') ?>",
                text: "<?= __('You are about to delete your account. This action is irreversible and all your data on Azaverze will be lost!') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Ok') ?>"],
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
                                $(deleteAccount).attr('disabled', false)
                                $(deleteAccount).html('Delete account permanently')
                            },
                            beforeSend: function() {
                                $(deleteAccount).attr('disabled', true)
                                $(deleteAccount).html('Deleting ...')
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
                                    location.href = base_url;
                                })
        
                            } else {
                                swal("", response.txt, "error");
                            }
                        }
                    )
                } else {
                    swal("<?= __('Cancelled') ?>", "<?= __('Action aborted') ?>", "error");
                }
            })
        })
    })
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        if (calendarEl != undefined) {
            var calendar = new FullCalendar.Calendar(calendarEl, {
                selectable: true,
                eventColor: '#014e96',
                initialView: 'timeGridDay',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                selectMirror: true,
                unselectAuto: true,
                dateClick: function(info) {},
                select: function(info) {
                    if ((info.startStr > new Date().toISOString())) {
                        if (info.view.type != 'dayGridMonth') {
                            if (moment(info.endStr).format('MMMM Do YYYY, h:mm:ss a') <= moment(info.startStr).add(1, 'hour').format('MMMM Do YYYY, h:mm:ss a')) {
                                // ajax save event
                                $('.dynamoModal-dialog').show()
                                $("input[name=start_time]").val(info.startStr)
                                $("input[name=end_time]").val(info.endStr)
                                $('.fromtimeSlot').html('From: ' + moment(info.startStr).format('MMMM D YYYY, h:mm a'))
                                $('.totimeSlot').html('To: ' + moment(info.endStr).format('MMMM D YYYY, h:mm a'))
                                $('.dynamoModalBtn').trigger('click')
                            } else {
                                $.dialog({
                                    backgroundDismiss: true,
                                    title: 'Error',
                                    content: 'Maximum event duration is 1 hour.'
                                });
                            }
                        } else {
                            $.dialog({
                                backgroundDismiss: true,
                                title: 'Error',
                                content: 'Select week or day grid to mark your availability.'
                            });
                        }
                    } else {
                        $.dialog({
                            backgroundDismiss: true,
                            title: 'Error',
                            content: 'Select future time slot to mark your availability.'
                        });
                    }
                },
                eventClick: function(info) {
                    var eventObj = info.event;
                    swal({
                        title: "<?= __('Delete this slot?') ?>",
                        text: 'Remove your availability? \n' +
                            'From: ' + eventObj.startStr + '\n' +
                            'To: ' + eventObj.endStr,
                        icon: "warning",
                        className: "text-center",
                        buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
                    }).
                    then((isConfirm) => {
                        if (isConfirm) {
                            var data = {
                                'id': eventObj.id,
                            }
                            var url = base_url + 'dashboard/custom/delete_availability_slot'

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
                                        swal({
                                            title: "Success",
                                            text: response.txt,
                                            icon: "success",
                                        }).then(() => {
                                            calendar.getEventById(eventObj.id).remove()
                                        })
                                    } else {
                                        swal("Error", response.txt, "error");
                                    }
                    		    }
                		    )
                        } else {
                            swal("Cancelled", "Action aborted", "error");
                        }
                    })
                },
                unselect: function(event, view) {
                    return false;
                },
                unselectAuto: true,
                selectOverlap: function(event) {
                    return !event.block;
                },
                editable: true,
                events: <?= (isset($availability_slots) && count($availability_slots) > 0) ? json_encode($availability_slots) : "[]" ?>,
            });

            calendar.render();
        }
    });
</script>