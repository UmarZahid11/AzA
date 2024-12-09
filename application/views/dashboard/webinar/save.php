<div class="dashboard-content">
    <i class="fa-solid fa-desktop"></i>
    <h4><?= ucfirst($type) . ' ' . __('Webinar') ?></h4>
    <hr />
    <div class="create-profile-form">
        <form id="webinarForm" method="POST" action="javascript:;" novalidate>
            <div class="row">
                <input type="hidden" name="identity_reverification" value="<?= $this->model_config->getConfigValueByVariable('identity_reverification') ?>" />
                <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                <?php if (isset($webinar['webinar_id'])) : ?>
                    <input type="hidden" class="form-control" name="webinar[webinar_id]" required value="<?= isset($webinar['webinar_id']) ? $webinar['webinar_id'] : '0' ?>" />
                <?php endif; ?>

                <input type="hidden" class="form-control" name="webinar[webinar_type]" required value="<?= isset($webinar['webinar_type']) ? $webinar['webinar_type'] : '5' ?>" />

                <div class="col-6">
                    <label><?= __('Topic') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter topic" name="webinar[webinar_topic]" required maxlength="100" value="<?= isset($webinar['webinar_topic']) ? $webinar['webinar_topic'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Duration') ?> <span class="text-danger">*</span> <span data-toggle="tooltip" data-bs-placement="top" title="Duration of the webinar (minutes)."><i class="fa fa-circle-question"></i></span></label>
                    <!--<input type="number" class="form-control" placeholder="Enter duration in minutes" name="webinar[webinar_duration]" required min="2" max="120" value="<?= isset($webinar['webinar_duration']) ? $webinar['webinar_duration'] : '' ?>" />-->
                    <select class="form-select" name="webinar[webinar_duration]">
                        <option value="30" <?= isset($webinar['webinar_duration']) && $webinar['webinar_duration'] == 30 ? 'selected' : '' ?> >30</option>
                        <option value="60" <?= isset($webinar['webinar_duration']) && $webinar['webinar_duration'] == 60 ? 'selected' : '' ?> >60</option>
                        <option value="90" <?= isset($webinar['webinar_duration']) && $webinar['webinar_duration'] == 90 ? 'selected' : '' ?> >90</option>
                        <option value="120" <?= isset($webinar['webinar_duration']) && $webinar['webinar_duration'] == 120 ? 'selected' : '' ?> >120</option>
                    </select>
                </div>

                <div class="col-12">
                    <label><?= __('Agenda') ?> <span class="text-danger">*</span></label>
                    <textarea type="text" class="form-control" placeholder="Enter webinar agenda" name="webinar[webinar_agenda]" required maxlength="2000"><?= isset($webinar['webinar_agenda']) ? $webinar['webinar_agenda'] : '' ?></textarea>
                </div>

                <div class="col-6 d-none">
                    <label><?= __('Approval type') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="webinar[webinar_approval_type]" required>
                        <option value="2" <?= isset($webinar['webinar_approval_type']) && $webinar['webinar_approval_type'] == '2' ? 'selected' : 'selected' ?>>No registration required.</option>
                        <option value="0" <?= isset($webinar['webinar_approval_type']) && $webinar['webinar_approval_type'] == '0' ? 'selected' : '' ?>>Automatically approve.</option>
                        <option value="1" <?= isset($webinar['webinar_approval_type']) && $webinar['webinar_approval_type'] == '1' ? 'selected' : '' ?>>Manually approve.</option>
                    </select>
                </div>

                <div class="col-6">
                    <label><?= __('Password') ?> </label>
                    <input type="password" class="form-control" placeholder="Enter password" name="webinar[webinar_password]" maxlength="10" value="<?= isset($webinar['webinar_password']) ? $webinar['webinar_password'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Contact email') ?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="Enter contact email" name="webinar[webinar_contact_email]" required value="<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : (isset($webinar['webinar_contact_email']) ? $webinar['webinar_contact_email'] : '') ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Contact name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter contact name" name="webinar[webinar_contact_name]" required maxlength="100" value="<?= isset($this->user_data) ? $this->model_signup->signupName($this->user_data, FALSE) : (isset($webinar['webinar_contact_name']) ? $webinar['webinar_contact_name'] : '') ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Auto recording') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="webinar[webinar_auto_recording]" required>
                        <option value="none" <?= isset($webinar['webinar_auto_recording']) && $webinar['webinar_auto_recording'] == 'none' ? 'selected' : '' ?>>None</option>
                        <option value="local" <?= isset($webinar['webinar_auto_recording']) && $webinar['webinar_auto_recording'] == 'local' ? 'selected' : '' ?>>Local</option>
                        <option value="cloud" disabled <?= isset($webinar['webinar_auto_recording']) && $webinar['webinar_auto_recording'] == 'cloud' ? 'selected' : '' ?>>Cloud</option>
                    </select>
                </div>

                <div class="col-6 d-none">
                    <label><?= __('Allow only authenticated users') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="webinar[webinar_meeting_authentication]" required>
                        <option value="1" <?= isset($webinar['webinar_meeting_authentication']) && $webinar['webinar_meeting_authentication'] == '1' ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($webinar['webinar_meeting_authentication']) && $webinar['webinar_meeting_authentication'] == '0' ? 'selected' : 'selected' ?>>No</option>
                    </select>
                </div>

                <input type="hidden" name="webinar[webinar_mute_upon_entry]" value="1" />

                <div class="col-6">
                    <label><?= __('Start time') ?> <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" id="startTime" placeholder="Enter start time" name="webinar[webinar_start_time]" required maxlength="100" value="<?= isset($webinar['webinar_start_time']) ? $webinar['webinar_start_time'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Timezone') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="webinar[webinar_timezone]" required>
                        <?php if ($timezones) : ?>
                            <?php foreach ($timezones as $key => $value) : ?>
                                <option value="<?= $value['timezones_name'] ?>" <?= isset($webinar['webinar_timezone']) && $webinar['webinar_timezone'] == $value['timezones_name'] ? 'selected' : '' ?>><?= $value['timezones_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12">
                    <label>Options</label> <br />
                    <label>
                        <input style="float: initial;" type="checkbox" name="webinar[webinar_panelist_authentication]" value="1" <?= isset($webinar['webinar_panelist_authentication']) && $webinar['webinar_panelist_authentication'] ? 'checked' : ''; ?> />&nbsp;Require panelists to authenticate to join <span data-toggle="tooltip" data-bs-placement="top" title="Panelists will need to sign into the Zoom account that was invited to the webinar."><i class="fa fa-circle-question"></i></span>
                    </label> <br />
                    <label>
                        <input style="float: initial;" type="checkbox" name="webinar[webinar_question_and_answer]" value="1" <?= isset($webinar['webinar_question_and_answer']) && $webinar['webinar_question_and_answer'] ? 'checked' : ''; ?> />&nbsp;Q&A
                    </label> <br />
                    <label>
                        <input style="float: initial;" type="checkbox" name="webinar[webinar_practice_session]" value="1" <?= isset($webinar['webinar_practice_session']) && $webinar['webinar_practice_session'] ? 'checked' : ''; ?> />&nbsp;Enable Practice Session
                    </label> <br />
                </div>

                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-custom previewBtn" id="webinarFormBtn">Save</button>
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

        function submitwebinarForm() {
            var webinarFormBtn = '#webinarFormBtn'
            var data = $("#webinarForm").serialize();
            var url = "<?php echo l('dashboard/webinar/saveData'); ?>";

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
                        $('#webinarFormBtn').removeClass('disabled')
                        $('#webinarFormBtn').html('Save')
                    },
                    beforeSend: function() {
                        $('#webinarFormBtn').addClass('disabled')
                        $('#webinarFormBtn').html('Saving ...')
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
                                    window.open('<?= l('dashboard/webinar/detail/') ?>' + response.webinar_id, '_blank');
                                }
                            }
                        });
                        // if (response.refresh) {
                        //     $('#webinarForm').each(function() {
                        //         this.reset();
                        //     });
                        // }
                    } else {
                        $.confirm({
                            title: '<?= __("Error!") ?>',
                            content: response.txt,
                            buttons: {
                                cancel: function() {
                                    if (response.refresh) {
                                        // location.reload();
                                    }
                                },
                            }
                        });
                    }
                }
            )
        }

        $("#webinarForm").on('submit', function() {
            //
            $('.previewBtn').attr('disabled', true)
            //
            if (!$('#webinarForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#webinarForm').addClass('was-validated');
                $('#webinarForm').find(":invalid").first().focus();
                //
                $('.previewBtn').attr('disabled', false)
                return false;
            } else {
                $('#webinarForm').removeClass('was-validated');
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
                            submitwebinarForm()

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
                submitwebinarForm()
            }
            $('.previewBtn').attr('disabled', false)
        })
    })
</script>