<div class="dashboard-content posted-theme">
    <i class="fa-solid fa-briefcase"></i>
    <h4><?= __('Posted Jobs') ?></h4>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $job, $job_count) ?></small>
        </div>
        <div class="offset-2 col-md-4">
            <div class="search-box-table">
                <i class="fa-regular fa-magnifying-glass"></i>
                <form class="searchForm" action="javascript:;">
                    <input type="text" class="form-control" name="job_search" placeholder="Search your posted jobs" value="<?= isset($search) ? $search : '' ?>" />
                </form>
            </div>
        </div>
    </div>
    <hr>
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Title') ?></th>
                <th><?= __('Company') ?></th>
                <th><?= __('Created & Expired at') ?></th>
                <!--<th><?//= __('Submission deadline') ?></th>-->
                <th><?= __('Job expiry') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Completion Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($job) && count($job) > 0) : ?>
            <tbody>
                <?php foreach ($job as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <!-- <img src="<?= g('dashboard_images_root') ?>exp1.jpg" alt=""> -->
                                <div>
                                    <p>
                                    <a href="<?= l('dashboard/job/detail/') . ($value['job_slug'] ? $value['job_slug'] : '') ?>" target="_blank" data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['job_title']) ? $value['job_title'] : '..' ?>">
                                        <?= isset($value['job_title']) ? strip_string($value['job_title']) : '..' ?>
                                    </a>
                                    </p>
                                    <small><?= isset($value['job_type']) ? $value['job_type'] : '..' ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?= isset($this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name']) ? $this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name'] : 'Not Available.' ?>
                        </td>
                        <td>
                            <?= (isset($value['job_createdon']) && (validateDate($value['job_createdon'], 'Y-m-d H:i:s'))) ? date("M d, Y", strtotime($value['job_createdon'])) : 'Not Available' ?> <br /> <small><?= (isset($value['job_expiry']) && (validateDate($value['job_expiry'], 'Y-m-d H:i:s'))) ? date("M d, Y", strtotime($value['job_expiry'])) : 'Not Available' ?></small>
                        </td>
                        <!--<td>-->
                            <!--<?//= (isset($value['job_submission_deadline']) && (validateDate($value['job_submission_deadline'], 'Y-m-d H:i:s'))) ? (strtotime(date('Y-m-d H:i:s')) > strtotime($value['job_submission_deadline']) ? '<i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="Submissions for this job are closed!" ></i>&nbsp;' : '') . date("M d, Y", strtotime($value['job_submission_deadline'])) : 'Not Available' ?>-->
                        <!--</td>-->
                        <td>
                            <small><?= (isset($value['job_subscription_expiry']) && (validateDate($value['job_subscription_expiry'], 'Y-m-d H:i:s'))) ? date("M d, Y h:i a", strtotime($value['job_subscription_expiry'])) : 'Not Available' ?></small>
                        </td>
                        <td>
                            <span class="stats <?= date("Y-m-d") > $value['job_expiry'] ? 'expired-stats' : '' ?>">
                                <?= date("Y-m-d") > $value['job_expiry'] ? 'Expired' : ($value['job_status'] ? 'Active' : 'Inactive') ?>
                            </span>
                        </td>
                        <td>
                            <?= ($value['job_completion_status'] ? 'Completed' : 'In progress') ?>
                        </td>
                        <td>
                            <a class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("See job applicants.") ?>" target="_blank" href="<?= l('dashboard/application/listing/') . JWT::encode($value['job_id']) ?>"><i class="fa fa-address-card-o"></i></a>
                            <a class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit this job.") ?>" href="<?= l('dashboard/job/post/') . $value['job_slug'] . '/edit' ?>" target="_blank"><i class="fa fa-edit"></i></a>
                            <a class="actns delete_job" title="<?= __("Delete this job.") ?>" href="javascript:;" data-id="<?= $value['job_id'] ?>"><i class="fa fa-trash"></i></a>
                            <!--data-toggle="tooltip" data-bs-placement="top" -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td>
                        <small><?= __('Your posted jobs will be shown here.') ?></small>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($job_count) && $job_count > 0) : ?>
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
                                                                                        echo l('dashboard/job/posted/') . $prev . '/' . $limit . '/' . $organizationId . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/job/posted/') . $i . '/' . $limit . '/' . $organizationId . '/' . $search; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/job/posted/') . $next . '/' . $limit . '/' . $organizationId . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('submit', '.searchForm', function(){
            location.href = base_url + 'dashboard/job/posted' + '/' + '<?= $page ?>' + '/' + '<?= $limit ?>' + '/' + '<?= $organizationId ?>' + '/' + $('input[name=job_search]').val();
        })
    })
</script>