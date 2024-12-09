<style>
    /* user-dashboard-info-box */
    .user-dashboard-info-box .candidates-list .thumb {
        margin-right: 20px;
    }

    .user-dashboard-info-box .candidates-list .thumb img {
        width: 80px;
        height: 80px;
        -o-object-fit: cover;
        object-fit: cover;
        overflow: hidden;
        border-radius: 50%;
    }

    .user-dashboard-info-box .title {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 10px 0;
    }

    .title {
        text-transform: none;
    }

    .user-dashboard-info-box .candidates-list td {
        vertical-align: middle;
    }

    .user-dashboard-info-box td li {
        margin: 0 4px;
    }

    .user-dashboard-info-box .table thead th {
        border-bottom: none;
    }

    .table.manage-candidates-top th {
        border: 0;
    }

    .user-dashboard-info-box .candidate-list-favourite-time .candidate-list-favourite {
        margin-bottom: 10px;
    }

    .table.manage-candidates-top {
        min-width: 650px;
    }

    .user-dashboard-info-box .candidate-list-details ul {
        color: #969696;
    }

    /* Candidate List */
    .candidate-list {
        background: #ffffff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        border-bottom: 1px solid #eeeeee;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 20px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .candidate-list:hover {
        -webkit-box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
        box-shadow: 0px 0px 34px 4px rgba(33, 37, 41, 0.06);
        position: relative;
        z-index: 99;
    }

    .candidate-list:hover a.candidate-list-favourite {
        color: #e74c3c;
        -webkit-box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
        box-shadow: -1px 4px 10px 1px rgba(24, 111, 201, 0.1);
    }

    .candidate-list .candidate-list-image {
        margin-right: 25px;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 80px;
        flex: 0 0 80px;
        border: none;
    }

    .candidate-list .candidate-list-image img {
        width: 80px;
        height: 80px;
        -o-object-fit: cover;
        object-fit: cover;
    }

    .candidate-list-title {
        margin-bottom: 5px;
    }

    .candidate-list-details ul {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-bottom: 0px;
    }

    .candidate-list-details ul li {
        margin: 5px 10px 5px 0px;
        font-size: 13px;
    }

    .candidate-list .candidate-list-favourite-time {
        margin-left: auto;
        text-align: center;
        font-size: 13px;
        -webkit-box-flex: 0;
        -ms-flex: 0 0 90px;
        flex: 0 0 90px;
    }

    .candidate-list .candidate-list-favourite-time span {
        display: block;
        margin: 0 auto;
    }

    .candidate-list .candidate-list-favourite-time .candidate-list-favourite {
        display: inline-block;
        position: relative;
        height: 40px;
        width: 40px;
        line-height: 40px;
        border: 1px solid #eeeeee;
        border-radius: 100%;
        text-align: center;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        margin-bottom: 20px;
        font-size: 16px;
        color: #646f79;
    }

    .candidate-list .candidate-list-favourite-time .candidate-list-favourite:hover {
        background: #ffffff;
        color: #e74c3c;
    }

    .candidate-banner .candidate-list:hover {
        position: inherit;
        -webkit-box-shadow: inherit;
        box-shadow: inherit;
        z-index: inherit;
    }
</style>

