<div class="dashboard-content expire-theme">
    <i class="fa-solid fa-tag"></i>
    <h4><?= __('Expired Jobs') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $job, $job_count) ?></small>
        </div>
    </div>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Title') ?></th>
                <th><?= __('Company') ?></th>
                <th><?= __('Created & Expired at') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($job) && count($job) > 0) : ?>
            <tbody>
                <?php foreach ($job as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <div>
                                    <p>
                                        <a href="<?= l('dashboard/job/detail/') . ($value['job_slug'] ? $value['job_slug'] : '') ?>" target="_blank">
                                            <?= isset($value['job_title']) ? $value['job_title'] : '..' ?>
                                        </a>
                                    </p>
                                    <p><?= isset($value['job_type']) ? $value['job_type'] : '..' ?></p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p><?= isset($this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name']) ? $this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name'] : 'Not Available.' ?></p>
                        </td>
                        <td>
                            <p><?= (isset($value['job_createdon']) && (strtotime($value['job_createdon']) !== false)) ? date("M d, Y", strtotime($value['job_createdon'])) : 'Not Available' ?> <br> <small><?= (isset($value['job_expiry']) && (strtotime($value['job_expiry']) !== false)) ? date("M d, Y", strtotime($value['job_expiry'])) : 'Not Available' ?></small></p>
                        </td>
                        <td>
                            <span class="stats"><?= date("Y-m-d") > $value['job_expiry'] ? 'Expired' : 'Active' ?></span>
                        </td>
                        <td>
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("See associates job applications.") ?>" href="<?= l('dashboard/application/listing/') . JWT::encode($value['job_id']); ?>"><i class="fa fa-address-card-o"></i></a>&nbsp;
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit this job.") ?>" href="<?= l('dashboard/job/post/') . $value['job_slug'] . '/edit' ?>" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Delete this job.") ?>" class="delete_job" href="javascript:;" data-id="<?= $value['job_id'] ?>"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td>
                        <?= __('Your expired jobs will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($job) && count($job) > 0) : ?>
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
                                                                                        echo l('dashboard/job/expired/') . $prev . '/' . $limit . '/' . $organizationId;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/job/expired/') . $i . '/' . $limit . '/' . $organizationId; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/job/expired/') . $next . '/' . $limit . '/' . $organizationId;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>
