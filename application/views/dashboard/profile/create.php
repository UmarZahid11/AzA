<style>
    .removeCredential {
        position: relative;
        right: 20px;
        top: 50px;
    }
    .iti {
        width: 100%;
    }
</style>

<div class="dashboard-content">
    <i class="fa-regular fa-user"></i>
    <h4><?= __('Create Profile') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . PROFILE_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Profile Tutorial</a>
    <hr />

    <div class="create-profile-form">
        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <form action="javascript:void(0)" method="post" id="form-profile-image">
                                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                                <input type="file" name="file" id="imageUpload" accept="image/*" />
                                <label for="imageUpload"></label>
                            </form>
                            <button class="trash_img trash_profile_img" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Delete profile picture.') ?>"><i class="fa fa-trash"></i></button>
                        </div>
        
                        <input type="hidden" name="signup_logo_image" value="<?= (isset($this->user_data['signup_logo_image']) && $this->user_data['signup_logo_image'] != "") ? $this->user_data['signup_logo_image'] : '' ?>" />
                        
                        <div class="avatar-preview">
                            <?php if (isset($this->user_data['signup_logo_image']) && $this->user_data['signup_logo_image'] != "") : ?>
                                <div id="imagePreview" style="background-image: url(<?= get_image($this->user_data['signup_logo_image_path'], $this->user_data['signup_logo_image']) ?>);">
                                </div>
                            <?php else : ?>
                                <div id="imagePreview" style="background-image: url(<?= g('dashboard_images_root') ?>upload-img.jpg);">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <small class="text-danger font-11">Note: <?= str_replace('{height}', '300', str_replace('{width}', '300', UPLOAD_GUIDELINES_PROFILE_IMAGE)) ?></small>
                </div>
                <div class="col-md-6">
                    <form method="POST" action="javascript:;" id="signupVideoForm">
                        <label class="form__container" id="upload-container"><?= __('Drop or click to upload profile video') ?>
                            <input type="file" name="signup_video" class="form__file" id="upload-signup-video" accept="video/*" />
                        </label>
                        <p id="files-area">
                            <span id="videoList">
                                <span id="video-names"></span>
                            </span>
                        </p>
                        <button type="submit" class="btn btn-custom" id="signupVideoFormBtn">Save</button>
                        <hr />
                        <div class="videoDiv">
                            <?php if (isset($this->user_data['signup_video']) && $this->user_data['signup_video']) : ?>

                                <a data-fancybox href="<?= get_image($this->user_data['signup_logo_image_path'], $this->user_data['signup_video']) ?>">
                                    <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="100" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                                </a>
                                <a class="video-del-btn" style="bottom: 15px; color: #fff !important;" href="javascript:;" data-id="<?= isset($this->userid) && $this->userid ? (int) $this->userid : 0 ?>" data-param="signup_video" data-toggle="tooltip" data-bs-placement="top" title="Delete signup video">
                                    <i class="fa fa-close" aria-hidden="true"></i>
                                </a>

                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <span class="dropdown float-right" data-toggle="tooltip" data-bs-placement="right" title="Set profile privacy.">
            <input type="hidden" name="signup_privacy" value="<?= $this->user_data['signup_privacy'] ?>" />
            <button><span id="privacyType"><?= ucfirst($this->user_data['signup_privacy']) ?></span></button>
            <label>
                <input type="checkbox" />
                <ul>
                    <li><a href="javascript:;" class="changePrivacy" data-value="<?= SIGNUP_PRIVACY_PUBLIC ?>"><i class="fa fa-globe"></i> Public</a></li>
                    <li><a href="javascript:;" class="changePrivacy" data-value="<?= SIGNUP_PRIVACY_PRIVATE ?>"><i class="fa fa-user-lock"></i> Private</a.< /li>
                    <li><a href="javascript:;" class="changePrivacy" data-value="<?= SIGNUP_PRIVACY_FOLLOWER ?>"><i class="fa fa-users"></i> Follower</a></li>
                </ul>
            </label>
        </span>

        <form class="profileForm" id="profileForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Personal Information') ?></h5>
                </div>
                <div class="col-md-6">
                    <label><?= __('First name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter First Name" name="signup[signup_firstname]" required value="<?= isset($this->user_data['signup_firstname']) ? $this->user_data['signup_firstname'] : '' ?>" maxlength="500" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Last name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Last Name" name="signup[signup_lastname]" required value="<?= isset($this->user_data['signup_lastname']) ? $this->user_data['signup_lastname'] : '' ?>" maxlength="500" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Email') ?> <span class="text-danger">*</span></label>
                    <!--<input type="email" class="form-control" placeholder="info@domain.com" readonly name="signup[signup_email]" required value="<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : '' ?>" maxlength="500" />-->
                    <p><b><?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : '' ?></b></p>
                </div>
                <!--<div class="col-md-6">-->
                <!--    <label><?= __('Paypal Email') ?> <span class="text-danger">(Fill in this field to receive payments through Paypal) *</span></label>-->
                <!--    <input type="email" class="form-control" placeholder="info@domain.com" name="signup[signup_paypal_email]" required value="<?= isset($this->user_data['signup_paypal_email']) ? $this->user_data['signup_paypal_email'] : '' ?>" maxlength="500" />-->
                <!--</div>-->
                <div class="col-md-6">
                    <label><?= __('Phone') ?> <span class="text-danger">*</span></label> <br />
                    <input type="text" class="phone form-control" name="signup[signup_phone]" id="phone" required value="<?= isset($this->user_data['signup_phone']) ? $this->user_data['signup_phone'] : '' ?>" <?= (isset($this->user_data['signup_is_phone_confirmed']) && $this->user_data['signup_phone'] && $this->user_data['signup_is_phone_confirmed']) ? 'readonly' : '' ?> maxlength="20" />
                    <!-- pattern="[0-9]{3}[-. ][0-9]{3}[-. ][0-9]{4}" -->
                </div>
                <div class="col-md-6">
                    <label><?= __('Date of birth') ?> <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="birthdayPickerId" name="signup[signup_birthday]" required value="<?= isset($this->user_data['signup_birthday']) ? date('Y-m-d', strtotime($this->user_data['signup_birthday'])) : '' ?>" />
                </div>

                <div class="col-md-6">
                    <label><?= __('Gender') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="signup[signup_gender]" required>
                        <option value="">Select gender</option>
                        <option value="male" <?= $this->user_data['signup_gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= $this->user_data['signup_gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= $this->user_data['signup_gender'] == 'other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <?php if (isset($language)) : ?>
                    <?php
                    $fetched_signup_language = array();
                    if (isset($this->user_data['signup_language']) && $this->user_data['signup_language'] != NULL && @unserialize($this->user_data['signup_language']) !== FALSE) {
                        $fetched_signup_language = unserialize($this->user_data['signup_language']);
                    }
                    ?>
                    <div class="col-md-6">
                        <label><?= __('Language') ?></label>
                        <select class="form-select languageSelect" name="signup[signup_language][]" multiple>
                            <option disabled><?= __('Select Language Proficiency') ?></option>
                            <?php foreach ($language as $key => $value) : ?>
                                <option value="<?= $value['language_value'] ?>" <?= (isset($this->user_data['signup_language']) && in_array($value['language_value'], $fetched_signup_language)) ? 'selected' : '' ?>><?= $value['language_value'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="col-md-6">
                    <label><?= __('Current company') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="company" placeholder="" name="signup[signup_company]" required value="<?= isset($this->user_data['signup_company']) ? $this->user_data['signup_company'] : '' ?>" maxlength="1000" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Profession') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Profession" name="signup[signup_profession]" required value="<?= isset($this->user_data['signup_profession']) ? $this->user_data['signup_profession'] : '' ?>" maxlength="150" />
                </div>

                <div class="col-md-6 mb-4">
                    <label><?= __('Skills') ?></label>
                    <input type="text" class="form-control" id="signup_skill" name="signup[signup_skill]" value="<?= isset($this->user_data['signup_skill']) ? $this->user_data['signup_skill'] : '' ?>" />
                </div>

                <div class="col-md-6 mb-4">
                    <label><?= __('Recognitions (Awards & Honors)') ?></label>
                    <input type="text" class="form-control" id="signup_recognition" name="signup[signup_recognition]" value="<?= isset($this->user_data['signup_recognition']) ? $this->user_data['signup_recognition'] : '' ?>" />
                </div>

                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <div class="col-12">
                        <label><?= __('About info') ?> <span class="text-danger">*</span></label>
                        <div>
                            <textarea class="ckeditor form-control" id="editor" name="signup[signup_about_me]" contenteditable="true"><?= isset($this->user_data['signup_about_me']) ? $this->user_data['signup_about_me'] : '' ?></textarea>
                            <small class="invalid-feedback aboutEditor"><?= sprintf(__('%s is a required field.'), 'About info') ?></small>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <div class="col-12 mt-4">
                        <h5><?= __('Address') ?></h5>
                    </div>
                    <div class="col-md-12">
                        <label><?= __('Location') ?> <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="address" placeholder="Enter valid address" autocomplete="off" name="signup[signup_address]" required value="<?= isset($this->user_data['signup_address']) ? $this->user_data['signup_address'] : '' ?>" maxlength="1000" />
                        <small>
                            <?php if (isset($this->user_data['signup_is_address_verified']) && $this->user_data['signup_is_address_verified']) : ?>
                                <a class="text-success text-decoration-underline" id="validate-address" href="javascript:;">
                                    <i class="fa fa-check-circle"></i> validated.
                                </a>
                            <?php else : ?>
                                <a class="text-danger text-decoration-underline" id="validate-address" href="javascript:;">
                                    <i class="fa fa-clock-o"></i> validate.
                                </a>
                            <?php endif; ?>
                        </small>
                    </div>

                    <input type="hidden" name="signup[signup_is_address_verified]" id="signup_is_address_verified" value="<?= isset($this->user_data['signup_is_address_verified']) ? $this->user_data['signup_is_address_verified'] : 0 ?>" />
                <?php endif; ?>

                <div class="col-12 mt-4">
                    <h5><?= __('Social accounts') ?></h5>
                </div>
                <div class="col-md-6">
                    <label><?= __('Facebook') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_facebook]" value="<?= isset($this->user_data['signup_facebook']) ? $this->user_data['signup_facebook'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Twitter') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_twitter]" value="<?= isset($this->user_data['signup_twitter']) ? $this->user_data['signup_twitter'] : '' ?>" />
                </div>
                <!-- <div class="col-md-6">
                    <label>Google Plus </label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_google_plus]" value="<?= isset($this->user_data['signup_google_plus']) ? $this->user_data['signup_google_plus'] : '' ?>" />
                </div> -->
                <div class="col-md-6">
                    <label><?= __('Youtube') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_youtube]" value="<?= isset($this->user_data['signup_youtube']) ? $this->user_data['signup_youtube'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Vimeo') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_vimeo]" value="<?= isset($this->user_data['signup_vimeo']) ? $this->user_data['signup_vimeo'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Linkedin') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_linkedin]" value="<?= isset($this->user_data['signup_linkedin']) ? $this->user_data['signup_linkedin'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Github') ?></label>
                    <input type="url" class="form-control" placeholder="" name="signup[signup_github]" value="<?= isset($this->user_data['signup_github']) ? $this->user_data['signup_github'] : '' ?>" />
                </div>

                <div class="col-12 mt-4">
                    <h5><?= __('Work info & preferences') ?></h5>
                </div>

                <div class="col-md-6">
                    <!--<label><?= __('Biotech/Pharmaceutical/Life science work') ?> <span class="text-danger">*</span></label>-->
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Industry') ?> </label>
                    <!--required-->
                    <select class="form-select" id="sciencework" name="signup[signup_sciencework]">
                        <?php if (isset($job_category) && count($job_category) > 0) : ?>
                            <option value="">Select Industry</option>
                            <?php foreach ($job_category as $key => $value) : ?>
                                <option value="<?= $value['job_category_name'] ?>" <?= isset($this->user_data['signup_sciencework']) && $this->user_data['signup_sciencework'] == $value['job_category_name'] ? 'selected' : '' ?>><?= $value['job_category_name'] ?></option>
                            <?php endforeach; ?>
                            <option value="other" <?= (isset($this->user_data['signup_sciencework']) && $this->user_data['signup_sciencework'] != "" && !in_array($this->user_data['signup_sciencework'], $job_category_array)) ? 'selected' : '' ?>>Other</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Work type') ?> </label>
                    <!--required-->
                    <select class="form-select" name="signup[signup_worktype]">
                        <option value="">Select work type</option>
                        <?php if (isset($job_type) && count($job_type) > 0) : ?>
                            <?php foreach ($job_type as $key => $value) : ?>
                                <option value="<?= $value['job_type_name'] ?>" <?= isset($this->user_data['signup_worktype']) && $this->user_data['signup_worktype'] == $value['job_type_name'] ? 'selected' : '' ?>><?= $value['job_type_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6 other_worktype <?= (isset($this->user_data['signup_sciencework']) && $this->user_data['signup_sciencework'] != "" && !in_array($this->user_data['signup_sciencework'], $job_category_array)) ? '' : 'd-none' ?>">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Other work type') ?> </label>
                    <!--required-->
                    <input type="text" class="form-control other_worktype_input" name="signup[signup_sciencework]" value="<?= isset($this->user_data['signup_sciencework']) ? $this->user_data['signup_sciencework'] : '' ?>" maxlength="100" <?= (isset($this->user_data['signup_sciencework']) && $this->user_data['signup_sciencework'] != "" && !in_array($this->user_data['signup_sciencework'], $job_category_array)) ? '' : 'disabled' ?> />
                </div>

                <div class="col-md-6">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Preferred organization') ?> </label>
                    <!--required-->
                    <select class="form-select" name="signup[signup_preferred_organization]" >
                        <option value="">Select preferred organization</option>
                        <?php if (isset($organization_type) && count($organization_type) > 0) : ?>
                            <?php foreach ($organization_type as $key => $value) : ?>
                                <option value="<?= $value['organization_type_name'] ?>" <?= isset($this->user_data['signup_preferred_organization']) && $this->user_data['signup_preferred_organization'] == $value['organization_type_name'] ? 'selected' : '' ?>><?= $value['organization_type_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

        <form class="profileExperienceForm" id="profileExperienceForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Professional experience') ?></h5>
                </div>
                <div id="experienceDiv">
                    <?php if (isset($signup_experience) && empty($signup_experience)) : ?>
                        <div class="row">
                            <input type="hidden" name="signup_credential[0][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_credential[0][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EXPERIENCE ?>" />

                            <div class="col-md-3">
                                <label><?= __('Company') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter company" name="signup_credential[0][signup_credential_company]" required value="" maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Designation') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter designation" name="signup_credential[0][signup_credential_designation]" required value="" maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Start date') ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control signup_credential_start_date" id="signup_credential_start_date00" name="signup_credential[0][signup_credential_start_date]" required />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('End date') ?></label>
                                <input type="date" class="form-control signup_credential_end_date" id="signup_credential_end_date00" name="signup_credential[0][signup_credential_end_date]" />
                            </div>
                            <div class="col-md-12">
                                <label><?= __('Summary of role') ?></label>
                                <textarea class="form-control" name="signup_credential[0][signup_credential_desc]" maxlength="5000"></textarea>
                            </div>
                        </div>
                    <?php elseif (isset($signup_experience) && count($signup_experience) > 0) : ?>
                        <?php foreach ($signup_experience as $key => $value) : ?>
                            <a href="javascript:;" class="deleteCredential" data-id="<?= $value['signup_credential_id'] ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                            <div class="row">
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EXPERIENCE ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_id]" value="<?= $value['signup_credential_id'] ?>" />

                                <div class="col-md-3">
                                    <label><?= __('Company') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter company" name="signup_credential[<?= $key ?>][signup_credential_company]" required value="<?= $value['signup_credential_company'] ?>" maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Designation') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter designation" name="signup_credential[<?= $key ?>][signup_credential_designation]" required value="<?= $value['signup_credential_designation'] ?>" maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Start date') ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control signup_credential_start_date" data-id="<?= $key ?>" id="signup_credential_start_date<?= $key ?>" name="signup_credential[<?= $key ?>][signup_credential_start_date]" value="<?= date('Y-m-d', strtotime($value['signup_credential_start_date'])) ?>" required />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('End date') ?></label>
                                    <input type="date" class="form-control signup_credential_end_date" data-id="<?= $key ?>" id="signup_credential_end_date<?= $key ?>" name="signup_credential[<?= $key ?>][signup_credential_end_date]" value="<?= date('Y-m-d', strtotime($value['signup_credential_end_date'])) ?>" />
                                </div>
                                <div class="col-md-12">
                                    <label><?= __('Summary of role') ?></label>
                                    <textarea class="form-control" name="signup_credential[<?= $key ?>][signup_credential_desc]" maxlength="5000"><?= isset($value['signup_credential_desc']) ? $value['signup_credential_desc'] : '' ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="javascript:;" class="addExperience"><i class="fa fa-plus-square"></i></a>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileExperienceFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

        <form class="profileEducationForm" id="profileEducationForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Education') ?></h5>
                </div>
                <div id="educationDiv">
                    <?php if (isset($signup_education) && empty($signup_education)) : ?>
                        <div class="row">
                            <input type="hidden" name="signup_credential[0][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_credential[0][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EDUCATION ?>" />

                            <div class="col-md-3">
                                <label><?= __('University/College Name') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter University/College" name="signup_credential[0][signup_credential_organization]" value="" required maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Name of Program') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter name of program" name="signup_credential[0][signup_credential_program]" value="" required maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Qualification') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter qualification" name="signup_credential[0][signup_credential_qualification]" value="" required maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Dates at University/College') ?></label>
                                <input type="date" class="form-control" name="signup_credential[0][signup_credential_date]" />
                            </div>
                        </div>
                    <?php elseif (isset($signup_education) && count($signup_education) > 0) : ?>
                        <?php foreach ($signup_education as $key => $value) : ?>
                            <a href="javascript:;" class="deleteCredential" data-id="<?= $value['signup_credential_id'] ?>"><i class="fa fa-trash"></i></a>
                            <div class="row">
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EDUCATION ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_id]" value="<?= $value['signup_credential_id'] ?>" />

                                <div class="col-md-3">
                                    <label><?= __('University/College Name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter University/College" name="signup_credential[<?= $key ?>][signup_credential_organization]" value="<?= $value['signup_credential_organization'] ?>" required maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Name of Program') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter name of program" name="signup_credential[<?= $key ?>][signup_credential_program]" value="<?= $value['signup_credential_program'] ?>" required maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Qualification') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter qualification" name="signup_credential[<?= $key ?>][signup_credential_qualification]" value="<?= $value['signup_credential_qualification'] ?>" required maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Dates at that University/College') ?></label>
                                    <input type="date" class="form-control" name="signup_credential[<?= $key ?>][signup_credential_date]" value="<?= date('Y-m-d', strtotime($value['signup_credential_date'])) ?>" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="javascript:;" class="addEducation"><i class="fa fa-plus-square"></i></a>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileEducationFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

        <form class="profileLicenseForm" id="profileLicenseForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Professional licenses') ?></h5>
                </div>
                <div id="licenseDiv">
                    <?php if (isset($signup_license) && empty($signup_license)) : ?>
                        <div class="row">
                            <input type="hidden" name="signup_credential[0][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_credential[0][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_LICENSE ?>" />

                            <div class="col-md-6">
                                <label><?= __('License Name') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter license" name="signup_credential[0][signup_credential_name]" required value="" maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Where license obtained from') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="" name="signup_credential[0][signup_credential_organization]" value="" required maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Date of validity of license') ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="signup_credential[0][signup_credential_date]" required />
                            </div>
                        </div>
                    <?php elseif (isset($signup_license) && count($signup_license) > 0) : ?>
                        <?php foreach ($signup_license as $key => $value) : ?>
                            <a href="javascript:;" class="deleteCredential" data-id="<?= $value['signup_credential_id'] ?>"><i class="fa fa-trash"></i></a>
                            <div class="row">
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_LICENSE ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_id]" value="<?= $value['signup_credential_id'] ?>" />

                                <div class="col-md-6">
                                    <label><?= __('License Name') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter license" name="signup_credential[<?= $key ?>][signup_credential_name]" required value="<?= $value['signup_credential_name'] ?>" maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Where license obtained from') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="" name="signup_credential[<?= $key ?>][signup_credential_organization]" value="<?= $value['signup_credential_organization'] ?>" required maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Date of validity of license') ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="signup_credential[<?= $key ?>][signup_credential_date]" value="<?= date('Y-m-d', strtotime($value['signup_credential_date'])) ?>" required />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="javascript:;" class="addLicense"><i class="fa fa-plus-square"></i></a>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileLicenseFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

        <form class="profileCertificateForm" id="profileCertificateForm" method="POST" action="javascript:;" novalidate enctype="multipart/form-data">
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Certificates') ?></h5>
                </div>
                <div id="certificateDiv">
                    <?php if (isset($signup_certificate) && empty($signup_certificate)) : ?>
                        <div class="row">
                            <input type="hidden" name="signup_credential[0][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_credential[0][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_CERTIFICATE ?>" />

                            <div class="col-md-6">
                                <label><?= __('Certificate') ?> <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="signup_credential[0][signup_credential_attachment]" required />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Certificate obtained from') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="" name="signup_credential[0][signup_credential_organization]" required value="" maxlength="500" />
                            </div>
                            <div class="col-md-3">
                                <label><?= __('Date certificate obtained') ?> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="signup_credential[0][signup_credential_date]" value="" required />
                            </div>
                        </div>
                    <?php elseif (isset($signup_certificate) && count($signup_certificate) > 0) : ?>
                        <?php foreach ($signup_certificate as $key => $value) : ?>
                            <a href="javascript:;" class="deleteCredential" data-id="<?= $value['signup_credential_id'] ?>"><i class="fa fa-trash"></i></a>
                            <div class="row">
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_CERTIFICATE ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_id]" value="<?= $value['signup_credential_id'] ?>" />

                                <div class="col-md-1" id="view-file">
                                    <a href="<?= get_image($value['signup_credential_attachment_path'], $value['signup_credential_attachment']) ?>" target="_blank" data-toggle="tooltip" title="View certificate"><i class="fa fa-eye"></i></a>
                                </div>
                                <div class="col-md-5">
                                    <label><?= __('Certificate') ?> <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="signup_credential[<?= $key ?>][signup_credential_attachment]" required />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Certificate obtained from') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="" name="signup_credential[<?= $key ?>][signup_credential_organization]" required value="<?= $value['signup_credential_organization'] ?>" maxlength="500" />
                                </div>
                                <div class="col-md-3">
                                    <label><?= __('Date certificate obtained') ?> <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="signup_credential[<?= $key ?>][signup_credential_date]" value="<?= date('Y-m-d', strtotime($value['signup_credential_date'])) ?>" required />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="javascript:;" class="addCertificate"><i class="fa fa-plus-square"></i></a>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileCertificateFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

        <form class="profilePublicationForm" id="profilePublicationForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="row">
                <div class="col-12 mt-4">
                    <h5><?= __('Publications') ?></h5>
                </div>
                <div id="publicationDiv">
                    <?php if (isset($signup_publication) && empty($signup_publication)) : ?>
                        <div class="row">
                            <input type="hidden" name="signup_credential[0][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                            <input type="hidden" name="signup_credential[0][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_PUBLICATION ?>" />

                            <div class="col-md-6">
                                <label><?= __('Publication citation') ?> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter publication citation" name="signup_credential[0][signup_credential_name]" required value="" maxlength="500" />
                            </div>
                            <div class="col-md-6">
                                <label><?= __('Publication URL') ?> <span class="text-danger">*</span></label>
                                <input type="url" class="form-control" placeholder="Enter publication url" name="signup_credential[0][signup_credential_url]" value="" required maxlength="1000" />
                            </div>
                        </div>
                    <?php elseif (isset($signup_publication) && count($signup_publication) > 0) : ?>
                        <?php foreach ($signup_publication as $key => $value) : ?>
                            <a href="javascript:;" class="deleteCredential" data-id="<?= $value['signup_credential_id'] ?>"><i class="fa fa-trash"></i></a>
                            <div class="row">
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_PUBLICATION ?>" />
                                <input type="hidden" name="signup_credential[<?= $key ?>][signup_credential_id]" value="<?= $value['signup_credential_id'] ?>" />

                                <div class="col-md-6">
                                    <label><?= __('Publication citation') ?> <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Enter publication citation" name="signup_credential[<?= $key ?>][signup_credential_name]" required value="<?= $value['signup_credential_name'] ?>" maxlength="500" />
                                </div>
                                <div class="col-md-6">
                                    <label><?= __('Publication URL') ?> <span class="text-danger">*</span></label>
                                    <input type="url" class="form-control" placeholder="Enter publication url" name="signup_credential[<?= $key ?>][signup_credential_url]" value="<?= $value['signup_credential_url'] ?>" required maxlength="1000" />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <a href="javascript:;" class="addPublication"><i class="fa fa-plus-square"></i></a>
                </div>
                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profilePublicationFormBtn"><?= __('Save changes') ?></button>
                </div>
            </div>
        </form>
        <hr />

    </div>
</div>

<script id="search-js" defer="" src="https://api.mapbox.com/search-js/v1.0.0-beta.16/web.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

<script>
    function invalidFileLength(file) {
        var video = document.createElement('video');
        video.preload = 'metadata';
        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            if (video.duration <= 120) {
                return false;
            } else {
                console.log("Invalid Video! video is less than 120 second");
                return true;
            }
        }
        // video.src = URL.createObjectURL(file);
    }

    async function saveVideo() {
        var data = new FormData(document.getElementById('signupVideoForm'))
        var url = "<?php echo l('dashboard/profile/saveVideo'); ?>";
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
                    $('#signupVideoFormBtn').attr('disabled', true)
                    $('#signupVideoFormBtn').html('Saving ...')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    AdminToastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                },
                complete: function() {
                    $('#signupVideoFormBtn').attr('disabled', false)
                    $('#signupVideoFormBtn').html('Save')
                }
            });
        })
    }

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/profile/deleteVideo'
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

    $(document).ready(function() {
        
        //
        if($('input[name=signup_logo_image]').val() == '') {
            $('.trash_profile_img').addClass('d-none')
        }
        
        const dt = new DataTransfer();

        $('#upload-signup-video').on('change', function() {
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
                $('#video-names').html('')
                if (this.files.item(i).size < 2000000) {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }

                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };

            dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                let name = $(this).parent().find('span.name').html()
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        $(this).parent().remove();
                        dt.items.remove(i);
                        continue;
                    }
                }
                const input = document.getElementById('upload-signup-video')
                input.files = dt.files;
            });
        })
        
        $('#signupVideoForm').on('submit', function() {
            var size_error = false;
            var length_error = false;

            var signupVideo = document.getElementById('upload-signup-video')
            if(signupVideo.files.length == 0) {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= __("Attach a video to upload!") ?>',
                });
            } else {
                $('#upload-signup-video').each(function(index, ele) {
                    for (var i = 0; i < ele.files.length; i++) {
                        const file = ele.files[i];
                        if(invalidFileLength(file)) {
                            length_error = true;
                        }
                        if (file.size > 2000000) {
                            size_error = true;
                        }
                    }
                }) 
                
                if(!size_error && !length_error) {
                    saveVideo().then(
                        function(response) {
                            if (response.status == 0) {
                                $.dialog({
                                    backgroundDismiss: true,
                                    title: '<?= __("Error") ?>',
                                    content: response.txt,
                                });
                                return false;
                            } else if (response.status == 1) {
                                AdminToastr.success(response.txt, 'Success');
                                $(".videoDiv").load(location.href + " .videoDiv>*", function() {
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                    $('#files-area').html('')
                                });
                            }                    
                        }
                    )
                } else {
                    if(size_error) {
                        $.dialog({
                            backgroundDismiss: true,
                            title: '<?= __("Error") ?>',
                            content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                        });
                    }
                    if(length_error) {
                        $.dialog({
                            backgroundDismiss: true,
                            title: '<?= __("Error") ?>',
                            content: '<?= __("Upload a video of maximum duration of 120 seconds!") ?>',
                        });
                    }
                }
            }
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
        
        // make select2
        $('.languageSelect').select2({
            maximumSelectionLength: 10
        });

        // jquery tags input
        $('#signup_skill').tagsinput({
            maxTags: 30,
        });
        $('#signup_recognition').tagsinput({
            maxTags: 30,
        });

        // PHONE MASK //
        // intlTelInput
        const input = document.querySelector("#phone");
        const telInput = intlTelInput(input, {
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
            initialCountry: 'us',
            separateDialCode: false,
            nationalMode: false,
            autoHideDialCode: true,
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
        // PHONE MASK //

        //
        $('.phone').on('keyup keydown change focus', function() {
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                error = true;
                $('.phone').addClass('force-invalid');
            } else {
                $('.phone').removeClass('force-invalid');
            }
        })

        //
        $('#sciencework').on('change', function() {
            if ($(this).val() == 'other') {
                $('.other_worktype').removeClass('d-none')
                $('.other_worktype_input').attr('disabled', false)
                $('.other_worktype_input').val('')
            } else {
                $('.other_worktype').addClass('d-none')
                $('.other_worktype_input').attr('disabled', true)
            }
        })

        //
        birthdayPickerId.max = new Date(Date.now() - (3600 * 1000 * 24 * 31 * 12 * 18)).toISOString().split("T")[0];

        //
        var aboutEditor = null;
        if (<?= ($this->model_signup->hasPremiumPermission()) ? 1 : 0 ?>) {
            ClassicEditor
                .create(document.querySelector('#editor'))
                .then(editor => {
                    aboutEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // location autocomplete
        $(function() {
            $("#address").autocomplete({
                source: function(request, response) {
                    $.getJSON(base_url + 'job/mapbox', {
                            _token: $('meta[name="csrf-token"]').attr('content'),
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

        //
        $('body').on('click', '.deleteCredential', function() {
            var id = $(this).parent().attr('id');
            swal({
                title: "<?= __('Are you sure?') ?>",
                text: "<?= __('Perform this action.') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'signup_credential[signup_credential_id]': $(this).data('id'),
                        'signup_credential[signup_credential_status]': '<?= STATUS_INACTIVE ?>',
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    }

                    var url = base_url + 'dashboard/profile/update_credentials'

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
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
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
                            if (response.status) {
                                AdminToastr.success(response.txt, 'Success');
                                //
                                $('#' + id).load(location.href + " #" + id + ">*", function() {
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                });
                            } else {
                                AdminToastr.error(response.txt)
                            }
                        }
                    )
                }
            })
        })
        
        //
        $('body').on('change', '.signup_credential_start_date', function() {
            // var signup_credential_end_date_id = $('.signup_credential_start_date').parent().parent().find('.signup_credential_end_date').attr('id');
            var signup_credential_end_date_id = $(this).parent().parent().find('.signup_credential_end_date').attr('id');
            document.getElementById(signup_credential_end_date_id).setAttribute("min", new Date(Date.parse($(this).val()) + (3600 * 1000)).toISOString().split("T")[0])
        })
        
        //
        var experience_counter = '<?= isset($signup_experience) ? count($signup_experience) : 0 ?>';
        $('.addExperience').click(function() {
            html = '<div class="row">' +
                '<input type="hidden" name="signup_credential[' + experience_counter + '][signup_credential_signup_id]" value="<?= $this->userid ?>" />' +
                '<input type="hidden" name="signup_credential[' + experience_counter + '][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EXPERIENCE ?>" />' +
                '<a href="javascript:;" class="removeCredential"><i class="fa fa-minus-square"></i></a>' +
                '<div class="col-md-3">' +
                '<label>' + '<?= __('Company') ?>' + ' <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter company" name="signup_credential[' + experience_counter + '][signup_credential_company]" required value="" maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label>' + '<?= __('Designation') ?>' + ' <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter designation" name="signup_credential[' + experience_counter + '][signup_credential_designation]" required value="" maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label>' + '<?= __('Start date') ?>' + ' <span class="text-danger">*</span></label>' +
                '<input type="date" class="form-control signup_credential_start_date" id="signup_credential_start_date'+ experience_counter +'" name="signup_credential[' + experience_counter + '][signup_credential_start_date]" required />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label>' + '<?= __('End date') ?>' + '</label>' +
                '<input type="date" class="form-control signup_credential_end_date" id="signup_credential_end_date'+ experience_counter +'"  name="signup_credential[' + experience_counter + '][signup_credential_end_date]" />' +
                '</div>' +
                '<div class="col-md-12">' +
                '<label>' + '<?= __('Summary of role') ?>' + '</label>' +
                '<textarea class="form-control" name="signup_credential[' + experience_counter + '][signup_credential_desc]" maxlength="5000"></textarea>'
            '</div>' +
            '</div>';
            $('#experienceDiv').append(html)
            experience_counter++;
        })

        $("body").on('submit', '#profileExperienceForm', function() {
            
            if (!$('.profileExperienceForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.profileExperienceForm').addClass('was-validated');
                $('.profileExperienceForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.profileExperienceForm').removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = $("#profileExperienceForm").serialize();
            var url = "<?php echo l('dashboard/profile/update_credentials'); ?>";

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
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#profileExperienceFormBtn').attr('disabled', true)
                        $('#profileExperienceFormBtn').html('Saving ...')
                    },
                    complete: function() {
                        $('#profileExperienceFormBtn').attr('disabled', false)
                        $('#profileExperienceFormBtn').html('Save changes')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        //
                        $("#experienceDiv").load(location.href + " #experienceDiv>*", function() {
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
        //

        //
        var education_counter = '<?= isset($signup_education) ? count($signup_education) : 0 ?>';
        $('.addEducation').click(function() {
            html = '<div class="row">' +
                '<input type="hidden" name="signup_credential[' + education_counter + '][signup_credential_signup_id]" value="<?= $this->userid ?>" />' +
                '<input type="hidden" name="signup_credential[' + education_counter + '][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_EDUCATION ?>" />' +
                '<a href="javascript:;" class="removeCredential"><i class="fa fa-minus-square"></i></a>' +
                '<div class="col-md-3">' +
                '<label><?= __('University/College Name') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter University/College" name="signup_credential[' + education_counter + '][signup_credential_organization]" value="" required maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Name of Program') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter name of program" name="signup_credential[' + education_counter + '][signup_credential_program]" value="" required maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Qualification') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter qualification" name="signup_credential[' + education_counter + '][signup_credential_qualification]" value="" required maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Dates at that University/College') ?></label>' +
                '<input type="date" class="form-control" name="signup_credential[' + education_counter + '][signup_credential_date]" />' +
                '</div>' +
                '</div>';
            $('#educationDiv').append(html)
            education_counter++;
        })

        //
        $("body").on('submit', '#profileEducationForm', function() {
            
            if (!$('.profileEducationForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.profileEducationForm').addClass('was-validated');
                $('.profileEducationForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.profileEducationForm').removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = $("#profileEducationForm").serialize();
            var url = "<?php echo l('dashboard/profile/update_credentials'); ?>";

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
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#profileEducationFormBtn').attr('disabled', true)
                        $('#profileEducationFormBtn').html('Saving ...')
                    },
                    complete: function() {
                        $('#profileEducationFormBtn').attr('disabled', false)
                        $('#profileEducationFormBtn').html('Save changes')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        //
                        $("#educationDiv").load(location.href + " #educationDiv>*", function() {
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
        //

        //
        var license_counter = '<?= isset($signup_license) ? count($signup_license) : 0 ?>';
        $('.addLicense').click(function() {
            html = '<div class="row">' +
                '<input type="hidden" name="signup_credential[' + license_counter + '][signup_credential_signup_id]" value="<?= $this->userid ?>" />' +
                '<input type="hidden" name="signup_credential[' + license_counter + '][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_LICENSE ?>" />' +
                '<a href="javascript:;" class="removeCredential"><i class="fa fa-minus-square"></i></a>' +
                '<div class="col-md-6">' +
                '<label><?= __('License Name') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter license" name="signup_credential[' + license_counter + '][signup_credential_name]" required value="" maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Where license obtained from') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="" name="signup_credential[' + license_counter + '][signup_credential_organization]" value="" required maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Date of validity of license') ?> <span class="text-danger">*</span></label>' +
                '<input type="date" class="form-control" name="signup_credential[' + license_counter + '][signup_credential_date]" required />' +
                '</div>' +
                '</div>';
            $('#licenseDiv').append(html)
            license_counter++;
        });

        $("body").on('submit', '#profileLicenseForm', function() {
            
            if (!$('.profileLicenseForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.profileLicenseForm').addClass('was-validated');
                $('.profileLicenseForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.profileLicenseForm').removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = $("#profileLicenseForm").serialize();
            var url = "<?php echo l('dashboard/profile/update_credentials'); ?>";

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
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#profileLicenseFormBtn').attr('disabled', true)
                        $('#profileLicenseFormBtn').html('Saving ...')
                    },
                    complete: function() {
                        $('#profileLicenseFormBtn').attr('disabled', false)
                        $('#profileLicenseFormBtn').html('Save changes')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        //
                        $("#licenseDiv").load(location.href + " #licenseDiv>*", function() {
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
        //

        //
        var certificate_counter = '<?= isset($signup_certificate) ? count($signup_certificate) : 0 ?>';
        $('.addCertificate').click(function() {
            html = '<div class="row">' +
                '<input type="hidden" name="signup_credential[' + certificate_counter + '][signup_credential_signup_id]" value="<?= $this->userid ?>" />' +
                '<input type="hidden" name="signup_credential[' + certificate_counter + '][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_CERTIFICATE ?>" />' +
                '<a href="javascript:;" class="removeCredential"><i class="fa fa-minus-square"></i></a>' +
                '<div class="col-md-6">' +
                '<label><?= __('Certificate') ?> <span class="text-danger">*</span></label>' +
                '<input type="file" class="form-control" name="signup_credential[' + certificate_counter + '][signup_credential_attachment]" required />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Certificate obtained from') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="" name="signup_credential[' + certificate_counter + '][signup_credential_organization]" required value="" maxlength="500" />' +
                '</div>' +
                '<div class="col-md-3">' +
                '<label><?= __('Date certificate obtained') ?> <span class="text-danger">*</span></label>' +
                '<input type="date" class="form-control" name="signup_credential[' + certificate_counter + '][signup_credential_date]" value="" required />' +
                '</div>' +
                '</div>';
            $('#certificateDiv').append(html)
            license_counter++;
        });

        $("body").on('submit', '#profileCertificateForm', function() {
            
            if (!$('.profileCertificateForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.profileCertificateForm').addClass('was-validated');
                $('.profileCertificateForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.profileCertificateForm').removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = new FormData(document.getElementById("profileCertificateForm"));
            var url = "<?php echo l('dashboard/profile/update_credentials'); ?>";
            
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
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#profileCertificateFormBtn').attr('disabled', true)
                        $('#profileCertificateFormBtn').html('Saving ...')
                    },
                    complete: function() {
                        $('#profileCertificateFormBtn').attr('disabled', false)
                        $('#profileCertificateFormBtn').html('Save changes')
                    }
                });
            }).then(
                function(response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt, 'Success');
                        //
                        $("#certificateDiv").load(location.href + " #certificateDiv>*", function() {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    }
                }
            )
        })
        //

        //
        var publication_counter = '<?= isset($signup_publication) ? count($signup_publication) : 0 ?>';
        $('.addPublication').click(function() {
            html = '<div class="row">' +
                '<input type="hidden" name="signup_credential[' + publication_counter + '][signup_credential_signup_id]" value="<?= $this->userid ?>" />' +
                '<input type="hidden" name="signup_credential[' + publication_counter + '][signup_credential_type]" value="<?= SIGNUP_CREDENTIAL_PUBLICATION ?>" />' +
                '<a href="javascript:;" class="removeCredential"><i class="fa fa-minus-square"></i></a>' +
                '<div class="col-md-6">' +
                '<label><?= __('Publication citation') ?> <span class="text-danger">*</span></label>' +
                '<input type="text" class="form-control" placeholder="Enter publication citation" name="signup_credential[' + publication_counter + '][signup_credential_name]" required value="" maxlength="500" />' +
                '</div>' +
                '<div class="col-md-6">' +
                '<label><?= __('Publication URL') ?> <span class="text-danger">*</span></label>' +
                '<input type="url" class="form-control" placeholder="Enter publication url" name="signup_credential[' + publication_counter + '][signup_credential_url]" value="" required maxlength="1000" />' +
                '</div>' +
                '</div>';
            $('#publicationDiv').append(html)
            publication_counter++;
        })

        $("body").on('submit', '#profilePublicationForm', function() {

            if (!$('.profilePublicationForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.profilePublicationForm').addClass('was-validated');
                $('.profilePublicationForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.profilePublicationForm').removeClass('was-validated');
            }

            //
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = $("#profilePublicationForm").serialize();
            var url = "<?php echo l('dashboard/profile/update_credentials'); ?>";
            
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
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#profilePublicationFormBtn').attr('disabled', true)
                        $('#profilePublicationFormBtn').html('Saving ...')
                    },
                    complete: function() {
                        $('#profilePublicationFormBtn').attr('disabled', false)
                        $('#profilePublicationFormBtn').html('Save changes')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        //
                        $("#publicationDiv").load(location.href + " #publicationDiv>*", function() {
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
        //

        //
        $('body').on('click', '.removeCredential', function() {
            $(this).parent().remove()
        })

        //
        $("#profileForm").on('submit', function() {
            var error = false;
            var size_error = false;

            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                error = true;
                $('.phone').addClass('force-invalid');
                $('.phone').focus()
            } else {
                $('.phone').removeClass('force-invalid');
            }

            if ($('#signup_is_address_verified').length && $('#signup_is_address_verified').val() == 0) {
                error = true;
                $('#address').focus()
            }

            if (!$('#profileForm')[0].checkValidity() || error) {
                event.preventDefault()
                event.stopPropagation()
                $('#profileForm').addClass('was-validated');
                $('#profileForm').find(":invalid").first().focus();

                if (aboutEditor != null && aboutEditor.getData() == '<p>&nbsp;</p>') {
                    $('.aboutEditor.invalid-feedback').show()
                } else {
                    $('.aboutEditor.invalid-feedback').hide()
                }
                return false;
            } else {
                if (aboutEditor != null && aboutEditor.getData() == '<p>&nbsp;</p>') {
                    $('.aboutEditor.invalid-feedback').show()
                    return false;
                } else {
                    $('.aboutEditor.invalid-feedback').hide()
                }
                $('#profileForm').removeClass('was-validated');
            }

            // $('#upload-signup-video').each(function(index, ele) {
            //     for (var i = 0; i < ele.files.length; i++) {
            //         const file = ele.files[i];
            //         if (file.size > 2000000) {
            //             size_error = true;
            //         }
            //     }
            // })
            
            if(!size_error) {
                var data = $("#profileForm").serialize();
                var url = "<?php echo l('dashboard/profile/update'); ?>";
                
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
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        },
                        beforeSend: function() {
                            $('#profileFormBtn').attr('disabled', true)
                            $('#profileFormBtn').html('Saving ...')
                        },
                        complete: function() {
                            $('#profileFormBtn').attr('disabled', false)
                            $('#profileFormBtn').html('Save changes')
                        }
                    });
                }).then(
                    function(response) {
                        if (response.status) {
                            AdminToastr.success(response.txt, 'Success');
                        } else {
                            AdminToastr.error(response.txt, 'Error');
                        }
                    }
                )
            } else {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                });
            }
        })

        $('body').on('click', '.trash_profile_img', function(e) {
            if($('input[name=signup_logo_image]').val() != '') {
                swal({
                    title: "<?= __('Are you sure?') ?>",
                    text: "<?= __('You are about to delete your profile image.') ?>",
                    icon: "warning",
                    className: "text-center",
                    buttons: ["<?= __('Cancel') ?>", "<?= __('Yes') ?>"],
                }).
                then((isConfirm) => {
                    if (isConfirm) {

                        var data = {}
                        var url = '<?= g('base_url') ?>dashboard/profile/update_image'

                        updateProfileImageAjax(data, url).then(
                            function(response) {
                                if (response.status == 0) {
                                    AdminToastr.error(response.txt, 'Error');
                                } else if (response.status == 1) {
                                    AdminToastr.success(response.txt, 'Success');
                                }
                            }
                        );
                        
                    } else {
                        swal("<?= __('Cancelled') ?>", "<?= __('Action aborted') ?>", "error");
                    }
                })
            } else {
                swal('Error', 'No image found to delete!', 'error')
            }
        })
    })
</script>
<script>
    $(document).ready(function() {
        $('body').on('change keyup keydown', '#address', function() {
            $('#signup_is_address_verified').val(0)
            if ($('#validate-address').hasClass('text-success')) {
                $('#validate-address').removeClass('text-success')
            }
            if (!$('#validate-address').hasClass('text-danger')) {
                $('#validate-address').addClass('text-danger')
            }
            $('#validate-address').html('<i class="fa fa-clock-o"></i> validate.')
        })

        $('body').on('click', '#validate-address', function() {
            var url = base_url + 'job/mapbox'
            var data = {
                'term': $('#address').val(),
                'validate': true,
                '_token': $('meta[name="csrf-token"]').attr('content')
            }

            jQuery.ajax({
                url: url,
                type: "GET",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    if (response) {
                        relevance = response.relevance
                        if (relevance.includes(1)) {
                            if (!$('#validate-address').hasClass('text-success')) {
                                $('#validate-address').addClass('text-success')
                            }
                            if ($('#validate-address').hasClass('text-danger')) {
                                $('#validate-address').removeClass('text-danger')
                            }
                            $('#validate-address').html('<i class="fa fa-check-circle"></i> validated.')
                            $('#signup_is_address_verified').val(1)
                        } else {
                            if ($('#validate-address').hasClass('text-success')) {
                                $('#validate-address').removeClass('text-success')
                            }
                            if (!$('#validate-address').hasClass('text-danger')) {
                                $('#validate-address').addClass('text-danger')
                            }
                            $('#validate-address').html('<i class="fa fa-times-circle"></i> not validated.')
                            setTimeout(() => {
                                $('#validate-address').html('<i class="fa fa-clock-o"></i> validate.')
                            }, 1000)
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    AdminToastr.error('An unexpected error has occurred, Try refreshing the page.');
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function() {},
                beforeSend: function() {
                    $('#validate-address').html('<span class="spinner-border spinner-border-sm"></span>')
                }
            });
        })

        $('body').on('click', '.changePrivacy', function() {
            
            var privacy = $(this).data('value')
            var data = {
                'signup': {
                    'signup_privacy': privacy,
                },
                '_token': $('meta[name="csrf-token"]').attr('content')
            }
            var url = base_url + 'dashboard/profile/update'

            AjaxRequest.asyncRequest(url, data).then(
                function(response) {
                    if (response.status) {
                        setTimeout(function(){
                            $('#privacyType').html(privacy[0].toUpperCase() + privacy.substring(1))
                            $('input[name=signup_privacy]').val(privacy)
                        }, 1000)
                    } else {
                        var old = $('input[name=signup_privacy]').val()
                        $('#privacyType').html(old[0].toUpperCase() + old.substring(1))
                    }
                }
            )
        })
    })
</script>