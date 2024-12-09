<div class="dashboard-content posted-theme">
    <i class="fa fa-address-card-o"></i>
    <h4><?= __('Apply for job') ?></h4>
    <hr />

    <div>
        <label><?= __('Job details') ?>:</label>
        <ul>
            <li>
                <h5><?= isset($job['job_title']) ? $job['job_title'] : '' ?></h5>
            </li>
            <li>
                <div class="d-flex">
                    <div>
                        <?= __('Category') ?>:
                        <?php if (isset($job['job_category']) && $job['job_category'] != NULL && @unserialize($job['job_category']) !== FALSE) : ?>
                            <?php foreach (unserialize($job['job_category']) as $ke => $val) : ?>
                                <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            ...
                        <?php endif; ?>
                    </div>
                </div>
            </li>
            <li>
                <small><?= __('Posted') ?>: <?= date('M d, Y', strtotime($job['job_createdon'])) ?></small>
            </li>
            <!-- <li>
                <p class="m-0">
                    <?php //if ($job['job_submission_deadline']) : ?>
                        <?//= __('Submission deadline') ?>: <span class="text-danger"><?//= date('M d, Y', strtotime($job['job_submission_deadline'])) ?></span>&nbsp;<small class="text-danger"><?php //echo ((strtotime(date('Y-m-d H:i:s')) > strtotime($job['job_submission_deadline'])) ? '(Submission deadline has passed).' : '') ?></small>
                    <?php //endif; ?>
                </p>

            </li> -->
            <li>
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <?php if (isset($job['job_detail']) && $job['job_detail'] != NULL) : ?>
                        <?php echo html_entity_decode($job['job_detail']); ?>
                    <?php else : ?>
                        <small><?= __("Job description is not available.") ?></small>
                    <?php endif; ?>
                <?php endif; ?>
            </li>
        </ul>
        <small><?= __('View complete job details') ?> <a href="<?= l('dashboard/job/detail/' . $job['job_slug']) ?>" target="_blank"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a></a>.</small>
    </div>
    <hr />

    <div>
        <label><?= __('Terms') ?>:</label>
        <?php if ($this->signup_info['signup_info_hourly_rate']) : ?>
            <div class="d-flex space-between">
                <div>
                    <small><?php echo 'My asking rate: ' . price($this->signup_info['signup_info_hourly_rate']) . '/hr' ?></small>
                </div>
                <div>
                    <small><?= __('Organization budget') ?>: <?= (isset($job['job_salary_lower']) && $job['job_salary_lower'] ? price($job['job_salary_lower']) : 'Not Set') . (isset($job['job_salary_upper']) ? ' - ' . price($job['job_salary_upper']) : '') ?></small>
                </div>
            </div>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>
                            <small><?= __('Hourly rate') ?>: </small>
                        </td>
                        <td>
                            <small><?= isset($this->signup_info['signup_info_hourly_rate']) ? price($this->signup_info['signup_info_hourly_rate']) . '/hr' : 'Not Set'; ?></small>
                        </td>
                    </tr>
                    <?php if (g('db.admin.service_fee')) : ?>
                        <?php $organization_share = (percent_amount($this->signup_info['signup_info_hourly_rate'], g('db.admin.service_fee'))); ?>
                        <tr>
                            <td>
                                <small><?= (g('db.admin.service_fee') ? g('db.admin.service_fee') : '0') . '% ' . $title . ' service fee:' ?></small>
                            </td>
                            <td>
                                <small><?= price($organization_share) . '/hr'  ?></small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <small><?= __('Your share') ?>: </small>
                            </td>
                            <td><small><?= price($this->signup_info['signup_info_hourly_rate'] - $organization_share) . '/hr' ?></small></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php else : ?>
            <small><?= __('You haven\'t set your profile rates yet!'); ?></small>
        <?php endif; ?>
        <small><?= __('Update your asking rates') ?> <a href="<?= l('dashboard/profile/setting#rateModal') ?>" target="_blank"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a>.</small>
    </div>
    <hr />

    <form id="job_application_proposal_form" action="javascript:;" method="POST" enctype="multipart/form-data">
        <div>
            <label><?= __('Job Questions') ?></label>
            <?php if (isset($job_question) && is_array($job_question) && count($job_question) > 0) : ?>
                &nbsp;(<small><?= __(GENERAL_ATTACHMENT_SIZE_DESCIPTION) ?>):</small>
                <ul class="list-group-numbered">
                    <?php foreach ($job_question as $key => $value) : ?>
                        <li><?= $value['job_question_title'] ?></li>
                        <input type="hidden" class="form-control" name="job_question_answer[job_question_answer_question_id][]" value="<?= $value['job_question_id'] ?>" />
                        <textarea class="form-control font-12" name="job_question_answer[job_question_answer_desc][]" placeholder="Enter your answer"></textarea>
                        <label>Add attachment to your answer</label>
                        <input type="file" class="form-control upload-answer font-12" name="job_question_answer_attachment[]" accept="video/*" />
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                :&nbsp;<small>Job questions are unavailable.</small>
            <?php endif; ?>
        </div>
        <hr />

        <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
        <input type="hidden" name="job_id" value="<?= isset($job['job_id']) ? $job['job_id'] : '' ?>" />

        <div class="form-group my-4">
            <label><?= __('Video attachment') ?>&nbsp;(<small><?= __('The size limit for video is 50 MB') ?>):</small> <span data-toggle="tooltip" data-bs-placement="top" title="A detailed video describing your eligibility for the job, ideally less than 3 minutes."><i class="fa fa-circle-question"></i></span></label>
            <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop Video') ?>
                <input type="file" name="job_application_attachment" class="form__file" id="upload-video" accept="video/*" />
            </label>
            <p id="files-area">
                <span id="videoList">
                    <span id="video-names"></span>
                </span>
            </p>
        </div>

        <div class="form-group my-4">
            <label>
                <input type="checkbox" name="isFile" class="form-check-input" />
                <span id="coverLetterTextCheck">Check to upload the cover letter instead</span>
            </label><br/>
            <label><?= __('Cover Letter') ?></label>
            <span id="coverLetterTextArea">
                <textarea name="job_application_cover_letter" class="form-control" maxlength="5000"></textarea>
            </span>
            <span id="coverLetterFileArea" class="d-none">
                <input type="file" name="job_application_cover_letter" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx" />
            </span>
        </div>

        <div class="form-group my-4">
            <label><?= __('Resume') ?></label>
            <input type="file" name="job_application_resume" class="form-control"  />
        </div>

        <div class="form-group my-4">
            <label><?= __('Attachments') ?>&nbsp;(<small><?= __('The size limit for each file is 10 MB') ?>):</small></label>
            <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop Files') ?>
                <input type="file" name="job_application_attachments[]" class="form__file" id="upload-files" accept=".pdf,.doc,.docx,.ppt,.pptx" multiple="multiple" />
            </label>
            <p id="files-area">
                <span id="filesList">
                    <span id="files-names"></span>
                </span>
            </p>
        </div>

        <?php $get_request = $this->model_job_testimonial_request->getRequest($this->userid); ?>
        <!-- user has the required number of testimonial or user has the approval from admin or the user has special bypass privilege from admin -->
        <?php if (($this->model_signup_testimonial->getSignupTestimonial($this->userid, TRUE) >= MINIMUM_SIGNUP_TESTIMONIAL) || ($this->model_job_testimonial_request->getUseRequestrApprovalById($this->userid)) || ($this->model_signup_bypass_privilege->get($this->userid, PRIVILEGE_TYPE_TESTIMONIAL, TRUE))) : ?>
            <button type="submit" class="btn btn-custom" id="job_application_proposal_form_btn"><?= __('Apply now') ?></button>
        <?php elseif (empty($get_request) || ($get_request && validateDate($get_request['job_testimonial_request_extention'], 'Y-m-d H:i:s') && (strtotime($get_request['job_testimonial_request_extention']) < strtotime(date('Y-m-d H:i:s'))))) : ?>
            <button data-fancybox data-animation-duration="700" data-src="#testimonialRequestModal" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="top" title="<?= TESTIMONIAL_ALERT ?>">
                <?= __('Add request') ?>
            </button>
        <?php endif; ?>

        <!-- 0 for hiding purpose only -->
        <?php if (!empty($get_request) && 0) {
            echo '<br/><small>';
            echo 'Status: ';
            switch ($get_request['job_testimonial_request_current_status']) {
                case REQUEST_PENDING:
                    echo 'Request pending';
                    echo '<hr />';
                    echo '<a href="' . l(TUTORIAL_PATH . APPLY_JOB_TUTORIAL) . '" target="_blank"><i class="fa fa-film"></i> Testimonial Tutorial</a>';
                    break;
                case REQUEST_ACCEPTED:
                    echo 'Request accepted';
                    break;
                case REQUEST_REJECTED:
                    echo 'Request rejected';
                    break;
                case REQUEST_EXTENDED:
                    echo 'Request extended';
                    if ($get_request['job_testimonial_request_extention'] && validateDate($get_request['job_testimonial_request_extention'], 'Y-m-d H:i:s')) {
                        echo ' ' . 'till: ' . date('d M, Y', strtotime($get_request['job_testimonial_request_extention']));
                    }
                    break;
            }
            echo '</small>';
        }
        ?>
    </form>

