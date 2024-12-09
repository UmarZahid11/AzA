<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <div class="side-large-text"><?= count($job_applications) ?></div>
    </div>
    <i class="fa fa-address-card-o"></i>
    <h4><?= __('Job applications') . (isset($job_details) && is_array($job_details) ? ' for "' . $job_details['job_title'] . '"' : '') ?></h4>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $job_applications, $job_applications_count) ?></small>
        </div>
        <div class="col-md-6">
            <?php if(!$existed_email): ?>
                <?= __('Link my account') ?>
            <?php endif; ?>
        </div>
    </div>
    <hr/>
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-4"><?= __('Applicant') ?></th>
                <th><?= __('Company') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($job_applications) && count($job_applications) > 0) : ?>
            <tbody>
                <?php foreach ($job_applications as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <a href="<?= l('dashboard/profile/detail/') . JWT::encode($value['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $value['signup_type'] ?>" target="_blank">
                                <?php else : ?>
                                    <a href="javascript:;">
                                <?php endif; ?>
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <img src="<?= get_user_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                <?php else : ?>
                                    <img src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                <?php endif; ?>
                                </a>

                                <div>
                                    <p>
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
                                        </a>

                                        <?php $data['connectionLevel'] = $this->model_signup_follow->connectionLevel($value['signup_id'], $this->userid); ?>
                                        <?php $this->load->view('widgets/connection_level', $data) ?>
                                    </p>
                                    <small><?= isset($value['signup_profession']) ? strip_string($value['signup_profession']) : '' ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p><?= isset($value['signup_company']) ? $value['signup_company'] : 'Not Available' ?></p>
                        </td>
                        <td>
                            <span class="stats">
                                <?php echo ucfirst($this->model_job_application->jobApplicationStatus($value['job_application_request_status'])); ?>
                            </span>
                        </td>
                        <td class="d-flex">
                            <a href="<?= l('dashboard/application/detail/') . JWT::encode($value['job_application_id']) . '/' . $value['job_id']  ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View application details.") ?>" data-id="<?= $value['job_application_id'] ?>"><i class="fa-regular fa-file"></i></a>
                            <a href="<?= l('dashboard/profile/testimonial/') . JWT::encode($value['signup_id']); ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View applicatnt testimonials.") ?>" data-id="<?= $value['job_application_id'] ?>"><i class="fa-regular fa-quote-left"></i></a>
                            <?php if($existed_email): ?>
                                <a href="<?= l('dashboard/meeting/listing/') . JWT::encode($value['job_application_id'])  ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View all meetings.") ?>" data-id="<?= $value['job_application_id'] ?>"><i class="fa-regular fa-server"></i></a>
                                <a href="<?= l('dashboard/meeting/save/' . CREATE . '/' . JWT::encode($value['job_application_id'])) ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Schedule a meeting.") ?>" data-id="<?= $value['job_application_id'] ?>"><i class="fa-regular fa-desktop"></i></a>
                            <?php endif; ?>

                            <button class="actns delete_job_application" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Delete job application.") ?>" data-id="<?= $value['job_application_id'] ?>"><i class="fa-regular fa-trash-can"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td>
                        <?= isset($value['job_title']) ? $value['job_title'] : 'Job' . ' ' . __('application will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($job_applications_count) && ($job_applications_count) > 0) : ?>
    <div class="row mt-4">
        <div class="col-lg-12">

            <nav aria-label="Page navigation example mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/application/listing/') . JWT::encode($job_details['job_id']) . '/' . $userid . '/' . $prev;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/application/listing/') . JWT::encode($job_details['job_id']) . '/' . $userid . '/' . $i; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/application/listing/') . JWT::encode($job_details['job_id']) . '/' . $userid . '/' . $next;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_job_application', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this job application!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).then((isConfirm) => {
                if (isConfirm) {

                    var data = {
                        id: $(this).data('id')
                    }
                    var url = base_url + 'dashboard/job/delete_application'

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
                                showLoader();
                            },
                            complete: function() {
                                hideLoader();
                            }
                        })
        			}).then(
        			    function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
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
    })
</script>