<div class="expert-box det-box">

    <div class="row align-items-end">

        <div class="col-lg-9 d-flex align-items-start">

            <div class="u-box">

                <h1 class="m-0 text-white">
                    <?= (isset($job['job_title']) && $job['job_title']) ? strtoupper($job['job_title'][0]) : '&#183;' ?>
                </h1>

            </div>

            <div>

                <h3 class="job_title"><?= isset($job['job_title']) ? strtoupper($job['job_title']) : '...' ?></h3>
                <input type="hidden" name="job_slug" value="<?= (isset($job['job_slug']) ? $job['job_slug'] : '') ?>" />

                <div class="tag-ts">
                    <i class="fa-solid fa-circle-check"></i>
                    <?= isset($job['job_type']) ? $job['job_type'] : '...' ?>
                </div>

                <div class="d-flex">

                    <?php $job_tags = isset($job['job_tags']) ? explode(',', $job['job_tags']) : array(); ?>

                    <?php foreach ($job_tags as $ke1 => $val1) : ?>
                        <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>
                    <?php endforeach; ?>

                </div>

                <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                    <div class="badges">

                        <?php if (isset($job['job_category']) && $job['job_category'] != NULL && @unserialize($job['job_category']) !== FALSE) : ?>
                            <span>
                                <i class="fa-light fa-briefcase"></i>
                                <?php foreach (unserialize($job['job_category']) as $ke => $val) : ?>
                                    <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                                <?php endforeach; ?>
                            </span>
                        <?php endif; ?>

                        <span>
                            <i class="fa-light fa-location-dot"></i>
                            <?php if (isset($job['job_location']) && $job['job_location']) : ?>
                                <a href="https://maps.google.com/?q=<?= $job['job_location'] ?>" target="_blank"> <?= explode(',', $job['job_location'])[0] ?? 'Na' ?></a>
                            <?php else : ?>
                                ...
                            <?php endif; ?>
                        </span>

                        <?php if ($this->userid > 0) : ?>
                            <span>
                                <i class="fa-light fa-circle-dollar-to-slot"></i>
                                <?= (isset($job['job_salary_lower']) ? price($job['job_salary_lower']) : price(0)) . (isset($job['job_salary_upper']) && $job['job_salary_upper'] ? (' - ' . price($job['job_salary_upper'])) : '') . ((isset($job['job_salary_interval']) ? ' / ' . $job['job_salary_interval'] : '')) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

            </div>
        </div>

        <div class="col-lg-3 text-right btn-det">
            <?php if ($this->userid == 0) : ?>
                <a href="<?= l('login') . '?redirect_url=' . urlencode(l('job/detail/') . (isset($job['job_slug']) ? $job['job_slug'] : '')) ?>" class="btn-2">Please login to view salary</a>
            <?php else : ?>
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <?php if (isset($job_application['job_application_id'])) : ?>
                        <p><i class="fa fa-circle-check text-success"></i>&nbsp;<?= __('Job request sent.') ?></p>
                        <?= __('See application details') ?><a target="_blank" href="<?= l('dashboard/application/detail/' . JWT::encode($job_application['job_application_id']) . '/' . $job['job_id']) ?>">&nbsp;<?= __('here') ?>&nbsp;<i class="fa fa-external-link"></i></a>
                    <?php elseif ($this->userid !== $job['job_userid']) : ?>
                        <a href="<?= l('dashboard/job/apply/' . (isset($job['job_id']) ? JWT::encode($job['job_id']) : 0)) ?>" class="btn btn-custom"><?= __('Apply Now') ?></a>
                    <?php endif; ?>
                <?php elseif ($this->userid === (int) $job['job_userid']) : ?>
                    <a class="btn btn-custom" target="_blank" href="<?= l('dashboard/job/post/') . $job['job_slug'] . '/edit' ?>" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Edit this job.') ?>"><i class="fa fa-edit text-white"></i>&nbsp;Edit</a>
                    <a class="btn btn-custom" target="_blank" href="<?= l('dashboard/application/listing/') . JWT::encode($job['job_id']) ?>" data-toggle="tooltip" data-bs-placement="top" title="<?= __('See received applications for this job.') ?>"><i class="fa fa-address-card-o text-white"></i>&nbsp;Applications</a>
                <?php elseif($this->model_signup->hasRole(ROLE_1)): ?>
                    <a class="btn btn-custom" href="<?= l('membership') ?>" target="_blank" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Upgrade to ' . RAW_ROLE_3 . ' membership to apply for this job') ?>"><small><?= __('Apply now') ?></small></a>
                <?php endif; ?>
            <?php endif; ?>

        </div>

    </div>

    <ul class="nav nav-tabs" id="myTab" role="tablist">

        <li class="nav-item" role="presentation">

            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true" data-toggle="tooltip" data-bs-placement="top" title="<?= (!$this->model_signup->hasPremiumPermission() || $this->userid == 0) ? 'Job details will be viewable once you upgrade your membership.' : '' ?>"><?= __('Job detail') ?></a>

        </li>

        <li class="nav-item" role="presentation">

            <a class="nav-link" id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true" data-toggle="tooltip" data-bs-placement="top" title="<?= (!$this->model_signup->hasPremiumPermission() || $this->userid == 0) ? 'Work description will be viewable once you upgrade your membership.' : '' ?>"><?= __('Work description') ?></a>

        </li>

        <li class="nav-item" role="presentation">

            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false" data-toggle="tooltip" data-bs-placement="top" title="<?= (!$this->model_signup->hasPremiumPermission() || $this->userid == 0) ? 'Company details will be viewable once you upgrade your membership.' : '' ?>"><?= __('About company') ?></a>

        </li>

        <li class="nav-item" role="presentation">

            <a class="nav-link" id="video-tab" data-bs-toggle="tab" href="#video" role="tab" aria-controls="video" aria-selected="false" data-toggle="tooltip" data-bs-placement="top" title="<?= (!$this->model_signup->hasPremiumPermission() || $this->userid == 0) ? 'Job video will be viewable once you upgrade your membership.' : '' ?>"><?= __('Job video') ?></a>

        </li>

        <li class="nav-item" role="presentation">

            <a class="nav-link" id="question-tab" data-bs-toggle="tab" href="#question" role="tab" aria-controls="question" aria-selected="false" data-toggle="tooltip" data-bs-placement="top" title="<?= (!$this->model_signup->hasPremiumPermission() || $this->userid == 0) ? 'Job questions will be viewable once you upgrade your membership.' : '' ?>"><?= __('Job questions') ?></a>

        </li>

        <?php if ($this->userid == $job['job_userid']) : ?>

            <li class="nav-item" role="presentation">

                <a class="nav-link" id="candidate-tab" data-bs-toggle="tab" href="#candidate" role="tab" aria-controls="candidate" aria-selected="false"><?= __('Ideal candidates') ?></a>

            </li>

        <?php endif; ?>

        <a href="javascript:;" class="share-btn share-button"><i class="fa-light fa-share-nodes"></i> Share&nbsp;this job</a>

    </ul>


    <div class="tab-content" id="myTabContent">

        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="container mt-3">
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <ul>
                        <?php if($this->userid == $job['job_userid']): ?>
                            <li><?= __('Subscription expiry') ?>:
                                <?php if (isset($job['job_subscription_expiry']) && $job['job_subscription_expiry']) : ?>
                                    <span class="text-danger"><?= date('d M, Y h:i a', strtotime($job['job_subscription_expiry'])) ?></span>
                                <?php else : ?>
                                    <?= __(NOT_AVAILABLE);  ?>
                                <?php endif; ?>
                            </li>
                        <?php endif; ?>

                        <?php $organization = array(); ?>
                        <?php if (isset($job['job_userid'])) : ?>
                            <?php $organization = $this->model_signup->find_by_pk($job['job_userid']); ?>
                        <?php endif; ?>

                        <li><?= __('Organization representative') ?>: <?= $this->model_signup->profileName($organization, FALSE) ?></li>
                        <li><?= __('Type') ?>: <?= isset($job['job_type']) ? $job['job_type'] : '...' ?></li>
                        <li><?= __('Short details') ?>: <?= isset($job['job_short_detail']) ? $job['job_short_detail'] : '...' ?></li>
                        <li><?= __('Estimated working hours') ?>: <?= (isset($job['job_estimated_hours']) && $job['job_estimated_hours']) ? $job['job_estimated_hours'] : 'Na' ?></li>
                        <li><?= __('Estimated working days') ?>: <?= (isset($job['job_estimated_days']) && $job['job_estimated_days']) ? $job['job_estimated_days'] : 'Na' ?></li>
                        <li><?= __('Estimated working weeks') ?>: <?= (isset($job['job_estimated_weeks']) && $job['job_estimated_weeks']) ? $job['job_estimated_weeks'] : 'Na' ?></li>
                        <li><?= __('Category') ?>:
                            <?php if (isset($job['job_category']) && $job['job_category'] != NULL && @unserialize($job['job_category']) !== FALSE) : ?>
                                <?php foreach (unserialize($job['job_category']) as $ke => $val) : ?>
                                    <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                ...
                            <?php endif; ?>
                        </li>
                        <li><?= __('Location') ?>:
                            <?php if (isset($job['job_location'])) : ?>
                                <a href="https://maps.google.com/?q=<?= $job['job_location'] ?>" target="_blank"> <?= $job['job_location'] ?? 'Na' ?></a>
                            <?php else : ?>
                                ...
                            <?php endif; ?>
                        </li>
                        <li><?= __('Wages') ?>:
                            <?= (isset($job['job_salary_lower']) ? price($job['job_salary_lower']) : price(0)) ?>
                                <?= (isset($job['job_salary_upper']) && $job['job_salary_upper'] ? (' - ' . price($job['job_salary_upper'])) : '') ?>
                                <?= (isset($job['job_salary_interval']) ? (' / ' . $job['job_salary_interval']) : '') ?>
                        </li>
                        <li><?= __('URL') ?>:
                            <?php if (isset($job['job_url']) && $job['job_url']) : ?>
                                <a href="<?= $job['job_url'] ?>" target="_blank"><?= $job['job_url'] ?></a>
                            <?php else : ?>
                                ...
                            <?php endif; ?>
                        </li>
                        <li><?= __('Application email') ?>:
                            <?php if (isset($job['job_application_email']) && $job['job_application_email']) : ?>
                                <a href="mailto:<?= $job['job_application_email'] ?>"><?= $job['job_application_email'] ?></a>
                            <?php else : ?>
                                <?= __(NOT_AVAILABLE);  ?>
                            <?php endif; ?>
                        </li>
                        <li><?= __('Level') ?>:
                            <?php if (isset($job['job_level']) && $job['job_level']) : ?>
                                <?= $job['job_level'] ?>
                            <?php else : ?>
                                <?= __(NOT_AVAILABLE);  ?>
                            <?php endif; ?>
                        </li>
                        <li><?= __('Language') ?>:
                            <?php if (isset($job['job_language'])) : ?>
                                <?php
                                $fetched_job_language = array();
                                if (isset($job['job_language']) && $job['job_language'] != NULL && @unserialize($job['job_language']) !== FALSE) {
                                    $fetched_job_language = unserialize($job['job_language']);
                                }
                                ?>
                                <?php if (empty($fetched_job_language) && (@unserialize($job['job_language'] !== false))) {
                                    $language = $this->model_language->find_one_active(
                                        array(
                                            'where' => array(
                                                'language_code' => $job['job_language']
                                            )
                                        )
                                    );
                                    if($language) {
                                        echo $language['language_value'];
                                    }
                                } else {
                                    foreach ($fetched_job_language as $key => $value) {
                                        if ($value)
                                            echo $this->model_language->find_one_active(
                                                array(
                                                    'where' => array(
                                                        'language_code' => $value
                                                    )
                                                )
                                            )['language_value'] . (array_key_last($fetched_job_language) ? '.' : ', ');
                                    }
                                }
                                ?>
                            <?php else : ?>
                                <?= __(NOT_AVAILABLE);  ?>
                            <?php endif; ?>
                        </li>
                        <!-- <li><?//= __('Application submission deadline') ?>:
                            <?php //if (isset($job['job_submission_deadline']) && $job['job_submission_deadline']) : ?>
                                <span class="text-danger"><b><?//= date('d M, Y', strtotime($job['job_submission_deadline'])) ?></b></span>
                            <?php //else : ?>
                                <?//= __(NOT_AVAILABLE);  ?>
                            <?php //endif; ?>
                        </li> -->
                        <li><?= __('Expiry') ?>:
                            <?php if (isset($job['job_expiry']) && $job['job_expiry']) : ?>
                                <?= date('d M, Y', strtotime($job['job_expiry'])) ?>
                            <?php else : ?>
                                <?= __(NOT_AVAILABLE);  ?>
                            <?php endif; ?>
                        </li>
                    </ul>
                <?php elseif ($this->userid > 0) : ?>
                    <small><?= __("The job details are not viewable. Reason: Insufficient Privilege.") ?></small>
                <?php else : ?>
                    <small><?= __("You need to login to view the job details.") ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
            <div class="container mt-3">
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <?php if (isset($job['job_detail']) && $job['job_detail'] != NULL) : ?>
                        <?php echo html_entity_decode($job['job_detail']); ?>
                    <?php else : ?>
                        <small><?= __("The job description is not available.") ?></small>
                    <?php endif; ?>
                <?php elseif ($this->userid > 0) : ?>
                    <small><?= __("The job description is not viewable. Reason: Insufficient Privilege.") ?></small>
                <?php else : ?>
                    <small><?= __("You need to login to view the job description.") ?></small>
                <?php endif; ?>
            </div>

        </div>

        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="container mt-3">
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <?php if (isset($job['job_company_detail']) && $job['job_company_detail'] != NULL) : ?>
                        <?php echo html_entity_decode($job['job_company_detail']); ?>
                    <?php else : ?>
                        <small><?= __("The company details are not available.") ?></small>
                    <?php endif; ?>
                <?php elseif ($this->userid > 0) : ?>
                    <small><?= __("The company details are not viewable. Reason: Insufficient Privilege.") ?></small>
                <?php else : ?>
                    <small><?= __("You need to login to view the company details.") ?></small>
                <?php endif; ?>
            </div>

        </div>

        <div class="tab-pane fade" id="video" role="tabpanel" aria-labelledby="video-tab">
            <div class="container mt-3">
                <?php if (isset($job['job_attachment']) && $job['job_attachment']) : ?>
                    <a data-fancybox href="<?= get_image($job['job_attachment_path'], $job['job_attachment']) ?>">
                        <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="500" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                    </a>
                <?php else : ?>
                    <small><?= __('The description video is unavailable for this job.') ?></small>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="question" role="tabpanel" aria-labelledby="question-tab">
            <div class="container mt-3">
                <?php if (isset($job_question) && is_array($job_question) && count($job_question) > 0) : ?>
                    <ul class="list-group-numbered">
                        <?php foreach ($job_question as $key => $value) : ?>
                            <li><?= $value['job_question_title']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <small><?= __('Questions are not available for this job.') ?></small>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($this->userid == $job['job_userid']) : ?>
            <div class="tab-pane fade" id="candidate" role="tabpanel" aria-labelledby="candidate-tab">
                <div class="container mt-3">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-12">
                                <?php if (isset($ideal_candidate) && !empty($ideal_candidate)) : ?>
                                    <div class="user-dashboard-info-box table-responsive mb-0 bg-white p-4 shadow-sm">
                                        <table class="table manage-candidates-top mb-0">
                                            <tbody>
                                                <?php foreach ($ideal_candidate as $key => $value) : ?>
                                                    <tr class="candidates-list">
                                                        <td class="title">
                                                            <div class="thumb">
                                                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                                    <img src="<?= get_user_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                                                <?php else : ?>
                                                                    <img src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="candidate-list-details">
                                                                <div class="candidate-list-info">
                                                                    <div class="candidate-list-title">
                                                                        <h5 class="mb-0">

                                                                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                                                <a href="<?= l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] ?>" target="_blank">
                                                                                <?php else : ?>
                                                                                    <a href="javascript:;">
                                                                                    <?php endif; ?>

                                                                                    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                                                        <?= $this->model_signup->listingName($value, false) ?>
                                                                                    <?php else : ?>
                                                                                        <?= $this->model_signup->listingName($value) ?>
                                                                                    <?php endif; ?>

                                                                                    <?php $data['connectionLevel'] = $this->model_signup_follow->connectionLevel($value['signup_id'], $this->userid); ?>
                                                                                    <?php $this->load->view('widgets/connection_level', $data) ?>
                                                                                    </a>
                                                                        </h5>
                                                                    </div>
                                                                    <div class="candidate-list-option">
                                                                        <ul class="list-unstyled">
                                                                            <li><i class="fas fa-envelope pr-1"></i>&nbsp;<?= isset($value['signup_email']) && $value['signup_email'] ? ($value['signup_email']) : '' ?></li>
                                                                            <li><i class="fas fa-filter pr-1"></i>&nbsp;<?= isset($value['signup_profession']) && $value['signup_profession'] ? ($value['signup_profession']) : '' ?></li>
                                                                            <li><i class="fas fa-map-marker-alt pr-1"></i>&nbsp;<?= isset($value['signup_address']) && $value['signup_address'] ? ($value['signup_address']) : '' ?></li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else : ?>
                                    <small><?= __(NOT_AVAILABLE); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <hr />
    <?php $this->load->view('widgets/comment_widget.php'); ?>

</div>

<script>
    $(document).ready(function() {
        const shareButton = document.querySelector('.share-button');

        shareButton.addEventListener('click', event => {
            if (navigator.share) {
                navigator.share({
                        title: 'Share Job: ' + $('.job_title').html(),
                        url: base_url + 'job/detail/' + $('input[name=job_slug]').val(),
                    }).then(() => {
                        console.log('Thanks for sharing!');
                    })
                    .catch(console.error);
            } else {
                shareDialog.classList.add('is-open');
            }
        });
    })
</script>