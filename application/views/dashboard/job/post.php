<style>
    .removeQuestion {
        position: relative;
        right: 20px;
        top: 50px;
    }
</style>


<div class="dashboard-content">
    <i class="fa-light fa-pen-ruler"></i>
    <?php if($edit) : ?>
        <h4><?= __('Edit Job') ?> </h4>
    <?php else: ?>
        <h4><?= __('Post New Job') ?> </h4>
    <?php endif; ?>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . POST_JOB_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Post job Tutorial</a>
    <hr />

    <input type="hidden" name="enable_job_listing_subscription" value="<?= g('db.admin.enable_job_listing_subscription') ?>" />
    <input type="hidden" name="job_listing_subscription_fee" value="<?= g('db.admin.job_listing_subscription_fee') ?>" />
    <input type="hidden" name="job_subscription_expired" value="<?= (isset($job['job_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($job['job_subscription_expiry'])) ?>" />

    <?php if(isset($job['job_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($job['job_subscription_expiry'])): ?>
        <p class="text-danger">This job's subscription has expired on <?= date('d M, Y h:i a', strtotime($job['job_subscription_expiry'])) ?>. <small>Renew the subscription to continue listing of this job.</small></p>
    <?php endif; ?>

    <input type="hidden" name="currency" value="<?= DEFAULT_CURRENCY_SYMBOL ?>" />

    <div class="create-profile-form">
        <form id="jobForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" />
            <div class="row">

                <div class="col-12 mb-4">
                    <label><?= __('Job title') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Title" name="job[job_title]" required maxlength="100" value="<?= isset($job['job_title']) ? $job['job_title'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Job title') ?></small>

                    <input type="hidden" class="slug" name="job[job_slug]" value="<?= isset($job['job_slug']) ? $job['job_slug'] : '' ?>" />
                    <input type="hidden" name="job[job_userid]" value="<?= $this->userid ?>" />

                    <?php if (isset($job['job_id']) && intVal($job['job_id']) > 0) : ?>
                        <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>" />
                    <?php endif; ?>
                </div>

                <div class="col-12 mb-4">
                    <label><?= __('Short details') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter short details" name="job[job_short_detail]" required maxlength="150" value="<?= isset($job['job_short_detail']) ? $job['job_short_detail'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Short detail') ?></small>
                </div>

                <div class="col-4 mb-4">
                    <label><?= __('Estimated working hours') ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" required placeholder="Enter estimated working hours" name="job[job_estimated_hours]" min="0" max="9999" value="<?= isset($job['job_estimated_hours']) ? $job['job_estimated_hours'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Working hours') ?></small>
                </div>

                <div class="col-4 mb-4">
                    <label><?= __('Estimated working days') ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" required placeholder="Enter estimated working days" name="job[job_estimated_days]" min="0" max="9999" value="<?= isset($job['job_estimated_days']) ? $job['job_estimated_days'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Working days') ?></small>
                </div>

                <div class="col-4 mb-4">
                    <label><?= __('Estimated working weeks') ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" required placeholder="Enter estimated working weeks" name="job[job_estimated_weeks]" min="0" max="9999" value="<?= isset($job['job_estimated_weeks']) ? $job['job_estimated_weeks'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Working weeks') ?></small>
                </div>

                <div class="col-6 mb-4">
                    <label><?= __('Start date') ?> <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="startDate" required placeholder="Enter estimated working weeks" name="job[job_estimated_start_date]" value="<?= isset($job['job_estimated_start_date']) && isValidDate($job['job_estimated_start_date']) ? date('Y-m-d', strtotime($job['job_estimated_start_date'])) : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Start date') ?></small>
                </div>
                <div class="col-6 mb-4">
                    <label><?= __('End date') ?> <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="endDate" required placeholder="Enter estimated working weeks" name="job[job_estimated_end_date]" value="<?= isset($job['job_estimated_end_date']) && isValidDate($job['job_estimated_end_date']) ? date('Y-m-d', strtotime($job['job_estimated_weeks'])) : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'End date') ?></small>
                </div>

                <div class="col-12 mb-4" id="descriptionEditorDiv">
                    <label><?= __('Job description') ?> <span class="text-danger">*</span></label>
                    <textarea id="job_detail" class="form-control" name="job[job_detail]"><?= isset($job['job_detail']) ? $job['job_detail'] : '' ?></textarea>
                    <small class="invalid-feedback descriptionEditor"><?= sprintf(__('%s is a required field.'), 'Job description') ?></small>
                </div>

                <div class="col-md-6  mb-4">
                    <label><?= __('Job category') ?></label>
                    <!--<span class="text-danger">*</span>-->
                    <div class="slect-in">
                        <!--required-->
                        <select name="job[job_category][]" class="jobCategory form-select" multiple>
                            <?php
                            $fetched_job_category = array();
                            if (isset($job['job_category']) && $job['job_category'] != NULL && @unserialize($job['job_category']) !== FALSE) {
                                $fetched_job_category = unserialize($job['job_category']);
                            }
                            ?>
                            <!-- <option value="" hidden><? //= __('Choose Category') ?></option> -->
                            <?php foreach ($job_category as $key => $value) : ?>
                                <option value="<?= $value['job_category_id'] ?>" <?= (isset($job['job_category']) && in_array($value['job_category_id'], $fetched_job_category)) ? 'selected' : '' ?>><?= $value['job_category_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <!-- <i class="fa-solid fa-caret-down"></i> -->
                        <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Category') ?></small>
                    </div>
                </div>

                <!--<div class="col-md-6 mb-4">-->
                <!--    <label><?= __('Tags') ?> <span class="text-danger">*</span></label>-->
                <!--    <input type="text" class="form-control" id="job_tags" name="job[job_tags]" value="<?= isset($job['job_tags']) ? $job['job_tags'] : '' ?>" required />-->
                <!--    <small class="invalid-feedback"><?= sprintf(__('Add atleast one %s.'), 'tag') ?></small>-->
                <!--</div>-->

                <div class="col-md-6 mb-4">
                    <label><?= __('Job type') ?></label>
                    <!--<span class="text-danger">*</span>-->
                    <div class="slect-in">
                        <!--required-->
                        <select class="form-select" name="job[job_type]">
                            <option value="" hidden><?= __('Job Type') ?></option>
                            <!-- <option value="Freelance" <? //= (isset($job['job_type']) && $job['job_type'] == "Freelance") ? 'selected' : ''
                                                            ?>>Freelance</option> -->
                            <?php if (isset($job_type) && count($job_type) > 0) : ?>
                                <?php foreach ($job_type as $key => $value) : ?>
                                    <option value="<?= $value['job_type_name'] ?>" <?= (isset($job['job_type']) && $job['job_type'] == $value['job_type_name']) ? 'selected' : '' ?>><?= $value['job_type_name'] ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Job type') ?></small>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <label><?= __('Job level') ?></label>
                    <!--<span class="text-danger">*</span>-->
                    <div class="slect-in">
                        <!--required-->
                        <select class="form-select" name="job[job_level]">
                            <option value="" hidden><?= __('Job Level') ?></option>
                            <option value="Beginner" <?= (isset($job['job_level']) && $job['job_level'] == "Beginner") ? 'selected' : '' ?>>Beginner</option>
                            <option value="Intermediate" <?= (isset($job['job_level']) && $job['job_level'] == "Intermediate") ? 'selected' : '' ?>>Intermediate</option>
                            <option value="Advanced" <?= (isset($job['job_level']) && $job['job_level'] == "Advanced") ? 'selected' : '' ?>>Advanced</option>
                        </select>
                        <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Job level') ?></small>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <?php
                    $fetched_job_language = array();
                    if (isset($job['job_language']) && $job['job_language'] != NULL && @unserialize($job['job_language']) !== FALSE) {
                        $fetched_job_language = unserialize($job['job_language']);
                    }
                    ?>
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Language') ?></label>
                    <div class="slect-in">
                        <!--required-->
                        <select class="form-select jobLanguage" name="job[job_language][]" multiple>
                            <option value="" hidden><?= __('Choose Language') ?></option>
                            <?php foreach ($language as $key => $value) : ?>
                                <option value="<?= $value['language_code'] ?>" <?= ((isset($job['job_language']) && in_array($value['language_code'], $fetched_job_language)) ? 'selected' : '') ?>><?= $value['language_value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Language') ?></small>
                    </div>
                </div>

                <div class="col-md-12 mb-4">
                    <label><?= __('Job URL') ?></label>
                    <input type="url" class="form-control" placeholder="http://demo.com" name="job[job_url]" value="<?= isset($job['job_url']) ? $job['job_url'] : '' ?>" />
                </div>

                <div class="col-md-6 mb-4">
                    <label><?= __('Job contact email') ?> </label>
                    <input type="email" class="form-control" placeholder="Ex@myapplication.com" name="job[job_application_email]" value="<?= isset($job['job_application_email']) ? $job['job_application_email'] : '' ?>" />
                </div>
                <!--<div class="col-md-6 mb-4"> -->
                    <!--<span class="text-danger">*</span>-->
                    <!--<label><?//= __('Submission deadline') ?></label>-->
                    <!--required-->
                    <!-- 
                    <input type="date" class="form-control" id="jobApplicationDeadline" name="job[job_submission_deadline]" value="<?//= isset($job['job_submission_deadline']) ? date('Y-m-d', strtotime($job['job_submission_deadline'])) : '' ?>" />
                    <small class="invalid-feedback"><?//= __('A valid submission deadline date is required.') ?></small>
                </div> -->

                <div class="col-12 ">
                    <h5><?= __('Wages') ?> <span class="text-danger">*</span></h5>
                </div>

                <label class="d-none">
                    <input class="form-check" id="bugdet-check" type="checkbox" checked disabled value="<?= isset($job['job_salary_upper']) && $job['job_salary_upper'] ? 'hour' : 'fixed' ?>" />
                    &nbsp;<?= isset($job['job_salary_upper']) && $job['job_salary_upper'] ? 'Pay by the hour' : 'Pay a fixed price' ?>
                </label>
                <div class="col-md-6 mb-4">
                    <label><span id="main-budget"><?= __('Budget from') ?></span> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Enter Budget From" name="job[job_salary_lower]" min="1" max="99999" required value="<?= isset($job['job_salary_lower']) ? $job['job_salary_lower'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('A valid %s value is required.'), 'Budget lower') ?></small>
                </div>

                <div class="col-md-4 mb-4 hour-sec">
                    <label><?= __('Budget to') ?></label>
                    <input type="number" class="form-control" placeholder="Enter Budget To" name="job[job_salary_upper]" min="2" max="99999" value="<?= isset($job['job_salary_upper']) ? $job['job_salary_upper'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Budget upper') ?></small>
                </div>
                <div class="col-md-4 mt-4 hour-sec">
                    <label>/ hr</label>
                </div>

                <!--d-none-->
                <div class="col-md-6 mb-4">
                    <label><?= __('Salary interval') ?> <span class="text-danger">*</span></label>
                    <div class="slect-in">
                        <select class="form-select" name="job[job_salary_interval]" required>
                            <option value="" hidden><?= __('Select salary interval') ?></option>
                            <option value="hour" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "hour") ? 'selected' : 'selected' ?>>Hour</option>
                            <option value="day" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "day") ? 'selected' : '' ?>>Daily</option>
                            <option value="biweek" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "biweek") ? 'selected' : '' ?>>Biweekly</option>
                            <option value="week" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "week") ? 'selected' : '' ?>>Weekly</option>
                            <option value="month" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "month") ? 'selected' : '' ?>>Monthly</option>
                            <option value="year" <?= (isset($job['job_salary_interval']) && $job['job_salary_interval'] == "year") ? 'selected' : '' ?>>Yearly</option>
                        </select>
                        <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Salary postfix') ?></small>
                    </div>
                </div>

                <div class="col-12 ">
                    <h5><?= __('Job Location & Map') ?> <span class="text-danger">*</span></h5>
                </div>

                <div class="col-md-12 mb-4">
                    <label><?= __('Address') ?> <span class="text-danger location_asterisk">*</span></label>
                    <input type="text" class="form-control" id="job_location" placeholder="street city state, country" name="job[job_location]" required value="<?= isset($job['job_location']) ? $job['job_location'] : '' ?>" minlength="3" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Address') ?></small>
                </div>

                <div class="col-12">
                    <h5><?= __('Company Details') ?> <span class="text-danger">*</span></h5>
                </div>

                <div class="col-12" id="companyEditorDiv">
                    <label><?= __('Company description') ?> <span class="text-danger">*</span></label>
                    <textarea id="job_company_detail" class="form-control" name="job[job_company_detail]"><?= isset($job['job_company_detail']) ? $job['job_company_detail'] : '' ?></textarea>
                    <small class="invalid-feedback companyEditor"><?= sprintf(__('%s is a required field.'), 'Company description') ?></small>
                </div>

                <div class="col-12 mt-4">
                    <h5><?= __('Job Status') ?></h5>
                </div>

                <div class="col-md-6">
                    <label><?= __('Job status') ?></label>
                    <div class="slect-in">
                        <select class="form-select" name="job[job_status]">
                            <option value="<?= STATUS_ACTIVE ?>" <?= isset($job['job_status']) && $job['job_status'] == STATUS_ACTIVE  ? 'selected' : '' ?>><?= __('ACTIVE') ?></option>
                            <option value="<?= STATUS_INACTIVE ?>" <?= isset($job['job_status']) && $job['job_status'] == STATUS_INACTIVE ? 'selected' : '' ?>><?= __('INACTIVE') ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <label><?= __('Job expiry') ?></label>
                    <input type="date" name="job[job_expiry]" class="form-control" id="jobExpiry" value="<?= isset($job['job_expiry']) ? date("Y-m-d", strtotime($job['job_expiry'])) : '' ?>" required />
                    <small class="invalid-feedback"><?= sprintf(__('A valid %s date is required.'), 'Job expiry') ?></small>
                </div>

                <div class="col-12 mt-4">
                    <h5><?= __('Job Question') ?></h5>
                </div>

                <div class="col-md-12">
                    <div id="questionDiv">
                        <?php if (isset($job_question) && is_array($job_question) && !empty($job_question)) : ?>
                            <?php foreach ($job_question as $key => $value) : ?>
                                <input type="hidden" class="form-control" name="job_question[<?= $key ?>][job_question_id]" value="<?= $value['job_question_id'] ?>" />
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Question</label>
                                        <textarea class="form-control" type="text" name="job_question[<?= $key ?>][job_question_title]" maxlength="1000"><?= $value['job_question_title'] ?></textarea>
                                        <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Job question') ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Question</label>
                                    <textarea class="form-control" type="text" name="job_question[0][job_question_title]" maxlength="1000"></textarea>
                                    <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Job question') ?></small>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <a href="javascript:;" class="addQuestion"><i class="fa fa-plus-square"></i></a>
                </div>

                <div class="form-group my-4">
                    <label>
                        <?= __('Video attachment') ?>&nbsp;(<small><?= __(JOB_ATTACHMENT_SIZE_DESCIPTION) ?>):</small>
                        <span data-toggle="tooltip" data-bs-placement="top" title="A detailed video describing the job, ideally less than 3 minutes.">
                            <i class="fa fa-circle-question"></i>
                        </span>
                    </label>
                    <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop multiple videos (upload upto 3 videos)') ?>
                        <input type="file" name="job_attachment[]" multiple="multiple" class="form__file" id="upload-job-video" accept="video/*" />
                    </label>

                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>

                    <div class="videoDiv">
                        <?php if (isset($job['job_attachment']) && $job['job_attachment']) : ?>
                            <a data-fancybox href="<?= get_image($job['job_attachment_path'], $job['job_attachment']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($job['job_id']) && $job['job_id'] ? (int) $job['job_id'] : 0 ?>" data-param="job_attachment" data-toggle="tooltip" data-bs-placement="top" title="Delete this video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($job['job_attachment1']) && $job['job_attachment1']) : ?>
                            <a data-fancybox href="<?= get_image($job['job_attachment_path'], $job['job_attachment1']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($job['job_id']) && $job['job_id'] ? (int) $job['job_id'] : 0 ?>" data-param="job_attachment1" data-toggle="tooltip" data-bs-placement="top" title="Delete this video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (isset($job['job_attachment2']) && $job['job_attachment2']) : ?>
                            <a data-fancybox href="<?= get_image($job['job_attachment_path'], $job['job_attachment2']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($job['job_id']) && $job['job_id'] ? (int) $job['job_id'] : 0 ?>" data-param="job_attachment2" data-toggle="tooltip" data-bs-placement="top" title="Delete this video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <?php if (g('db.admin.enable_job_listing_subscription') && ((!isset($job['job_id'])) || (isset($job['job_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($job['job_subscription_expiry'])))) : ?>
                    <div class="col-md-6">
                        <div class="form-group my-4">
                            <label>Select job post duration <span class="text-danger">*</span> <span data-toggle="tooltip" title="The number of duration the job post will be active."><i class="fa fa-circle-question"></i></span></label>
                            <select class="form-select" name="job[job_subscription_interval]" required>
                                <?php for($i = 1; $i <= 28; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i; ?></option>
                                <?php endfor; ?>
                            </select>
                            <label><small class="text-danger">Note: You will be charged <span class="subcsriptionPrice"><?= price(g('db.admin.job_listing_subscription_fee')) ?></span> per job post per <span class="subscriptionIntervalType"></span></small></label>
                            <!--<label><small class="text-danger">Note: You will be charged <?= price(g('db.admin.job_listing_subscription_fee')) ?> per job post per <?= SUBSCRIPTION_JOB_INTERVAL_TYPE ?></small></label>-->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group my-4">
                            <label>Select job post duration type <span class="text-danger">*</span></label>
                            <select class="form-select" name="job[job_subscription_interval_type]" required>
                                <option value="day">Day</option>
                                <option value="week">Week</option>
                                <option value="month">Month</option>
                            </select>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12 mt-4">
                    <button class="btn btn-custom previewBtn" type="button"><?= __('Preview') ?></button>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="post-job-popup">
    <a class="float-right" href="javascript:;" onclick="closePopup()">
        <i class="fa fa-close"></i>
    </a>
    <div>
        <h4><span class="job_title"><?= isset($job['job_title']) ? $job['job_title'] : __('Not Set') ?></span></h4>
        <div class="tag-ts"><i class="fa-solid fa-circle-check"></i> <span class="job_type"><?= isset($job['job_type']) ? $job['job_type'] : __('Not Set') ?></span></div>
        <div class="badges">
            <span><i class="fa-light fa-briefcase"></i>
                <span class="job_category">
                    <?php if (isset($job['job_category']) && $job['job_category'] != NULL && @unserialize($job['job_category']) !== FALSE) : ?>
                        <?php foreach (unserialize($job['job_category']) as $ke => $val) : ?>
                            <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        ...
                    <?php endif; ?>
                </span>
            </span>
            <span><i class="fa-light fa-location-dot"></i> <span class="job_location m-0"><?= isset($job['job_location']) ? $job['job_location'] : __('Not Set') ?></span></span>
            <span><i class="fa-light fa-circle-dollar-to-slot"></i> <span class="job_salary_lower m-0"><?= isset($job['job_salary_lower']) ? price($job['job_salary_lower']) : __('Not Set') ?></span><span class="job_salary_upper m-0"><?= isset($job['job_salary_upper']) && $job['job_salary_upper'] ? ' - ' . price($job['job_salary_upper']) : '' ?></span><span class="job_salary_interval m-0"><?= (isset($job['job_salary_upper']) && $job['job_salary_upper'] ? ' / ' . $job['job_salary_interval'] : '') ?></span> </span>
        </div>
        <!-- specify -->
        <!--<div class="d-flex job_tags">-->
        <!--    <div class="">-->
        <!--        <?php $job_tags = isset($job['job_tags']) ? explode(',', $job['job_tags']) : array(); ?>-->
        <!--        <?php if (count($job_tags) > 0) : ?>-->
        <!--            <?php foreach ($job_tags as $ke1 => $val1) : ?>-->
        <!--                <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>-->
        <!--            <?php endforeach; ?>-->
        <!--        <?php else : ?>-->
        <!--            <small><?= __('Not Set') ?></small>-->
        <!--        <?php endif; ?>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
    <p class="job_short_detail"><?= isset($job['job_short_detail']) ? $job['job_short_detail'] : __('Not Set') ?></p>

    <?php if (g('db.admin.enable_job_listing_subscription') && ((!isset($job['job_id'])) || (isset($job['job_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($job['job_subscription_expiry'])))) : ?>
    <?php //if (g('db.admin.enable_job_listing_subscription') && (!isset($job['job_id']))) : ?>
        <div class="form-row card-group" id="card-group">
            <label for="card-element">
                Credit or debit card
            </label>
            <div id="card-element" class="form-control card-elements">
                <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display Element errors. -->
            <small id="card-errors" class="text-danger" role="alert"></small>
        </div>
        <hr />
        <button class="btn btn-custom previewSubmit" data-html=""><?= __('Submit') ?><?= ' ' . 'and Pay: ' . '<span class="subcsriptionPrice"></span>'; ?></button>
        <p>The job post will be listed for <span class="subscriptionIntervalText">1</span> <span class="subscriptionIntervalType"></span>(s)</p>
        <!--<p>The job post will be listed for <span class="subscriptionIntervalText">1</span> <?= SUBSCRIPTION_JOB_INTERVAL_TYPE ?>(s)</p>-->
    <?php else: ?>
        <button class="btn btn-custom previewSubmit" data-html=""><?= __('Submit') ?></button>
    <?php endif; ?>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>
    
    function mount_stripe(card) {
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
    }

    function unmount_stripe(card) {
        card.unmount();
    }

    // open popup
    function openPopup() {
        $('.post-job-popup').show();
    }

    // close popup
    function closePopup() {
        $('.post-job-popup').hide();
    }

    function generateSlug(Text) {
        return Text.toLowerCase()
            .replace(/ /g, '-')
            .replace(/[^\w-]+/g, '');
    }

    const stripeTokenHandler = (token, formId) => {
        // Insert the token ID into the form so it gets submitted to the server
        const form = document.getElementById(formId);
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }

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
    // card.mount('#card-element');
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    async function saveJob() {
        $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
        var data = new FormData(document.getElementById('jobForm'))
        var url = "<?php echo l('dashboard/job/save'); ?>";
        return new Promise((resolve, reject) => {
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
                beforeSend: function() {
                    $('.previewSubmit').attr('disabled', true)
                    $('.previewSubmit').html('Submitting ...')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    AdminToastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                },
                complete: function() {
                    $('.previewSubmit').attr('disabled', false)
                    $('.previewSubmit').html($('.previewSubmit').data('html'))
                }
            });
        })
    }

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/job/deleteAttachment'
        //
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
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function(jqXHR, textStatus) {
                    hideLoader()
                },
                beforeSend: function() {
                    showLoader()
                }
            });
        })
    }

    function submitJobPaymentForm(event, descriptionEditor, companyEditor) {
        var size_error = false;
        var number_videos_error = false;

        if (!$('#jobForm')[0].checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            $('#jobForm').addClass('was-validated');
            $('#jobForm').find(":invalid").first().focus();
            if (descriptionEditor.getData() == '<p>&nbsp;</p>') {
                $('.descriptionEditor.invalid-feedback').show()
                var descriptionEditorDiv = document.getElementById("descriptionEditorDiv"); 
                descriptionEditorDiv.focus(); 
                descriptionEditorDiv.scrollIntoView(); 
            } else {
                $('.descriptionEditor.invalid-feedback').hide()
            }
            if (companyEditor.getData() == '<p>&nbsp;</p>') {
                $('.companyEditor.invalid-feedback').show()
                var companyEditorDiv = document.getElementById("companyEditorDiv"); 
                companyEditorDiv.focus(); 
                companyEditorDiv.scrollIntoView(); 
            } else {
                $('.companyEditor.invalid-feedback').hide()
            }
            closePopup()
            return false;
        } else {
            if (descriptionEditor.getData() == '<p>&nbsp;</p>' || companyEditor.getData() == '<p>&nbsp;</p>') {
                if (descriptionEditor.getData() == '<p>&nbsp;</p>') {
                    $('.descriptionEditor.invalid-feedback').show()
                    var descriptionEditorDiv = document.getElementById("descriptionEditorDiv"); 
                    descriptionEditorDiv.focus(); 
                    descriptionEditorDiv.scrollIntoView(); 
                } else {
                    $('.descriptionEditor.invalid-feedback').hide()
                }
                if (companyEditor.getData() == '<p>&nbsp;</p>') {
                    $('.companyEditor.invalid-feedback').show()
                    var companyEditorDiv = document.getElementById("companyEditorDiv"); 
                    companyEditorDiv.focus(); 
                    companyEditorDiv.scrollIntoView(); 
                } else {
                    $('.companyEditor.invalid-feedback').hide()
                }
                return false;
            }
            $('#jobForm').removeClass('was-validated');
        }

        $('#upload-job-video').each(function(index, ele) {
            if(ele.files.length > 3) {
                number_videos_error = true;
            }
            for (var i = 0; i < ele.files.length; i++) {
                const file = ele.files[i];
                if (file.size > 10000000) {
                    size_error = true;
                }
            }
        })

        if (!size_error && !number_videos_error) {
            saveJob().then(
                function (response) {
                    if (response.status == 0) {
                        $.dialog({
                            backgroundDismiss: true,
                            title: '<?= __("Error") ?>',
                            content: response.txt,
                        });
                        return false;
                    } else if (response.status == 1) {
                        $('.previewSubmit').attr('disabled', true)
                        AdminToastr.success(response.txt, 'Success');
                        if (response.slug) {
                            if (response.type == 'insert') {
                                window.location = '<?= l('dashboard/job/detail/') ?>' + response.slug;
                            } else {
                                closePopup()
                                Loader.show();
                                setTimeout(function(){
                                    location.reload()
                                }, 1000)
                            }
                        } else {
                            setTimeout(function(){
                                location.reload()
                            }, 1000)
                        }
                    }
                }
            )
        } else {
            if (number_videos_error) {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= __("The upload limit reached, a maximum of 3 videos per job post are allowed!") ?>',
                });
            } else if (size_error) {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                });
            } else {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= __("An unkown error occurred, please try contacting the administrator.") ?>',
                });
            }
        }
    }

    function calculateJobSubscriptionFee(currency, fee, interval, interval_type) {
        var calculatedFee = fee;
        switch(interval_type) {
            case 'day':
                calculatedFee = (fee) * interval;
                break;
            case 'week':
                calculatedFee = (fee * 7) * interval;
                break;
            case 'month':
                calculatedFee = (fee * 28) * interval;
                break;
        }
        return (currency + ' ' + parseFloat(calculatedFee).toFixed(2));
    }

    var formId = 'jobForm'
    var form = document.getElementById(formId);

    $(document).ready(function() {

        //
        var currency = $('input[name=currency]').val();
        // for shwoing loading text only
        $('.previewSubmit').attr('data-html', $('.previewSubmit').html())

        // var job_listing_subscription_fee = currency + ($('select[name="job[job_subscription_interval]"]').val() * $('input[name=job_listing_subscription_fee]').val()).toFixed(2)
        var job_listing_subscription_fee = calculateJobSubscriptionFee(currency, $('input[name=job_listing_subscription_fee]').val(), $('select[name="job[job_subscription_interval]"]').val(), $('select[name="job[job_subscription_interval_type]"]').val())

        $('.subcsriptionPrice').html(job_listing_subscription_fee)
        $('.subscriptionIntervalText').html($('select[name="job[job_subscription_interval]"]').val())
        $('.subscriptionIntervalType').html($('select[name="job[job_subscription_interval_type]"]').val())

        //
        $('select[name="job[job_subscription_interval]"]').on('change', function(){
            var job_listing_subscription_fee = calculateJobSubscriptionFee(currency, $('input[name=job_listing_subscription_fee]').val(), $('select[name="job[job_subscription_interval]"]').val(), $('select[name="job[job_subscription_interval_type]"]').val())
            $('.subcsriptionPrice').html(job_listing_subscription_fee)
            $('.subscriptionIntervalText').html($(this).val())
            $('.previewSubmit').attr('data-html', $('.previewSubmit').html())
        })

        //
        $('select[name="job[job_subscription_interval_type]"]').on('change', function(){
            var job_listing_subscription_fee = calculateJobSubscriptionFee(currency, $('input[name=job_listing_subscription_fee]').val(), $('select[name="job[job_subscription_interval]"]').val(), $('select[name="job[job_subscription_interval_type]"]').val())
            $('.subcsriptionPrice').html(job_listing_subscription_fee)
            $('.subscriptionIntervalType').html($(this).val())
            $('.previewSubmit').attr('data-html', $('.previewSubmit').html())
        })

        //
        if ($('#bugdet-check').is(':checked')) {
            $('#main-budget').html('Salary')
            $('input[name="job[job_salary_lower]"]').attr('placeholder', 'Enter job salary')
            $('input[name="job[job_salary_upper]"]').attr('disabled', true)
            if (!$('.hour-sec').hasClass('d-none')) {
                $('.hour-sec').addClass('d-none')
            }
        } else {
            $('#main-budget').html('Budget from')
            $('input[name="job[job_salary_lower]"]').attr('placeholder', 'Enter salary from')
            $('input[name="job[job_salary_upper]"]').attr('disabled', false)
            $('.hour-sec').removeClass('d-none')
        }
        //
        $('#bugdet-check').on('change', function() {
            if ($(this).is(':checked')) {
                $('#main-budget').html('Salary')
                $('input[name="job[job_salary_lower]"]').attr('placeholder', 'Enter job salary')
                $('input[name="job[job_salary_upper]"]').attr('disabled', true)
                if (!$('.hour-sec').hasClass('d-none')) {
                    $('.hour-sec').addClass('d-none')
                }
            } else {
                $('#main-budget').html('Budget from')
                $('input[name="job[job_salary_lower]"]').attr('placeholder', 'Enter salary from')
                $('input[name="job[job_salary_upper]"]').attr('disabled', false)
                $('.hour-sec').removeClass('d-none')
            }
        })

        //
        var descriptionEditor;
        var companyEditor;

        // make select2
        $('.jobCategory').select2({
            maximumSelectionSize: 10,
            // tags: true
        });

        $('.jobLanguage').select2({
            maximumSelectionSize: 10,
            allowClear: true
        });

        // initiate Ckeditor
        // ckeditor for job detail
        ClassicEditor
            .create(document.querySelector('#job_detail'))
            .then(editor => {
                descriptionEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        // ckeditor for company details
        ClassicEditor
            .create(document.querySelector('#job_company_detail'))
            .then(editor => {
                companyEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        //
        startDate.min = new Date(Date.now() + (3600 * 1000)).toISOString().split("T")[0];
        if (startDate.value == "") {
            startDate.value = new Date(Date.now() + (3600 * 1000)).toISOString().split("T")[0]
        }
        //
        endDate.min = new Date(Date.parse($('#startDate').val()) + (3600 * 1000 * 24)).toISOString().split("T")[0]
        $('#startDate').on('change', function() {
            endDate.min = new Date(Date.parse($('#startDate').val()) + (3600 * 1000 * 24)).toISOString().split("T")[0]
        })

        // set min date to today
        // jobApplicationDeadline.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];
        // if (jobApplicationDeadline.value == "") {
        //     jobApplicationDeadline.value = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0]
        // }

        jobExpiry.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0]
        if (jobExpiry.value == "") {
            jobExpiry.value = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0]
        }

        // preview after validation
        $(".previewBtn").on('click', function(event) {
            if (!$('#jobForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#jobForm').addClass('was-validated');
                $('#jobForm').find(":invalid").first().focus();
                if (descriptionEditor.getData() == '<p>&nbsp;</p>') {
                    $('.descriptionEditor.invalid-feedback').show()
                    var descriptionEditorDiv = document.getElementById("descriptionEditorDiv"); 
                    descriptionEditorDiv.focus(); 
                    descriptionEditorDiv.scrollIntoView(); 
                } else {
                    $('.descriptionEditor.invalid-feedback').hide()
                }
                if (companyEditor.getData() == '<p>&nbsp;</p>') {
                    $('.companyEditor.invalid-feedback').show()
                    var companyEditorDiv = document.getElementById("companyEditorDiv"); 
                    companyEditorDiv.focus(); 
                    companyEditorDiv.scrollIntoView(); 
                } else {
                    $('.companyEditor.invalid-feedback').hide()
                }
                return false;
            } else {
                if (descriptionEditor.getData() == '<p>&nbsp;</p>' || companyEditor.getData() == '<p>&nbsp;</p>') {
                    if (descriptionEditor.getData() == '<p>&nbsp;</p>') {
                        $('.descriptionEditor.invalid-feedback').show()
                        var descriptionEditorDiv = document.getElementById("descriptionEditorDiv"); 
                        descriptionEditorDiv.focus(); 
                        descriptionEditorDiv.scrollIntoView(); 
                    } else {
                        $('.descriptionEditor.invalid-feedback').hide()
                    }
                    if (companyEditor.getData() == '<p>&nbsp;</p>') {
                        $('.companyEditor.invalid-feedback').show()
                        var companyEditorDiv = document.getElementById("companyEditorDiv"); 
                        companyEditorDiv.focus(); 
                        companyEditorDiv.scrollIntoView(); 
                    } else {
                        $('.companyEditor.invalid-feedback').hide()
                    }
                    return false;
                }
                $('#jobForm').removeClass('was-validated');
                openPopup()
                if ($('input[name=enable_job_listing_subscription]').val() && (!$('input[name=job_id]').length || $('input[name=job_subscription_expired]').val())) {
                    mount_stripe(card)
                }
            }
        })

        // submit after preview is done
        $('body').on('click', '.previewSubmit', function(){
            $('#' + formId).submit()
        })

        // submit after preview
        $('#' + formId).on('submit', function(event) {
            event.preventDefault();
            if ($('input[name=enable_job_listing_subscription]').val() && (!$('input[name=job_id]').length || $('input[name=job_subscription_expired]').val())) {
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        stripeTokenHandler(result.token, formId);
                        submitJobPaymentForm(event, descriptionEditor, companyEditor)
                    }
                });
            } else {
                submitJobPaymentForm(event, descriptionEditor, companyEditor)
            }
        })

        $('input[name="job[job_title]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(generateSlug($(this).val()))
        })

        // location autocomplete
        $(function() {
            $("#job_location").autocomplete({
                source: function(request, response) {
                    $.getJSON(base_url + 'job/mapbox', {
                            _token: '<?= $this->csrf_token ?>',
                            term: request.term
                        },
                        response);
                },
                select: function(event, ui) {
                    event.preventDefault();
                    $("#job_location").val(ui.item.id);
                }
            });
        });


        if($('select[name="job[job_type]"]').val() == 'Remote') {
            $('#job_location').attr('required', false)
            $('.location_asterisk').html('')
        } else {
            $('#job_location').attr('required', true)
            $('.location_asterisk').html('*')
        }
        $('select[name="job[job_type]"]').on('change keyup' , function(){
            if($('select[name="job[job_type]"]').val() == 'Remote') {
                $('#job_location').attr('required', false)
                $('.location_asterisk').html('')
            } else {
                $('#job_location').attr('required', true)
                $('.location_asterisk').html('*')
            }
        })

        // jquery tags input
        // var tagInputEle = $('#job_tags');
        // tagInputEle.tagsinput({
        //     maxTags: 3,
        // });

        const dt = new DataTransfer();

        $('#upload-job-video').on('change', function() {
            // $("#videoList > #video-names").html('')
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
                if (this.files.item(i).size < 10000000) {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }

                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };

            // dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                // let name = $(this).parent().next('span.name').text();
                let name = $(this).parent().find('span.name').html()
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        $(this).parent().remove();
                        dt.items.remove(i);
                        continue;
                    }
                }
                const input = document.getElementById('upload-job-video')
                input.files = dt.files;
            });
        })

        // SET POPUP TEXT VALUES //
        $('input[name="job[job_title]"]').on('change keyup keydown keyup keypress', function() {
            $('.job_title').html($(this).val())
        })
        $('select[name="job[job_type]"]').on('change keyup keydown keyup keypress', function() {
            $('.job_type').html($(this).val())
        })
        $('.jobCategory').on("change keyup keydown keyup keypress", function(e) {
            // $('.job_category').html($(this).select2('data'))
            $('.job_category').html("");
            for (var i = 0; i < $(this).select2('data').length; i++) {
                if (i + 1 == $(this).select2('data').length) {
                    $('.job_category').append($(this).select2('data')[i]['text'])
                } else {
                    $('.job_category').append($(this).select2('data')[i]['text'] + ', ')
                }
            }
        })
        $('input[name="job[job_location]"]').on('input change keyup keydown keyup keypress', function() {
            $('.job_location').html($(this).val())
        })
        $('input[name="job[job_salary_upper]"]').on('change keyup keydown keyup keypress', function() {
            if ($(this).val() != '') {
                $('.job_salary_upper').html(' - $' + $(this).val() + ' / hr')
            }
        })
        $('input[name="job[job_salary_lower]"]').on('change keyup keydown keyup keypress', function() {
            $('input[name="job[job_salary_upper]"]').attr('min', $(this).val())
            $('.job_salary_lower').html('$' + $(this).val())
        })
        $('select[name="job[job_salary_interval]"]').on('change keyup keydown keyup keypress', function() {
            $('.job_salary_interval').html(' ' + '/' + ' ' + $(this).val())
        })
        $('input[name="job[job_short_detail]"]').on('change keyup keydown keyup keypress', function() {
            $('.job_short_detail').html($(this).val())
        })
        // $('input[name="job[job_tags]"]').on('change keyup keydown keyup keypress', function() {
        //     var tags = $(this).val()
        //     var tagsArray = tags.split(",");
        //     $('.job_tags').html("");
        //     for (var i = 0; i < tagsArray.length; i++) {
        //         if (i % 2 == 0) {
        //             $('.job_tags').append('<div class="specify yll">' + tagsArray[i] + '</div>')
        //         } else {
        //             $('.job_tags').append('<div class="specify">' + tagsArray[i] + '</div>')
        //         }
        //     }
        // })

        //
        var question_counter = '<?= isset($job_question) ? count($job_question) : 0 ?>';
        $('.addQuestion').click(function() {
            if ($('#questionDiv div.row').length < 5) {
                html = '<div class="row">' +
                    '<a href="javascript:;" class="removeQuestion"><i class="fa fa-minus-square"></i></a>' +
                    '<div class="col-md-12">' +
                    '<label>Question</label>' +
                    '<textarea class="form-control" type="text" name="job_question[' + question_counter + '][job_question_title]"></textarea>' +
                    '<small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Job question') ?></small>' +
                    '</div>' +
                    '</div>';
                $('#questionDiv').append(html)
                question_counter++;
                if ($('#questionDiv div.row').length == 5) {
                    $('.addQuestion').hide()
                }
            } else {
                $('.addQuestion').hide()
            }
        })

        //
        $('body').on('click', '.removeQuestion', function() {
            if ($('#questionDiv div.row').length <= 5) {
                $('.addQuestion').show()
            }
            $(this).parent().remove()
        })

        $('body').on('click', '.video-del-btn', function() {
            swal({
                title: "<?= __('Warning') ?>",
                text: 'Delete this video',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'id': $(this).data('id'),
                        'param': $(this).data('param')
                    }
                    deleteAttachment(data).then(
                        function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                                $('.videoDiv').remove();
                            } else {
                                swal("Error", response.txt, "error");
                            }
                        }
                    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })
    });
</script>