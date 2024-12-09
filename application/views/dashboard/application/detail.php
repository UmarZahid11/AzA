<style>
    .slick-prev:before,
    .slick-next:before {
        color: #000;
    }

    .slick-slide {
        height: max-content;
    }

    .card-elements {
        background-color: #fff !important;
    }
</style>

<div class="dashboard-content posted-theme">
    <?php if ($job_application['job_application_request_status'] == 1 && $this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid && $this->model_job_milestone->all_milestone_complete($job_application['job_id'], $job_application['job_application_id'])) : ?>
        <?php if (!$job_application['job_completion_status']) : ?>
            <a href="javascript:;" data-id="<?= $job_application['job_id'] ?>" data-update="job_completion_status" data-value="1" class="btn btn-custom updateJobBtn float-right"><?= __('Mark as complete') ?></a>
        <?php endif; ?>
    <?php endif; ?>
    <?php if ($job_application['job_completion_status']) : ?>
        <a href="javascript:;" class="float-right"><i class="fa fa-circle-check"></i>&nbsp;<?= __('Job completed') ?></a>
    <?php endif; ?>
    <i class="fa fa-address-card-o"></i>
    <h4><?= __('Job application detail') . (isset($job_application) && is_array($job_application) ? ' ' . __('of') . ' "' . $this->model_signup->profileName($job_application, FALSE) . '"' : '') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . SENDING_PAYING_MILESTONE_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Sending paying milestone Tutorial</a>
    <hr />

    <div class="row">
        <div class="col-6 col-md-6">
            <div class="mb-2">
                <h5 class="m-0"><?= __('Cover Letter') ?>:</h5>
                <?php if (isset($job_application['job_application_cover_letter'])) : ?>
                    <?php if(isset($job_application['job_application_is_cover_letter_file']) && $job_application['job_application_is_cover_letter_file']): ?>
                        <a href="<?php echo l($job_application['job_application_cover_letter']); ?>" target="_blank"><i class="fa fa-file"></i> <?= __('View cover letter') ?>.</a>
                    <?php else: ?>
                        <small>
                            <?= strip_string($job_application['job_application_cover_letter'], 1000) ?>
                            <?php if (strlen($job_application['job_application_cover_letter']) >= 1000) : ?>
                                <a data-fancybox data-animation-duration="700" data-src="#coverLetterModal" href="javascript:;"><?= __('see more') ?></a>
                                <div class="grid">
                                    <div style="display: none; padding: 44px !important;" id="coverLetterModal" class="animated-modal">
                                        <h2><?= __('Cover Letter') ?></h2>
                                        <?= $job_application['job_application_cover_letter'] ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </small>
                    <?php endif; ?>
                <?php else : ?>
                    <?= __(NOT_AVAILABLE); ?>
                <?php endif; ?>
            </div>

            <div class="mb-2">
                <h5 class="m-0"><?= __('Resume') ?>:</h5>
                <?php if (isset($job_application['job_application_resume']) && ($job_application['job_application_resume'])) : ?>
                    <a href="<?php echo l($job_application['job_application_resume']); ?>" target="_blank"><i class="fa fa-file"></i> <?= __('View resume') ?>.</a>
                <?php else : ?>
                    <?= __(NOT_AVAILABLE); ?>
                <?php endif; ?>
            </div>

            <div class="mb-2">
                <h5 class="m-0"><?= __('Attachments') ?>:</h5>
                <?php if (isset($job_application_attachment) && count($job_application_attachment) > 0) : ?>
                    <?php foreach ($job_application_attachment as $key => $value) : ?>
                        <a href="<?php echo get_image($value['job_application_attachment_path'], $value['job_application_attachment_name']); ?>" target="_blank"><i class="fa fa-file"></i> <?= __('View attachment no') ?>&nbsp;<?= $key + 1 ?>.</a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?= __(NOT_AVAILABLE); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-4 col-md-4 offset-2">
            <p>View applicant profile <a target="_blank" href="<?= l('dashboard/profile/detail/' . JWT::encode($job_application['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $job_application['signup_type']) ?>"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a>.</p>
            <p>View job details <a target="_blank" href="<?= l('dashboard/job/detail/' . $job_application['job_slug']) ?>"><?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a>.</p>
            <?php if (($this->model_signup->hasPremiumPermission() && $job_application['job_application_signup_id'] == $this->userid) || ($job_application['job_userid'] == $this->userid)) : ?>
                <p>
                    <?= __('Application Status') ?>:
                    <?php
                    switch ($job_application['job_application_request_status']) {
                        case 0:
                            echo '<span class="text-warning">Pending</span>';
                            break;
                        case 1:
                            echo '<span class="text-success">Approved</span>';
                            break;
                        case 2:
                            echo '<span class="text-danger">Declined</span>';
                            break;
                    }
                    ?>
                </p>
            <?php endif; ?>

            <?php if ($this->model_job_application->jobAssignedToThis($job_application['job_id'], $job_application['job_application_signup_id']) || !$this->model_job_application->jobAlreadyAssigned($job_application['job_id'])) : ?>

                <?php if ($this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid && !$job_application['job_completion_status']) : ?>
                    <form action="javascript:;" id="job_application_request_form" method="POST">
                        <input type="hidden" name="job_application_id" value="<?= $job_application['job_application_id'] ?>" />
                        <input type="hidden" name="job_id" value="<?= $job_application['job_id'] ?>" />
                        <input type="hidden" name="job_application_signup_id" value="<?= $job_application['job_application_signup_id'] ?>" />
                        <div class="mb-2">
                            <p class="m-0"><?= __('Action') ?>:</p>
                            <div class="row">
                                <div class="col-md-8">
                                    <select class="form-select font-12" name="job_application_request_status" required>
                                        <option disabled>Select application status</option>
                                        <option value="0" <?= $job_application['job_application_request_status'] == '0' ? 'selected' : '' ?>><?= __('Pending') ?></option>
                                        <option value="1" <?= $job_application['job_application_request_status'] == '1' ? 'selected' : '' ?>><?= __('Approve') ?></option>
                                        <option value="2" <?= $job_application['job_application_request_status'] == '2' ? 'selected' : '' ?>><?= __('Decline') ?></option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-custom" id="job_application_request_form_btn"><?= __('Update') ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>

            <?php endif; ?>

            <div class="mb-2">
                <h5 class="m-0"><?= __('Questions Response') ?>:</h5>
                <?php if (isset($job_question) && count($job_question) > 0) : ?>
                    <div class="slick-slider mt-2">
                        <?php foreach ($job_question as $key => $value) : ?>
                            <div>
                                <p><?php echo $value['job_question_title'] ?></p>
                                <?php if (($value['job_question_answer_attachment'] || $value['job_question_answer_desc']) && $value['job_question_answer_status']) : ?>
                                    <?php if($value['job_question_answer_desc']): ?>
                                        <p>Answer: <?= $value['job_question_answer_desc'] ?></p>
                                    <?php endif; ?>
                                    <?php if($value['job_question_answer_attachment']): ?>
                                        <a data-fancybox href="<?= get_image($value['job_question_answer_attachment_path'], $value['job_question_answer_attachment']) ?>">
                                            <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                                        </a>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <?= __(NOT_AVAILABLE); ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <?= __(NOT_AVAILABLE); ?>
                <?php endif; ?>
            </div>

        </div>

        <?php if ($this->model_signup->hasPremiumPermission()) : ?>

            <div id="milestone-section-id">
                <div class="row">
                    <?php if ($job_application['job_application_request_status'] == '1') : ?>
                        <hr />
                        <div class="col-md-4">
                            <h4><?= __('Payments') ?></h4>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-2"><?= __('Budget') ?><p><b><?= job_budget_string($job_application['job_salary_lower'], $job_application['job_salary_upper'], $job_application['job_salary_interval']); ?></b></p>
                                </div>
                                <div class="col-md-2"><?= __('In Escrow') ?><p><b><?= price($this->model_job_milestone_payment->milestone_payment($job_milestone_payment, MILESTONE_PAYMENT_ESCROW, 'job_milestone_payment_due')); ?></b></p>
                                </div>
                                <div class="col-md-2"><?= __('Platform fee') . ' (' . g('db.admin.service_fee') . '%)' ?><p><b><?= g('db.admin.service_fee') > 0 ? price((1 / (int) g('db.admin.service_fee')) * ($this->model_job_milestone_payment->milestone_payment($job_milestone_payment, 0, 'job_milestone_payment_amount', true))) : price(0);  ?></b></p>
                                </div>
                                <div class="col-md-2"><?= __('Milestone paid') ?><p><b><?= price($this->model_job_milestone_payment->milestone_payment($job_milestone_payment, MILESTONE_PAYMENT_PAID, 'job_milestone_payment_due')); ?></b></p>
                                </div>
                                <div class="col-md-2 border-right-1"><?= __('Remaining') ?>
                                    <p>
                                        <b>
                                            <?= price((int) job_budget_int($job_application['job_salary_lower'], $job_application['job_salary_upper'], $job_application['job_salary_interval']) - ((int) $this->model_job_milestone_payment->milestone_payment($job_milestone_payment, MILESTONE_PAYMENT_PAID, 'job_milestone_payment_amount'))); ?>
                                        </b>
                                    </p>
                                </div>
                                <div class="col-md-2"><?= __('Total spent') ?><p><b><?= price($this->model_job_milestone_payment->milestone_payment($job_milestone_payment, MILESTONE_PAYMENT_PAID, 'job_milestone_payment_amount')); ?></b></p>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="col-md-4">
                            <h4><?= __('Milestones') ?></h4>
                        </div>
                        <?php if ($this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid) : ?>
                            <?php if (!$job_application['job_completion_status']) : ?>
                                <div class="col-md-3 offset-5">
                                    <a data-fancybox data-animation-duration="700" data-src="#milestoneModal" href="javascript:;" class="btn btn-custom"><?= __('Add milestone') ?></a>
                                </div>
                            <?php endif; ?>

                            <div class="grid">

                                <div style="display: none; padding: 44px !important;" id="milestoneModal" class="animated-modal">
                                    <h2><?= __('Add new milestone') ?></h2>
                                    <form id="milestoneForm" action="javascript:;" method="POST" novalidate>
                                        <input type="hidden" name="_token" value="" />
                                        <input type="hidden" name="job_milestone[job_milestone_job_id]" value="<?= $job_application['job_id'] ?>" />
                                        <input type="hidden" name="job_milestone[job_milestone_application_id]" value="<?= $job_application['job_application_id'] ?>" />
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label><?= __('Title') ?> <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="job_milestone[job_milestone_title]" minlength="3" maxlength="100" required autocomplete="off" />
                                                <small class="invalid-feedback"><?= __('A valid title is required') ?>.</small>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label><?= __('Deposit Amount') ?> <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="job_milestone[job_milestone_amount]" min="1" required autocomplete="off" />
                                                <small class="invalid-feedback"><?= __('A valid deposit amount is required') ?>.</small>
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label><?= __('Due') ?> <small><?= __('UTC') ?></small> <span class="text-danger">*</span></label>
                                                <input type="date" id="datePickerId" class="form-control" name="job_milestone[job_milestone_due_date]" required />
                                                <small class="invalid-feedback"><?= __('A valid milestone due date is required.') ?>.</small>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label><?= __('Description') ?> <span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="job_milestone[job_milestone_text]" minlength="50" maxlength="1000" required></textarea>
                                                <small class="invalid-feedback"><?= __('A valid description is required of minimum 50 letters') ?>.</small>
                                            </div>
                                            <div class="form-group mt-2">
                                                <button type="submit" class="btn btn-custom" id="milestoneFormBtn"><?= __('Submit') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        <?php elseif (isset($job_milestone) && count($job_milestone) > 0 && $this->model_signup->hasPremiumPermission() && $job_application['job_application_signup_id'] == $this->userid && !$job_application['job_completion_status']) : ?>
                            <div class="col-md-3 offset-5">
                                <button class="btn btn-custom updateMilestoneStatus" disabled><?= __('Update milestone') ?></button>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php if ($job_application['job_application_request_status'] == 1) : ?>
                        <?php if (isset($job_milestone) && count($job_milestone) > 0) : ?>
                            <div class="milestone-section my-3">
                                <div class="card-deck">
                                    <?php foreach ($job_milestone as $key => $value) : ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <?php if ($value['job_milestone_lock_status'] && !$value['job_milestone_completion_status']) : ?>
                                                    <i class="fa fa-lock"></i>
                                                <?php else : ?>
                                                    <i class="fa-solid fa-ellipsis-vertical"></i><i class="fa-solid fa-ellipsis-vertical"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-md-9">
                                                        <h4 class="card-title"><?= $value['job_milestone_title'] ? $value['job_milestone_title'] : 'Title unavailable.' ?></h4>
                                                        <p class="card-text">
                                                            <?= strip_string($value['job_milestone_text']) ?>
                                                            <?php if (strlen($value['job_milestone_text']) >= 50) : ?>
                                                                <a data-fancybox data-animation-duration="700" data-src="#milestoneTextModal<?= $key ?>" href="javascript:;"><?= __('see more') ?></a>
                                                        <div class="grid">
                                                            <div style="display: none; padding: 44px !important;" id="milestoneTextModal<?= $key ?>" class="animated-modal">
                                                                <h2><?= $value['job_milestone_title'] ?></h2>
                                                                <?= $value['job_milestone_text'] ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    </p>

                                                    <p class="card-text">
                                                        <?= validateDate($value['job_milestone_due_date'], 'Y-m-d H:i:s') ? 'Due: ' . date('d, M Y', strtotime($value['job_milestone_due_date'])) : __(NOT_AVAILABLE) ?>&nbsp;|&nbsp;<span><?= __('Amount') ?>:&nbsp;<?= isset($value['job_milestone_amount']) ? price($value['job_milestone_amount']) : __(NOT_AVAILABLE) ?></span>
                                                    </p>
                                                    <?php if ($value['job_milestone_updatedon']) : ?>
                                                        <?php if ($value['job_milestone_last_update_by']) : ?>
                                                            <?php $last_updated_by = $this->model_signup->find_by_pk($value['job_milestone_last_update_by']); ?>
                                                            <small>
                                                                <?= __('Last update by') . ': ' ?><a href="<?= l('dashboard/profile/detail/') . JWT::encode($last_updated_by['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $last_updated_by['signup_type'] ?>" target="_blank"><?= $this->model_signup->profileName($last_updated_by, FALSE); ?></a>
                                                            </small>&centerdot;
                                                            <small data-toggle="tooltip" data-bs-placement="<?= date('d, M Y h:i a', strtotime($value['job_milestone_updatedon'])) ?>">
                                                                <?= timeago($value['job_milestone_updatedon']) ?>
                                                            </small>
                                                            <br />
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <small data-toggle="tooltip" data-bs-placement="top" title="
                                                                <?php
                                                                switch ($value['job_milestone_request_status']) {
                                                                    case 0:
                                                                        echo __('This milestone hasn\'t been accepted by the associate.');
                                                                        break;
                                                                    case 1:
                                                                        echo __('This milestone has been accepted by the associate.');
                                                                        break;
                                                                    case 2:
                                                                        echo __('This milestone has been rejected by the associate.');
                                                                        break;
                                                                }
                                                                ?>
                                                            ">
                                                            <?= __('Status') ?>:
                                                            <?php
                                                            switch ($value['job_milestone_request_status']) {
                                                                case 0:
                                                                    echo '<span class="text-warning">' . __('Pending') . '</span>';
                                                                    break;
                                                                case 1:
                                                                    echo '<span class="text-success">' . __('Accepted') . '</span>';
                                                                    break;
                                                                case 2:
                                                                    echo '<span class="text-danger">' . __('Declined') . '</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </small><br />
                                                        <!-- VIEW -->
                                                        <a data-fancybox data-animation-duration="700" data-src="#milestoneViewModal<?= $key ?>" href="javascript:;"><i class="fa fa-eye"></i>&nbsp;<?= __('View') ?></a>

                                                        <?php if ($this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid) : ?>
                                                            <!-- IF NOT ACCEPTED AND NOT LOCKED  -->
                                                            <?php //if ($value['job_milestone_request_status'] != '1' && !$value['job_milestone_lock_status']) :
                                                            ?>
                                                            <?php if (!$value['job_milestone_lock_status']) : ?>
                                                                |
                                                                <!-- EDIT -->
                                                                <a data-fancybox data-animation-duration="700" data-src="#milestoneEditModal<?= $key ?>" href="javascript:;"><i class="fa fa-edit"></i>&nbsp;<?= __('Edit') ?></a>
                                                                |
                                                                <!-- DELETE -->
                                                                <a href="#" class="milestoneBtn" data-type="delete" data-update="job_milestone_status" data-value="0" data-id="<?= $value['job_milestone_id'] ?>"><i class="fa fa-trash-can"></i>&nbsp;<?= __('Delete') ?></a>
                                                            <?php endif; ?>

                                                        <?php elseif ($this->model_signup->hasPremiumPermission() && $job_application['job_application_signup_id'] == $this->userid) : ?>
                                                            <?php if ($value['job_milestone_request_status'] == 0 || $value['job_milestone_request_status'] == 2) : ?>
                                                                <br />
                                                                <div class="o-switch btn-group" data-toggle="buttons" role="group">
                                                                    <?php if ($value['job_milestone_request_status'] == 0) : ?>
                                                                        <label class="btn btn-custom">
                                                                            <input class="request_status_radio" type="radio" name="job_milestone_request_status[<?= $value['job_milestone_id'] ?>]" data-id="<?= $value['job_milestone_id'] ?>" autocomplete="off" value="0" <?= $value['job_milestone_request_status'] == 0 ? 'checked' : '' ?> /> <?= __('Pending') ?>
                                                                        </label>
                                                                    <?php endif; ?>
                                                                    <label class="btn btn-custom">
                                                                        <input class="request_status_radio" type="radio" name="job_milestone_request_status[<?= $value['job_milestone_id'] ?>]" data-id="<?= $value['job_milestone_id'] ?>" autocomplete="off" value="1" <?= $value['job_milestone_request_status'] == 1 ? 'checked' : '' ?> /> <?= __('Accept') ?>
                                                                    </label>
                                                                    <label class="btn btn-custom">
                                                                        <input class="request_status_radio" type="radio" name="job_milestone_request_status[<?= $value['job_milestone_id'] ?>]" data-id="<?= $value['job_milestone_id'] ?>" autocomplete="off" value="2" <?= $value['job_milestone_request_status'] == 2 ? 'checked' : '' ?> /> <?= __('Decline') ?>
                                                                    </label>
                                                                </div>
                                                            <?php elseif ($value['job_milestone_request_status'] == 1) : ?>
                                                                <?php if (!$value['job_milestone_lock_status'] && !$this->model_job_milestone->locked_milestone_exists($job_application['job_id'], $job_application['job_application_id'])) : ?>
                                                                    <br />
                                                                    <?php if ($this->model_job_milestone->allowed_milestone_to_start($job_application['job_id'], $job_application['job_application_id'], $value['job_milestone_id'])) : ?>
                                                                        <a href="javascript:;" class="btn btn-custom milestoneBtn" data-type="start" data-update="job_milestone_lock_status" data-value="1" data-id="<?= $value['job_milestone_id'] ?>"><?= __('Start milestone') ?></a>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <?php if ($value['job_milestone_lock_status']) : ?>

                                                            <?php $job_milestone_payment = $this->model_job_milestone_payment->find_one_active(
                                                                array(
                                                                    'where' => array(
                                                                        'job_milestone_payment_milestone_id' => $value['job_milestone_id']
                                                                    )
                                                                )
                                                            ); ?>
                                                            <br />
                                                            <?= __('Task status') ?>:
                                                            <?php if (!$value['job_milestone_completion_status']) : ?>
                                                                <?= $value['job_milestone_lock_status'] ? 'Active' : 'Inactive' ?>
                                                            <?php else : ?>
                                                                <?php switch ($value['job_milestone_completion_status']) {
                                                                    case MILESTONE_COMPLETE:
                                                                        echo '<span class="text-success">' . __('Completed') . '</span>';
                                                                        break;
                                                                    case MILESTONE_REVISION:
                                                                        echo '<span class="text-danger">' . __('Revision required') . '</span>';
                                                                        break;
                                                                    case MILESTONE_PROCESSING:
                                                                        echo '<span>' . __('Processing') . '</span>';
                                                                        break;
                                                                }
                                                                ?>
                                                            <?php endif; ?>

                                                            <?php if (!empty($job_milestone_payment) && $job_milestone_payment['job_milestone_payment_method'] == MILESTONE_PLAID_PAYMENT && $job_milestone_payment['job_milestone_payment_money_position_status'] == MILESTONE_PAYMENT_ESCROW && $this->userid == $value['job_application_signup_id'] && $this->model_signup->hasPremiumPermission()) : ?>
                                                                <br />
                                                                <a href="javascript:;" class="plaid_transfer" data-id="<?= $value['job_milestone_id'] ?>" data-amount="<?= ($job_milestone_payment['job_milestone_payment_due']) ?>" data-mode="<?= PLAID_TRANSFER_MODE_DISBURSEMENT ?>" data-job_owner="0" data-job_applicant="1" data-toggle="tooltip" title="Receive funds through plaid"><img class="w-7 hue-rotate" src="<?= g('dashboard_images_root') ?>plaid.png" alt="" /> Receive funds</a>
                                                            <?php endif; ?>

                                                            <?php if (in_array($value['job_milestone_completion_status'], [MILESTONE_REVISION, MILESTONE_INCOMPLETE, MILESTONE_PROCESSING]) && $this->model_signup->hasPremiumPermission() && $job_application['job_application_signup_id'] == $this->userid) : ?>
                                                                <br /><a href="javascript:;" data-fancybox data-animation-duration="700" data-src="#submitMilestoneModal<?= $key ?>" data-update="job_milestone_completion_status" data-value="3" data-id="<?= $value['job_milestone_id'] ?>"><i class="fa fa-paper-plane"></i>&nbsp;<?= $value['job_milestone_completion_status'] == '0' ? __('Submit milestone') : __('Submit revision') ?></a>
                                                                <div class="grid">
                                                                    <div style="display: none; padding: 44px !important;" id="submitMilestoneModal<?= $key ?>" class="animated-modal">
                                                                        <h2><?= __('Submit milestone') ?></h2>
                                                                        <form class="submitMilestoneForm" action="javascript:;" method="POST" data-id="<?= $value['job_milestone_id'] ?>" novalidate>
                                                                            <input type="hidden" name="_token" value="" />
                                                                            <input type="hidden" name="job_milestone_id" value="<?= $value['job_milestone_id'] ?>" />
                                                                            <div class="form-group">
                                                                                <label><?= __('Attachment') ?> <span class="text-danger">*</span></label>
                                                                                <input type="file" class="form-control" name="job_milestone_attachment_name" required />
                                                                                <small class="invalid-feedback"><?= __('An attachment is required') ?></small>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label><?= __('Message (Optional)') ?></label>
                                                                                <textarea class="form-control" name="job_milestone_attachment_text" minlength="0" maxlength="1000"></textarea>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <button type="submit" class="btn btn-custom" id="submitMilestoneFormBtn<?= $value['job_milestone_id'] ?>"><?= __('Submit') ?></button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                                <?php
                                                                switch ($value['job_milestone_completion_status']) {
                                                                    case MILESTONE_COMPLETE:
                                                                    case MILESTONE_REVISION:
                                                                    case MILESTONE_PROCESSING:
                                                                        echo '<p class="m-0"><a href="javascript:;" data-fancybox data-animation-duration="700" data-src="#milestoneAttachmentViewModal' . $key . '"><i class="fa fa-eye"></i> ' . __('View submissions') . '</a></p>';
                                                                        break;
                                                                }
                                                                ?>
                                                                <?php if ($value['job_milestone_completion_status'] >= 1) : ?>
                                                                    <div class="grid">
                                                                        <div style="display: none; padding: 44px !important;" id="milestoneAttachmentViewModal<?= $key ?>" class="animated-modal">
                                                                            <h2><?= __('View submission') ?></h2>
                                                                            <?php
                                                                            $job_milestone_attachment = $this->model_job_milestone_attachment->find_all_active(
                                                                                array(
                                                                                    'where' => array(
                                                                                        'job_milestone_attachment_milestone_id' => $value['job_milestone_id']
                                                                                    ),
                                                                                    'order' => 'job_milestone_attachment_id DESC',
                                                                                    'limit' => 10
                                                                                )
                                                                            );
                                                                            ?>
                                                                            <?php if (count($job_milestone_attachment) > 0) : ?>
                                                                                <?php foreach ($job_milestone_attachment as $key_attachment => $value_attachment) : ?>
                                                                                    <div class="float-right"><?= __('Submitted') . ': ' . date('d, M Y h:i a', strtotime($value_attachment['job_milestone_attachment_createdon'])) ?></div>
                                                                                    <a href="<?php echo get_image($value_attachment['job_milestone_attachment_path'], $value_attachment['job_milestone_attachment_name']); ?>" target="_blank"><i class="fa fa-file"></i> <?= __('View attachment no') ?>&nbsp;<?= $key_attachment + 1 ?>.</a>
                                                                                    <?php if ($value_attachment['job_milestone_attachment_text']) : ?>
                                                                                        <p><?= $value_attachment['job_milestone_attachment_text'] ?></p>
                                                                                    <?php endif; ?>
                                                                                    <hr />
                                                                                <?php endforeach; ?>
                                                                            <?php else : ?>
                                                                                <?= __('No submissions available') ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                    <?php if ($this->model_signup->hasPremiumPermission() && $this->userid == $job_application['job_userid'] && $value['job_milestone_completion_status'] != MILESTONE_COMPLETE) : ?>
                                                                        <a href="javascript:;" data-fancybox data-animation-duration="700" data-src="#milestoneActionModal<?= $key ?>">
                                                                            <i class="fa fa-tasks"></i>&nbsp;<?= __('Action') ?>
                                                                        </a>
                                                                        <div class="grid">
                                                                            <div style="display: none; padding: 44px !important;" id="milestoneActionModal<?= $key ?>" class="animated-modal">
                                                                                <h2><?= __('Action') ?></h2>
                                                                                <form class="submissionActionForm" id="submissionActionForm<?= $value['job_milestone_id'] ?>" action="javascript:;" data-id="<?= $value['job_milestone_id'] ?>" method="POST" novalidate>
                                                                                    <input type="hidden" name="_token" value="" />
                                                                                    <input type="hidden" name="payment_status" id="payment_status<?= $value['job_milestone_id'] ?>" value="<?= !empty($job_milestone_payment) && $job_milestone_payment['job_milestone_payment_charge_id'] ? TRUE : FALSE; ?>" />

                                                                                    <input type="hidden" name="type" value="status_action" />
                                                                                    <input type="hidden" name="job_milestone_id" value="<?= $value['job_milestone_id'] ?>" />
                                                                                    <div class="form-group">
                                                                                        <label><?= __('Status') ?> <span class="text-danger">*</span></label>
                                                                                        <select class="form-select" name="job_milestone[job_milestone_completion_status]" data-id="<?= $value['job_milestone_id'] ?>" required>
                                                                                            <option value=""><?= __('Mark as') ?></option>
                                                                                            <option value="<?= MILESTONE_COMPLETE ?>" <?= $value['job_milestone_completion_status'] == MILESTONE_COMPLETE ? 'selected' : '' ?>><?= __('Complete') ?></option>
                                                                                            <option value="<?= MILESTONE_REVISION ?>" <?= $value['job_milestone_completion_status'] == MILESTONE_REVISION ? 'selected' : '' ?>><?= __('Revision required') ?></option>
                                                                                        </select>
                                                                                        <small class="invalid-feedback"><?= __('Select a valid action status') ?></small>
                                                                                    </div>
                                                                                    <div class="form-group">
                                                                                        <label><?= __('Message (optional)') ?></label>
                                                                                        <textarea class="form-control" name="job_milestone[job_milestone_comment]" maxlength="5000"></textarea>
                                                                                    </div>

                                                                                    <div class="form-row card-group" id="card-group<?= $value['job_milestone_id'] ?>">
                                                                                        <label for="card-element<?= $value['job_milestone_id'] ?>">
                                                                                            Credit or debit card (Pay <?= price($value['job_milestone_amount']) ?> + 2.9% + 30¢) <span class="text-danger">*</span>
                                                                                        </label>
                                                                                        <div id="card-element<?= $value['job_milestone_id'] ?>" class="form-control card-elements">
                                                                                            <!-- A Stripe Element will be inserted here. -->
                                                                                        </div>

                                                                                        <!-- Used to display Element errors. -->
                                                                                        <small id="card-errors<?= $value['job_milestone_id'] ?>" class="text-danger" role="alert"></small>
                                                                                    </div>
                                                                                    <div class="plaid-group" id="plaid-group<?= $value['job_milestone_id'] ?>">
                                                                                        <small>OR</small><br />
                                                                                        <a href="javascript:;" class="plaid_transfer" id="plaid_transfer<?= $value['job_milestone_id'] ?>" data-id="<?= $value['job_milestone_id'] ?>" data-amount="<?= ($value['job_milestone_amount']) ?>" data-mode="<?= PLAID_TRANSFER_MODE_PAYMENT ?>" data-job_owner="<?= ($this->model_signup->hasPremiumPermission() && $this->userid == $job_application['job_userid']) ? 1 : 0 ?>" data-job_applicant="<?= ($this->model_signup->hasPremiumPermission() && $this->userid == $job_application['job_userid']) ? 0 : 1 ?>">
                                                                                            Transfer through <img src="https://plaid.com/_next/image?url=/assets/img/navbar/logo.svg&w=1920&q=75" alt="Plaid" />
                                                                                        </a>
                                                                                        <?php if ($this->user_data['signup_plaid_account_id']) : ?>
                                                                                            (<a href="javascript:;" class="remove_plaid_account">Remove saved plaid account</a>)
                                                                                        <?php endif; ?>
                                                                                    </div>

                                                                                    <div class="form-group mt-2">
                                                                                        <button type="submit" class="btn btn-custom" id="submissionActionFormBtn<?= $value['job_milestone_id'] ?>"><?= __('Submit') ?></button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                <?php endif; ?>

                                                                <!-- Comments -->
                                                                <p class="m-0"><a data-fancybox data-animation-duration="700" data-src="#providerCommentModal<?= $key ?>" href="javascript:;"><i class="fa fa-comment-o"></i>&nbsp;<?= __('Provider response') ?></a></p>
                                                                <div class="grid">
                                                                    <div style="display: none; padding: 44px !important;" id="providerCommentModal<?= $key ?>" class="animated-modal">
                                                                        <h2><?= __('Provider response for milestone') ?>:&nbsp;"<?= $value['job_milestone_title'] ?>"</h2>
                                                                        <?php
                                                                        $milestone_comments = $this->model_job_milestone_comment->find_all_active(
                                                                            array(
                                                                                'where' => array(
                                                                                    'job_milestone_comment_milestone_id' => $value['job_milestone_id']
                                                                                ),
                                                                                'order' => 'job_milestone_comment_id Desc'
                                                                            )
                                                                        );
                                                                        ?>
                                                                        <?php if (count($milestone_comments) == 0 && $value['job_milestone_comment']) : ?>
                                                                            <?= $value['job_milestone_comment'] ?>
                                                                        <?php elseif (count($milestone_comments) > 0) : ?>
                                                                            <?php foreach ($milestone_comments as $key_comment => $value_comment) : ?>
                                                                                <p><?= $value_comment['job_milestone_comment_text'] ?><span class="float-right"><?= __('Sent') ?>:&nbsp;<?= date('d, M Y h:i a', strtotime($value_comment['job_milestone_comment_createdon'])) ?></span></p>
                                                                            <?php endforeach; ?>
                                                                        <?php else : ?>
                                                                            <?= __('No comments available'); ?>.
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>

                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        <?php //if ($value['job_milestone_request_status'] != '1' && !$value['job_milestone_lock_status'] && $this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid) :
                                        ?>
                                        <?php if (!$value['job_milestone_lock_status'] && $this->model_signup->hasPremiumPermission() && $job_application['job_userid'] == $this->userid) : ?>
                                            <!-- EDIT MODAL -->
                                            <div class="grid">

                                                <div style="display: none; padding: 44px !important;" id="milestoneEditModal<?= $key ?>" class="animated-modal">
                                                    <h2><?= __('Edit milestone') ?></h2>
                                                    <form class="updateMilestoneForm" action="javascript:;" method="POST" data-id="<?= $value['job_milestone_id'] ?>" novalidate>
                                                        <input type="hidden" name="_token" value="" />
                                                        <input type="hidden" name="job_milestone_id" value="<?= $value['job_milestone_id'] ?>" />
                                                        <input type="hidden" name="job_milestone[job_milestone_job_id]" value="<?= $job_application['job_id'] ?>" />
                                                        <input type="hidden" name="job_milestone[job_milestone_application_id]" value="<?= $job_application['job_application_id'] ?>" />
                                                        <div class="row">
                                                            <div class="form-group col-md-6">
                                                                <label><?= __('Title') ?> <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="job_milestone[job_milestone_title]" minlength="3" maxlength="100" required autocomplete="off" value="<?= isset($value['job_milestone_title']) ? $value['job_milestone_title'] : '' ?>" />
                                                                <small class="invalid-feedback"><?= __('A valid title is required') ?></small>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label><?= __('Deposit Amount') ?> <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control" name="job_milestone[job_milestone_amount]" min="1" required autocomplete="off" value="<?= isset($value['job_milestone_amount']) ? $value['job_milestone_amount'] : '' ?>" />
                                                                <small class="invalid-feedback"><?= __('A valid deposit amount is required') ?></small>
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label><?= __('Due') ?> <span class="text-danger">*</span></label>
                                                                <input type="date" id="dueUTC" class="form-control" name="job_milestone[job_milestone_due_date]" value="<?= isset($value['job_milestone_due_date']) ? date('Y-m-d', strtotime($value['job_milestone_due_date'])) : '' ?>" required />
                                                                <small class="invalid-feedback"><?= __('The milestone due date is a required field') ?>.</small>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <label><?= __('Description') ?> <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" name="job_milestone[job_milestone_text]" minlength="50" maxlength="1000" required><?= isset($value['job_milestone_text']) ? $value['job_milestone_text'] : '' ?></textarea>
                                                                <small class="invalid-feedback"><?= __('A valid description is required of minimum 50 letters') ?>.</small>
                                                            </div>
                                                            <div class="form-group mt-2">
                                                                <button type="submit" class="btn btn-custom" id="updateMilestoneFormBtn<?= $value['job_milestone_id'] ?>"><?= __('Submit') ?></button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                            </div>
                                            <!-- EDIT MODAL -->
                                        <?php endif; ?>

                                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                            <!-- VIEW MODAL -->
                                            <div class="grid">

                                                <div style="display: none; padding: 44px !important;" id="milestoneViewModal<?= $key ?>" class="animated-modal">
                                                    <h2><?= __('View milestone') ?></h2>
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label><?= __('Title') ?></label>
                                                            <p><?= $value['job_milestone_title'] ?></p>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label><?= __('Deposit Amount') ?></label>
                                                            <p><?= price($value['job_milestone_amount']) ?></p>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label><?= __('Due Date') ?></label>
                                                            <p><?= validateDate($value['job_milestone_due_date'], 'Y-m-d H:i:s') ? date('d, M Y', strtotime($value['job_milestone_due_date'])) : __(NOT_AVAILABLE) ?></p>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label><?= __('Description') ?></label>
                                                            <p><?= $value['job_milestone_text'] ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- VIEW MODAL -->
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="col-md-12">
                                <?= __('Milestone are not available yet!') ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>

<script>
    function mount_stripe(stripe, card, milestone_id) {
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element' + milestone_id);
        $('#card-group' + milestone_id).show()
    }

    function unmount_stripe(card, milestone_id) {
        card.unmount();
        $('#card-group' + milestone_id).hide()
    }

    const stripeTokenHandler = (token, milestone_id) => {
        // Insert the token ID into the form so it gets submitted to the server
        const form = document.getElementById('submissionActionForm' + milestone_id);
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

    const plaidIntentIdHandler = (transfer_intent_id, milestone_id) => {
        // Insert the token ID into the form so it gets submitted to the server
        const form = document.getElementById('submissionActionForm' + milestone_id);
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'transfer_intent_id');
        hiddenInput.setAttribute('value', transfer_intent_id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

    $(document).ready(function() {

        //
        $('.card-group').hide()
        $('.plaid-group').hide()

        var element_mounted = false;

        const stripe = Stripe('<?= STRIPE_PUBLISHABLE_KEY ?>');
        const elements = stripe.elements();
        // Custom styling can be passed to options when creating an Element.
        const style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '16px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        const card = elements.create('card', {
            style
        });

        //
        $('body').on('change', 'select[name="job_milestone[job_milestone_completion_status]"]', function() {
            milestone_id = $(this).data('id');
            if ($(this).find(':selected').val() == '<?= MILESTONE_COMPLETE ?>' && !$('#payment_status' + milestone_id).val()) {
                mount_stripe(stripe, card, milestone_id)
                element_mounted = true;
                //
                $('#plaid-group' + milestone_id).show()
            } else {
                unmount_stripe(card, milestone_id)
                element_mounted = false;
                $('#plaid-group' + milestone_id).hide()
            }
        })

        // slider for job question videos
        $(".slick-slider").slick({
            slidesToShow: 1,
            infinite: false,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            dots: false,
            arrows: true,
        });

        // approve, disapprove clicking action change
        $("input[type='radio']:checked").each(function(index, value) {
            let count = 0;
            if ($(this).val() != 0) {
                $('.updateMilestoneStatus').removeAttr('disabled')
                count++;
            } else {
                if (!count) {
                    $('.updateMilestoneStatus').attr('disabled', true)
                }
            }
        })

        // approve, disapprove clicking action change
        $("body").on('change', '.request_status_radio', function() {
            let count = 0;
            $("input[type='radio']:checked").each(function(index, value) {
                if ($(this).val() != 0) {
                    $('.updateMilestoneStatus').removeAttr('disabled')
                    count++;
                } else {
                    if (!count) {
                        $('.updateMilestoneStatus').attr('disabled', true)
                    }
                }
            })
        })

        // approve, disapprove by job applicant action
        $('body').on('click', '.updateMilestoneStatus', function() {
            $("input[type='radio']:checked").each(function() {
                if ($(this).val() != 0) {
                    var data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'job_milestone_id': $(this).data('id'),
                        'job_milestone': {
                            'job_milestone_request_status': $(this).val(),
                            'job_milestone_job_id': '<?= $job_application['job_id'] ?>',
                            'job_milestone_application_id': '<?= $job_application['job_application_id'] ?>',
                        }
                    }

                    var url = base_url + 'job_milestone/save_milestone'

                    AjaxRequest.asyncRequest(url, data, false, '.updateMilestoneStatus', 'Updating ...', 'Update milestone').then(
                        function(response) {
                            if (response.status) {
                                $("#milestone-section-id").load(location.href + " #milestone-section-id>*", function() {
                                    $(".slick-slider").not('.slick-initialized').slick({
                                        slidesToShow: 1,
                                        infinite: false,
                                        slidesToScroll: 1,
                                        autoplay: false,
                                        autoplaySpeed: 2000,
                                        dots: false,
                                        arrows: true,
                                    });
                                    $('[data-toggle="tooltip"]').tooltip()
                                });
                            } else {
                                swal("Error", response.txt, "error");
                            }
                        }
                    )
                }
            });
        })

        var dueUTC = document.getElementById('dueUTC');
        if (dueUTC) {
            dueUTC.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];;
        }

        var datePickerId = document.getElementById('datePickerId');
        if (datePickerId) {
            datePickerId.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];
        }

        var datePickerId2 = document.getElementById('datePickerId2');
        if (datePickerId2) {
            datePickerId2.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];
        }

        // approve, disapprove job application
        $('body').on('submit', '#job_application_request_form', function() {

            var data = $('#job_application_request_form').serialize();
            var url = base_url + 'job/assign_job';

            AjaxRequest.asyncRequest(url, data, false, '#job_application_request_form_btn', 'Updating ...', 'Update').then(
                function(response) {
                    if (response.status) {
                        swal({
                            title: "Success",
                            text: response.txt,
                            icon: "success",
                        }).
                        then(() => {
                            $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                $(".slick-slider").not('.slick-initialized').slick({
                                    slidesToShow: 1,
                                    infinite: false,
                                    slidesToScroll: 1,
                                    autoplay: false,
                                    autoplaySpeed: 2000,
                                    dots: false,
                                    arrows: true,
                                });
                                $('[data-toggle="tooltip"]').tooltip()
                            });
                        });
                    } else {
                        swal("Error", response.txt, "error");
                    }
                }
            )
        })

        // create milestone by job organizer action
        $('body').on('submit', '#milestoneForm', function(event) {

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                hideLoader()
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = $(this).serialize()
            var url = base_url + 'job_milestone/save_milestone'

            AjaxRequest.asyncRequest(url, data, false, '#milestoneFormBtn', 'Submitting ...', 'Submit').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $('#milestoneForm').each(function() {
                            this.reset();
                        });
                        $("#milestone-section-id").load(location.href + " #milestone-section-id>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })

        // update a milestone action
        $('body').on('submit', '.updateMilestoneForm', function(event) {

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                hideLoader()
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            var id = $(this).data('id')
            var updateMilestoneFormBtn = '#updateMilestoneFormBtn' + id
            
            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = $(this).serialize()
            var url = base_url + 'job_milestone/save_milestone'

            AjaxRequest.asyncRequest(url, data, false, updateMilestoneFormBtn, 'Submitting ...', 'Submit').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $(".milestone-section").load(location.href + " .milestone-section>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        });

        // completion status by job organizer action
        $('body').on('submit', '.submissionActionForm', async function(event) {

            var id = $(this).data('id')

            // stripe form error
            form_error = false;
            // if stripe element is mounted
            if (element_mounted) {
                const {
                    token,
                    error
                } = await stripe.createToken(card);

                if (error) {
                    // Inform the customer that there was an error.
                    const errorElement = document.getElementById('card-errors' + id);
                    errorElement.textContent = error.message;
                    form_error = true;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(token, id);
                }
            }

            if (!$(this)[0].checkValidity() || form_error) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            var submissionActionFormBtn = '#submissionActionFormBtn' + id

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = $(this).serialize()
            var url = base_url + 'job_milestone/update_milestone'

            AjaxRequest.asyncRequest(url, data, false, submissionActionFormBtn, 'Submitting ...', 'Submit').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        try {
                            elements.clear()
                        } catch (Exception) {
                            console.log(Exception)
                        }
                        $(".milestone-section").load(location.href + " .milestone-section>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                            $('.fancybox-close-small').trigger('click')
                            $('.card-group').hide()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })

        $('body').on('submit', '.submitMilestoneForm', function(event) {

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            var id = $(this).data('id')
            var submitMilestoneFormBtn = '#submitMilestoneFormBtn' + id

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            var data = new FormData($(this)[0])
            var url = base_url + 'job_milestone/save_milestone_attachment'

            AjaxRequest.fileAsyncRequest(url, data, false, submitMilestoneFormBtn, 'Submitting ...', 'Submit').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $('.fancybox-close-small').trigger('click')
                        $('.submitMilestoneForm').each(function() {
                            this.reset();
                        });
                        $(".milestone-section").load(location.href + " .milestone-section>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })

        // start, delete milestone action
        $('body').on('click', '.milestoneBtn', function(event) {

            var id = $(this).data('id')
            var update = $(this).data('update')
            var value = $(this).data('value')
            var type = $(this).data('type')
            
            if(type == 'delete') {
                var buttonTextBeforeSend = 'Deleting ...'
                var buttonTextAfterSend = '<i class="fa fa-trash-can"></i> Delete'
            } else if(type == 'start') {
                var buttonTextBeforeSend = 'Starting ...'
                var buttonTextAfterSend = 'Start milestone'
            }
            
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
                'job_milestone_id': id,
                'job_milestone': {
                    [update]: value
                },
                'type': type
            }
            var url = base_url + 'job_milestone/update_milestone'

            AjaxRequest.asyncRequest(url, data, false, this, buttonTextBeforeSend, buttonTextAfterSend).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $(".milestone-section").load(location.href + " .milestone-section>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })

        $('body').on('click', '.updateJobBtn', function(event) {

            var id = $(this).data('id')
            var update = $(this).data('update')
            var value = $(this).data('value')
            var data = {
                'job_id': id,
                'job': {
                    [update]: value
                }
            }
            var url = base_url + 'dashboard/job/update'

            AjaxRequest.asyncRequest(url, data, false, this, 'Processing ...', 'Mark as complete').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $(".slick-slider").not('.slick-initialized').slick({
                                slidesToShow: 1,
                                infinite: false,
                                slidesToScroll: 1,
                                autoplay: false,
                                autoplaySpeed: 2000,
                                dots: false,
                                arrows: true,
                            });
                            $('[data-toggle="tooltip"]').tooltip()
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        })

        $('body').on('click', '.plaid_transfer', function() {
            
            var milestone_id = $(this).data('id');
            var mode = $(this).data('mode');
            var job_owner = $(this).data('job_owner');
            var job_applicant = $(this).data('job_applicant');
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
                'milestone_id': milestone_id,
                'amount': $(this).data('amount'),
                'mode': mode,
            }
            var url = base_url + 'dashboard/plaid/createTransferIntent'
            
            AjaxRequest.asyncRequest(url, data).then(
                function(response) {
                    if (response.status) {
                        
                        var transfer_intent_id = response.response.transfer_intent.id;
                        AjaxRequest.asyncRequest('<?= l('plaid/generate_token') ?>', {
                            '_token': $('meta[name=csrf-token]').attr("content"),
                            'type': '<?= PLAID_TYPE_TRANSFER ?>',
                            'income_type': '',
                            'transfer_intent_id': transfer_intent_id,
                            'milestone_id': milestone_id
                        }).then(
                            function(response) {
                                if (response.status) {
                                    
                                    var account_id = response.account_id
                                    const handler = Plaid.create({
                                        token: response.link_token,
                                        onSuccess: (public_token, metadata) => {

                                            if (!account_id) {
                                                var data = {
                                                    '_token': $('meta[name=csrf-token]').attr("content"),
                                                    'public_token': public_token,
                                                    'type': '<?= PLAID_TYPE_TRANSFER ?>',
                                                    'income_type': '',
                                                    'link_session_id': metadata.link_session_id,
                                                    'account_id': metadata.account_id,
                                                    'transfer_status': metadata.transfer_status,
                                                }
                                                
                                                AjaxRequest.asyncRequest('<?= l('plaid/exchange_token') ?>', data).then(
                                                    function(response) {
                                                        if (response.status) {} else {
                                                            $.dialog({
                                                                backgroundDismiss: true,
                                                                title: '<?= __("Error!") ?>',
                                                                content: response.message,
                                                            });
                                                        }
                                                    }
                                                )
                                            }
        
                                            // modal is open and stripe is mounted
                                            if (element_mounted || job_owner) {
                                                // modal fill
                                                unmount_stripe(card, milestone_id)
                                                element_mounted = false;
                                                $('#plaid-group' + milestone_id).html('<small><i class="fa fa-check-circle" aria-hidden="true"></i> Success </small>');
                                                plaidIntentIdHandler(transfer_intent_id, milestone_id)
                                            }
        
                                            if (job_applicant) {

                                                AjaxRequest.asyncRequest('<?= l('job_milestone/milestone_payment_transfer_plaid') ?>', {
                                                    '_token': $('meta[name=csrf-token]').attr("content"),
                                                    'milestone_id': milestone_id,
                                                    'transfer_intent_id': transfer_intent_id,
                                                }).then(
                                                    function(payment_response) {
                                                        if (payment_response.status) {
                                                            AdminToastr.success(payment_response.message);
                                                            $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                                                $(".slick-slider").not('.slick-initialized').slick({
                                                                    slidesToShow: 1,
                                                                    infinite: false,
                                                                    slidesToScroll: 1,
                                                                    autoplay: false,
                                                                    autoplaySpeed: 2000,
                                                                    dots: false,
                                                                    arrows: true,
                                                                });
                                                                $('[data-toggle="tooltip"]').tooltip()
                                                            });
                                                        } else {
                                                            AdminToastr.error(payment_response.message);
                                                        }
                                                    }                                                    
                                                )
                                            }
                                        },
                                        onLoad: () => {},
                                        onExit: (err, metadata) => {
                                            //  console.log(err)
                                            //  console.log('message: ' + err.error_message)
                                            if (err) {
                                                $.dialog({
                                                    backgroundDismiss: true,
                                                    title: '<?= __("Error!") ?>',
                                                    content: err.error_message,
                                                    // onClose: function() {
                                                    //     window.location.reload()
                                                    // }
                                                });
                                            }
                                        },
                                        onEvent: (eventName, metadata) => {},
                                        // required for OAuth; if not using OAuth, set to null or omit:
                                        // receivedRedirectUri: window.location.href,
                                    });
                                    // Open Link
                                    handler.open();
                                } else {
                                    $.dialog({
                                        backgroundDismiss: true,
                                        title: '<?= __("Error!") ?>',
                                        content: response.message,
                                        onClose: function() {
                                            window.location.reload()
                                        }
                                    });
                                }
                            }
                        )
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            );
        })

        $('body').on('click', '.remove_plaid_account', function() {

            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
            }
            var url = base_url + 'dashboard/plaid/remove_plaid_account'

            AjaxRequest.asyncRequest(url, data, false, this, 'Removing ...', '<small><i class="fa fa-check-circle" aria-hidden="true"></i> Removed </small>').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                    } else {
                        AdminToastr.error(response.txt);
                    }
                }
            )
        });

    })
</script>