</div>

<div class="grid">

    <div style="display: none;" id="testimonialRequestModal" class="animated-modal">
        <h4>Send request to administrator</h4>
        <small class="text-custom">Note: <?= TESTIMONIAL_ALERT ?></small>
        <form id="job_testimonial_request_form" action="javascript:;" method="POST" novalidate>
            <input type="hidden" name="_token" />
            <input type="hidden" name="job_testimonial_request[job_testimonial_request_signup_id]" value="<?= $this->userid ?>" />
            <div class="form-group">
                <label>Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="job_testimonial_request[job_testimonial_request_desc]" placeholder="Add request description" minlength="10" maxlength="1000" required></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-custom">Save</button>
            </div>
        </form>
    </div>

</div>

<script>
    $(document).ready(function() {

        $('input[name=isFile]').on('change', function(){
            if($('input[name=isFile]').is(':checked')) {
                $('#coverLetterTextCheck').html('Uncheck to write the cover letter instead')
            } else {
                $('#coverLetterTextCheck').html('Check to upload the cover letter instead')
            }
            $('#coverLetterFileArea').toggleClass('d-none')
            $('#coverLetterTextArea').toggleClass('d-none')
        })

        const dt = new DataTransfer();
        const dt2 = new DataTransfer();

        $('#upload-video').on('change', function() {
            for (var i = 0; i < this.files.length; i++) {
                // 100000000 = 100 MB
                // 10000000 = 10 MB
                // 1000000 = 1 MB
                // 100000 = 100 KB
                let fileBloc = $('<span/>', {
                        class: 'file-block'
                    }),
                    fileName = $('<span/>', {
                        class: 'name',
                        text: this.files.item(i).name
                    });
                console.log(this.files.item(i).size);
                if (this.files.item(i).size < '<?= MAX_ATTACHMENT_SIZE_LIMIT ?>') {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }
                $("#videoList > #video-names").html('')
                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };
            dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                let name = $(this).next('span.name').text();
                $(this).parent().remove();
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        dt.items.remove(i);
                        continue;
                    }
                }
                // document.getElementById('attachment').files = dt.files;
            });
        })

        $('#upload-files').on('change', function() {

            for (var i = 0; i < this.files.length; i++) {
                // 100000000 = 100 MB
                // 10000000 = 10 MB
                // 1000000 = 1 MB
                // 100000 = 100 KB
                let fileBloc = $('<span/>', {
                        class: 'file-block'
                    }),
                    fileName = $('<span/>', {
                        class: 'name',
                        text: this.files.item(i).name
                    });
                if (this.files.item(i).size < '<?= GENERAL_ATTACHMENT_SIZE_LIMIT ?>') {
                    fileBloc.append('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.append('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }
                $("#filesList > #files-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };
            for (let file of this.files) {
                dt2.items.add(file);
            }
            this.files = dt2.files;

            $('a.file-delete').click(function() {
                let name = $(this).next('span.name').text();
                $(this).parent().remove();
                for (let i = 0; i < dt2.items.length; i++) {
                    if (name === dt2.items[i].getAsFile().name) {
                        dt.items.remove(i);
                        continue;
                    }
                }
                // document.getElementById('attachment').files = dt2.files;
            });
        })

        function ajax_submit() {
            let error = false;
            $('#upload-files').each(function(index, ele) {
                if(ele.files.length) {
                    for (var i = 0; i < ele.files.length; i++) {
                        const file = ele.files[i];
                        if (file.size > 10000000) {
                            error = true;
                        }
                    }
                }
            })
            $('#upload-video').each(function(index, ele) {
                if(ele.files.length) {
                    for (var i = 0; i < ele.files.length; i++) {
                        const file = ele.files[i];
                        if (file.size > 50000000) {
                            error = true;
                        }
                    }
                }
            })
            $('.upload-answer').each(function(index, ele) {
                if(ele.files.length) {
                    for (var i = 0; i < ele.files.length; i++) {
                        const file = ele.files[i];
                        if (file.size > 10000000) {
                            error = true;
                        }
                    }
                }
            })
            if (error) {
                $.dialog({
                    title: '<?= __("Error") ?>',
                    content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                });
                return false;
            }
            var data = new FormData(document.getElementById('job_application_proposal_form'))
            var url = '<?= l('job/apply_job') ?>';

            AjaxRequest.fileAsyncRequest(url, data, false, '#job_application_proposal_form_btn', 'Applying ...', 'Apply now').then(
                function(response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt, 'Success');
                        if (response.redirect_url != undefined && response.redirect_url != null) {
                            window.location.href = response.redirect_url;
                        } else {
                            window.location = '<?= l('dashboard/job/detail/') . (isset($job['job_slug']) ? $job['job_slug'] : '') ?>';
                        }
                    }
                }
            )
        }

        function prompt_message(message) {
            $.confirm({
                title: '<?= __("Warning!") ?>',
                content: message,
                buttons: {
                    cancel: function() {},
                    'proceed anyway': function() {
                        ajax_submit()
                    },
                }
            });
        }

        $('#job_application_proposal_form').on('submit', function() {
            if ($('textarea[name=job_application_cover_letter]').val() === '' && ($('input[name=job_application_cover_letter]').get(0).files.length === 0)) {
                prompt_message('You haven\'t added any cover letter yet!')
            } else if ($('input[name="job_application_attachments[]"]').get(0).files.length === 0) {
                prompt_message('You haven\'t added any attachment yet!')
            } else {
                ajax_submit();
            }
        })

        $('body').on('submit', '#job_testimonial_request_form', function() {
            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"));
            var data = $(this).serialize();
            var url = "<?php echo l('job/saveJobTestimonialRequest'); ?>";

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
                        AdminToastr.success(response.txt, 'Success');
                        $('.fancybox-close-small').trigger('click')
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
    		    }
		    )
        })
    })
</script>