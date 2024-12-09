<div class="dashboard-content">
    <i class="fa-regular fa-briefcase"></i>
    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <span class="dropdown float-right">
            <button><i class="fa fa-bars"></i></button>
            <label>
                <input type="checkbox" />
                <ul>
                    <li><a href="<?= l('dashboard/job/post') ?>"><?= __('Post job') ?></a></li>
                    <li><a href="<?= l('dashboard/job/listing') . '/1/' . PER_PAGE . '/' . $this->userid ?>"><?= __('My posted jobs') ?></a></li>
                    <li><a href="<?= l('dashboard/job/listing') ?>"><?= __('All jobs') ?></a></li>
                    <li><a href="<?= l('dashboard/job/listing') . '/1/' . $limit . '/' . $organizationId . '/1' ?>"><?= __('Applied jobs') ?></a></li>
                </ul>
            </label>
        </span>
    <?php endif; ?>

    <h4><?= ($hasApplied ? __('Applied Jobs') : ($organizationId == $this->userid ? __('My Jobs') : __('All Jobs'))) . (isset($organization['signup_company_name']) ? ' for "' . $organization['signup_company_name'] . '"' : '') ?></h4>
    <hr />

    <?php if (($this->model_signup->hasPremiumPermission())) : ?>
        <a href="<?= l(TUTORIAL_PATH . APPLY_JOB_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Apply job Tutorial</a>
        <hr />
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $job, $job_count) ?></small>
        </div>
        <div class="offset-2 col-md-4">
            <div class="search-box-table">
                <i class="fa-regular fa-magnifying-glass"></i>
                <form class="searchForm" action="javascript:;">
                    <input type="text" class="form-control" name="job_title" placeholder="Search jobs" value="<?= isset($search) ? $search : '' ?>" />
                </form>
            </div>
        </div>
    </div>
    <hr />

    <div class="listing-job-wrapper">
        <?php if (isset($job) && count($job) > 0) : ?>
            <?php foreach ($job as $key => $value) : ?>
                <div class="job-prop-box">
                    <div class="expert-box">
                        <div class="u-box">
                                <a href="<?= l('dashboard/job/detail/') . ($value['job_slug'] ? $value['job_slug'] : '') ?>">
                                <h1 class="m-0 text-white"><?= $value['job_title'] ? ($value['job_title'][0] ? strtoupper(trim($value['job_title'])[0]) : '&#183;') : '&#183;' ?></h1>
                            </a>
                        </div>

                        <div>
                            <h4>
                                <a href="<?= l('dashboard/job/detail/') . ($value['job_slug'] ? $value['job_slug'] : '') ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['job_title']) ? $value['job_title'] : '..' ?>">
                                    <?= isset($value['job_title']) ? strip_string($value['job_title']) : '..' ?>
                                </a>
                            </h4>

                            <?php if(isset($value['job_type']) && $value['job_type']) : ?>
                                <div class="tag-ts">
                                    <i class="fa-solid fa-circle-check"></i>
                                    <?= isset($value['job_type']) ? $value['job_type'] : '..' ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                                <div class="badges">
                                    <span><i class="fa-light fa-briefcase" data-toggle="tooltip" data-bs-placement="top" title="Job category"></i>
                                        <?php if (isset($value['job_category']) && $value['job_category'] != NULL && @unserialize($value['job_category']) !== FALSE && is_array(unserialize($value['job_category']))) : ?>
                                            <?php foreach (unserialize($value['job_category']) as $ke => $val) : ?>
                                                <?php if($ke < 3) : ?>
                                                    <?= ($ke > 0 ? ', ' : '') . ($this->model_job_category->find_by_pk($val)['job_category_name'] ?? '') ?>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            ...
                                        <?php endif; ?>
                                    </span>
                                    <span><i class="fa-light fa-location-dot" data-toggle="tooltip" data-bs-placement="top" title="Job location"></i>
                                        <?php if (isset($value['job_location']) && $value['job_location']) : ?>
                                            <a href="https://maps.google.com/?q=<?= $value['job_location'] ?>"> <?= explode(',', $value['job_location'])[0] ?? 'Na' ?></a>
                                        <?php else : ?>
                                            ...
                                        <?php endif; ?>
                                    </span>
                                    <span>
                                        <i class="fa-light fa-circle-dollar-to-slot" data-toggle="tooltip" data-bs-placement="top" title="Job budget"></i>
                                        <?= (isset($value['job_salary_lower']) ? price($value['job_salary_lower']) : price(0)) . 
                                            (isset($value['job_salary_upper']) && $value['job_salary_upper'] ? (' - ' . price($value['job_salary_upper']) . ((isset($value['job_salary_interval']) ? ' / ' . $value['job_salary_interval'] : ''))) : (((isset($value['job_salary_interval']) && $value['job_salary_interval']) ? ' / ' . $value['job_salary_interval'] : ''))) ?>
                                    </span>
                                </div>

                            <?php endif; ?>

                            <div class="d-flex">
                                <?php $job_tags = isset($value['job_tags']) ? explode(',', $value['job_tags']) : array(); ?>

                                <?php foreach ($job_tags as $ke1 => $val1) : ?>
                                    <div class="specify <?= $ke1 % 2 == 0 ? 'yll' : '' ?>"><?= $val1 ?></div>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    </div>
                    <div class="action-job">
                        <?php if ($value['job_userid'] == $this->userid) : ?>
                            <button class="delete_job" data-id="<?= $value['job_id'] ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Delete this job?') ?>"><i class="fa-regular fa-xmark"></i></button>
                            <a href="<?= l('dashboard/job/post/') . $value['job_slug'] . '/edit' ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Edit this job') ?>"><i class="fa-regular fa-pen-to-square"></i></a>
                            <?php if($value['job_subscription_id']) : ?>
                                <?php $order = $this->model_order->find_one(['where' => ['order_stripe_transaction_id' => $value['job_subscription_id'], 'order_user_id' => $this->userid]]); ?>
                                <?php if ($order) : ?>
                                    <a target="_blank" href="<?= l('dashboard/order/detail/') . JWT::encode($order['order_id']); ?>" data-toggle="tooltip" data-bs-placement="left" title="<?= __('View your subscription detail for this job.') ?>"><i class="fa-regular fa-file-invoice"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->model_job_application->hasSendApplication($this->userid, $value['job_id'])) : ?>
                            <button class="withdraw_application" data-id="<?= $value['job_id'] ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Remove job request.') ?>"><i class="fa fa-remove" aria-hidden="true"></i></button>
                            <a href="<?= l('dashboard/meeting/listing/') . JWT::encode($this->model_job_application->hasSendApplication($this->userid, $value['job_id'], TRUE)['job_application_id']); ?>" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View scheduled meetings for this job.") ?>"><i class="fa-regular fa-desktop"></i></a>
                            <a href="<?= l('dashboard/application/detail/') . JWT::encode($this->model_job_application->hasSendApplication($this->userid, $value['job_id'], TRUE)['job_application_id']) . '/' . $value['job_id'] ?>" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("See application details.") ?>"><i class="fa-regular fa-file"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <small><?= ($hasApplied ? __('All applied jobs will be shown here.') : __('All posted jobs will be shown here.')); ?></small>
        <?php endif; ?>

        <?php if (isset($job) && count($job) > 0) : ?>
            <div class="row">
                <div class="col-lg-12">

                    <nav aria-label="Page navigation example mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                                echo '#';
                                                                                            } else {
                                                                                                echo l('dashboard/job/listing/') . $prev . '/' . $limit . '/' . $organizationId . '/' . ($hasApplied ? 1 : 0) . '/' . $search;
                                                                                            } ?>"><i class="far fa-chevron-left"></i></a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) {
                                                            echo 'active';
                                                        } ?>">
                                    <a class="page-link" href="<?= l('dashboard/job/listing/') . $i . '/' . $limit . '/' . $organizationId . '/' . ($hasApplied ? 1 : 0) . '/' . $search; ?>"> <?= $i; ?> </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if ($page >= $totalPages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                                echo '#';
                                                                                            } else {
                                                                                                echo l('dashboard/job/listing/') . $next . '/' . $limit . '/' . $organizationId . '/' . ($hasApplied ? 1 : 0) . '/' . $search;
                                                                                            } ?>"><i class="far fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('click', '.withdraw_application', function() {
            var data = {
                id: $(this).data('id'),
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            var url = base_url + 'dashboard/application/delete'
            swal({
                title: "Are you sure?",
                text: "You are about to delete this job application!",
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
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", function(){
                                   $('[data-toggle="tooltip"]').tooltip({
                                      html: true,
                                   })
                                });
                            } else {
                                swal("", response.txt, "error");
                            }
            		    }
        		    )
                    
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })

        $('body').on('submit', '.searchForm', function() {
            // if($('input[name=job_title]').val() != '') {
            location.href = base_url + 'dashboard/job/listing' + '/' + '<?= $page ?>' + '/' + '<?= $limit ?>' + '/' + '<?= $organizationId ?>' + '/' + '<?= $hasApplied ? 1 : 0 ?>' + '/' + $('input[name=job_title]').val();
            // }
        })
    })
</script>