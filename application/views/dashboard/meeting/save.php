<div class="dashboard-content">
    <i class="fa-solid fa-desktop"></i>
    <h4><?= ucfirst($type) . ' ' . __('Meeting') ?></h4>
    <hr />
    <div class="create-profile-form">
        <form id="meetingForm" method="POST" action="javascript:;" novalidate>
            <div class="row">
                <input type="hidden" name="identity_reverification" value="<?= $this->model_config->getConfigValueByVariable('identity_reverification') ?>" />
                <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                <?php if (isset($meeting['meeting_id'])) : ?>
                    <input type="hidden" class="form-control" name="meeting[meeting_id]" required value="<?= isset($meeting['meeting_id']) ? $meeting['meeting_id'] : '0' ?>" />
                <?php endif; ?>

                <input type="hidden" class="form-control" name="meeting[meeting_type]" required value="<?= isset($meeting['meeting_type']) ? $meeting['meeting_type'] : '2' ?>" />
                <input type="hidden" class="form-control" name="meeting[meeting_reference_id]" required value="<?= isset($meeting['meeting_reference_id']) ? $meeting['meeting_reference_id'] : (isset($meeting_reference_id) ? $meeting_reference_id : 0) ?>" />
                <input type="hidden" class="form-control" name="meeting[meeting_reference_type]" required value="<?= isset($meeting['meeting_reference_type']) ? $meeting['meeting_reference_type'] : (isset($meeting_reference_type) ? $meeting_reference_type : MEETING_REFERENCE_APPLICATION) ?>" />

                <div class="col-6">
                    <label><?= __('Topic') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter topic" name="meeting[meeting_topic]" required maxlength="100" value="<?= isset($meeting['meeting_topic']) ? $meeting['meeting_topic'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Duration') ?> <span class="text-danger">*</span></label>
                    <!--<input type="number" class="form-control" placeholder="Enter duration in minutes" name="meeting[meeting_duration]" required min="2" max="120" value="<?= isset($meeting['meeting_duration']) ? $meeting['meeting_duration'] : '' ?>" />-->
                    <select class="form-select" name="meeting[meeting_duration]">
                        <option disabled>Select meeting duration</option>
                        <option value="10" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 10 ) ? 'checked' : '' ?>>10</option>
                        <option value="20" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 20 ) ? 'checked' : '' ?>>20</option>
                        <option value="30" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 30 ) ? 'checked' : '' ?>>30</option>
                        <option value="40" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 40 ) ? 'checked' : '' ?>>40</option>
                        <option value="50" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 50 ) ? 'checked' : '' ?>>50</option>
                        <option value="60" <?= isset($meeting['meeting_duration']) && ($meeting['meeting_duration'] == 60 ) ? 'checked' : '' ?>>60</option>
                    </select>
                </div>

                <div class="col-12">
                    <label><?= __('Agenda') ?> <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" placeholder="Enter meeting agenda" name="meeting[meeting_agenda]" required maxlength="2000"><?= isset($meeting['meeting_agenda']) ? $meeting['meeting_agenda'] : '' ?></textarea>
                </div>

                <div class="col-6">
                    <label><?= __('Meeting password') ?> <span class="text-danger">*</span></label>
            		<div class="search-hd-box">
                        <input type="password" class="form-control" placeholder="Enter password" name="meeting[meeting_password]" required maxlength="10" value="<?= isset($meeting['meeting_password']) ? $meeting['meeting_password'] : '' ?>" 
                        style="background-color: #fff;
                                font-size: 13px !important;
                                height: 40px;
                                border: 1px solid #ddd;" />
            			<a href="javascript:;" class="eye-patch" style="margin-top: 15px;">
            				<i class="fa fa-eye"></i>
            			</a>
            		</div>
                </div>

                <div class="col-6">
                    <label><?= __('Auto recording') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_auto_recording]" required>
                        <option value="none" <?= isset($meeting['meeting_auto_recording']) && $meeting['meeting_auto_recording'] == 'none' ? 'selected' : '' ?>>None</option>
                        <option value="local" <?= isset($meeting['meeting_auto_recording']) && $meeting['meeting_auto_recording'] == 'local' ? 'selected' : '' ?>>Local</option>
                        <option value="cloud" <?= isset($meeting['meeting_auto_recording']) && $meeting['meeting_auto_recording'] == 'cloud' ? 'selected' : '' ?>>Cloud</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Contact email') ?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="Enter contact email" name="meeting[meeting_contact_email]" required value="<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : (isset($meeting['meeting_contact_email']) ? $meeting['meeting_contact_email'] : '') ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Contact name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter contact name" name="meeting[meeting_contact_name]" required maxlength="100" value="<?= isset($this->user_data) ? $this->model_signup->signupName($this->user_data, FALSE) : (isset($meeting['meeting_contact_name']) ? $meeting['meeting_contact_name'] : '') ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Allow joining before host') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_join_before_host]" required>
                        <option value="1" <?= isset($meeting['meeting_join_before_host']) && $meeting['meeting_join_before_host'] == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($meeting['meeting_join_before_host']) && $meeting['meeting_join_before_host'] == '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Enter join before host time') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_jbh_time]" required>
                        <option value="0" <?= isset($meeting['meeting_jbh_time']) && $meeting['meeting_jbh_time'] == '0' ? 'selected' : '' ?>>Anytime</option>
                        <option value="5" <?= isset($meeting['meeting_jbh_time']) && $meeting['meeting_jbh_time'] == '5' ? 'selected' : '' ?>>5 minutes</option>
                        <option value="10" <?= isset($meeting['meeting_jbh_time']) && $meeting['meeting_jbh_time'] == '10' ? 'selected' : '' ?>>10 minutes</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Allow only authenticated users') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_meeting_authentication]" required>
                        <option value="1" <?= isset($meeting['meeting_meeting_authentication']) && $meeting['meeting_meeting_authentication'] == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($meeting['meeting_meeting_authentication']) && $meeting['meeting_meeting_authentication'] == '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Mute participants upon entry') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_mute_upon_entry]" required>
                        <option value="1" <?= isset($meeting['meeting_mute_upon_entry']) && $meeting['meeting_mute_upon_entry'] == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($meeting['meeting_mute_upon_entry']) && $meeting['meeting_mute_upon_entry'] == '0' ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Start time') ?> <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" id="startTime" placeholder="Enter start time" name="meeting[meeting_start_time]" required maxlength="100" value="<?= isset($meeting['meeting_start_time']) ? $meeting['meeting_start_time'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Timezone') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="meeting[meeting_timezone]" required>
                        <?php if ($timezones) : ?>
                            <?php foreach ($timezones as $key => $value) : ?>
                                <option value="<?= $value['timezones_name'] ?>" <?= isset($meeting['meeting_timezone']) && $meeting['meeting_timezone'] == $value['timezones_name'] ? 'selected' : '' ?>><?= $value['timezones_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-custom previewBtn" id="meetingFormBtn">Save</button>
                </div>

            </div>
        </form>
    </div>

</div>

<a class="vouchedModalBtn d-none" href="javascript:;" data-fancybox data-animation-duration="700" data-src="#vouchedModal"><?= __('Relaunch Vouched') ?>&nbsp;<span class="fa fa-question-circle" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Verify your identity with vouched.') ?>"></span></a>
<div class="grid">
    <div style="display: none; padding: 44px !important;width:100%;height:100%" id="vouchedModal" class="animated-modal">
        <h5><?= __('Reverify your identity') ?>!</h5>
        <div id='vouched-element' style="height: 100%"></div>
    </div>
</div>

<script src="https://static.vouched.id/widget/vouched-2.0.0.js"></script>

<script>
    $(document).ready(function() {

        let startTime = document.getElementById("startTime");
        startTime.min = new Date(Date.now() + (3600 * 1000 * 5)).toISOString().slice(0, new Date().toISOString().lastIndexOf(":"));

        function submitMeetingForm() {
            var meetingFormBtn = '#meetingFormBtn'
            var data = $("#meetingForm").serialize();
            var url = "<?php echo l('dashboard/meeting/saveData'); ?>";

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
                        $(meetingFormBtn).attr('disabled', false)
                        $(meetingFormBtn).html('Save')
                    },
                    beforeSend: function() {
                        $(meetingFormBtn).attr('disabled', true)
                        $(meetingFormBtn).html('Saving ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $.confirm({
                            title: '<?= __("Success") ?>',
                            content: response.txt,
                            buttons: {
                                close: function() {
                                    window.open('<?= l('dashboard/meeting/detail/') ?>' + response.meeting_id, '_blank');
                                }
                            }
                        });
                        if (response.refresh) {
                            $('#meetingForm').each(function() {
                                this.reset();
                            });
                        }
                    } else {
                        $.confirm({
                            title: '<?= __("Error!") ?>',
                            content: response.txt,
                            buttons: {
                                cancel: function() {
                                    if (response.refresh) {
                                        location.reload();
                                    }
                                },
                            }
                        });
                    }
                }
            )
        }

        //
        $("#meetingForm").on('submit', function() {

            if (!$('#meetingForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#meetingForm').addClass('was-validated');
                $('#meetingForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#meetingForm').removeClass('was-validated');
            }

            if ($('input[name=identity_reverification]').val() == '1') {

                // init_vouched()
                var vouched = Vouched({
                    // specify reverification job
                    type: 'reverify',
                    showProgressBar: true,
                    reverificationParameters: {
                        // reference the source job by its id
                        jobId: '<?= $this->user_data['signup_vouched_token'] ?>'
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
                        console.log('initialization');
                        console.log(token);
                        console.log(job);
                    },

                    // called when the reverification is completed.
                    onReverify: (job) => {
                        // token used to query jobs
                        console.log("Reverification complete", {
                            token: job.token
                        });

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
                            submitMeetingForm()

                        } else {
                            $('.fancybox-close-small').trigger('click');
                            swal("Error", "Identity verification failed!", "error");
                            vouched.unmount("#vouched-element");
                            // window.location.replace("https://localhost/aza-life/vouched/index");
                        }
                    },
                    // theme must be 'avant' for reverification
                    theme: {
                        name: 'avant',
                    },
                });
                vouched.mount("#vouched-element");
                $('.vouchedModalBtn').trigger('click')
            } else {
                submitMeetingForm()
            }

        })

        //
        if ($('select[name="meeting[meeting_join_before_host]"]').val() == 0) {
            $('select[name="meeting[meeting_jbh_time]"]').attr('disabled', true)
        } else {
            $('select[name="meeting[meeting_jbh_time]"]').attr('disabled', false)
        }

        $('select[name="meeting[meeting_join_before_host]"]').on('change', function() {
            if ($('select[name="meeting[meeting_join_before_host]"]').val() == 0) {
                $('select[name="meeting[meeting_jbh_time]"]').attr('disabled', true)
            } else {
                $('select[name="meeting[meeting_jbh_time]"]').attr('disabled', false)
            }
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
    })
</script>