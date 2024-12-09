<div class="dashboard-content posted-theme">
    <i class="fa-solid fa-shopping-cart"></i>
    <h4><?= __('Jobs milestone payment') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $job_milestone_payment, $job_milestone_payment_count) ?></small>
        </div>
    </div>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Job') ?></th>
                <th><?= __('Company') ?></th>
                <th><?= __('Milestone') ?></th>
                <th><?= __('Amount') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Payment status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($job_milestone_payment) && count($job_milestone_payment) > 0) : ?>
            <tbody>
                <?php foreach ($job_milestone_payment as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <div>
                                    <p>
                                        <a href="<?= l('dashboard/job/detail/') . ($value['job_slug'] ? $value['job_slug'] : '') ?>" target="_blank">
                                            <?= isset($value['job_title']) ? $value['job_title'] : '..' ?>
                                        </a>
                                    </p>
                                    <small><?= isset($value['job_type']) ? $value['job_type'] : '..' ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p><?= isset($this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name']) ? $this->model_signup_company->find_one_active(array('where' => array('signup_company_signup_id' => $value['job_userid'])))['signup_company_name'] : 'Not Available.' ?></p>
                        </td>
                        <td>
                            <p><?= $value['job_milestone_title'] ?></p>
                        </td>
                        <td>
                            <p><?= price($value['job_milestone_amount']) ?></p>
                        </td>
                        <td>
                            <p>
                                <?php
                                switch ($value['job_milestone_completion_status']) {
                                    case MILESTONE_COMPLETE:
                                        echo 'Completed';
                                        break;
                                    case MILESTONE_REVISION:
                                        echo 'Under revision';
                                        break;
                                    case MILESTONE_INCOMPLETE:
                                        echo 'Incomplete';
                                        break;
                                    case MILESTONE_PROCESSING:
                                        echo 'Processing';
                                        break;
                                    default:
                                        echo 'Pending';
                                }
                                ?>
                            </p>
                        </td>
                        <td>
                            <p>
                                <?php
                                switch ($value['job_milestone_payment_money_position_status']) {
                                    case MILESTONE_PAYMENT_PENDING:
                                        echo 'Pending';
                                        break;
                                    case MILESTONE_PAYMENT_PAID:
                                        echo 'Paid';
                                        break;
                                    case MILESTONE_PAYMENT_ESCROW:
                                        echo 'In escrow';
                                        break;
                                    default:
                                        echo 'Pending';
                                }
                                ?>
                            </p>
                        </td>
                        <td>
                            <a class="actns" href="<?= l('dashboard/application/detail/') . JWT::encode($value['job_milestone_application_id']) . '/' . ($value['job_id']) ?>" data-toggle="tooltip" data-bs-placement="top" title="Job application details">
                                <i class="fa-regular fa-address-card"></i>
                            </a>
                            <?php if($value['job_milestone_payment_money_position_status'] == MILESTONE_PAYMENT_ESCROW): ?>
                                <a class="actns" href="javascript:;" id="refund" data-id="" data-toggle="tooltip" data-bs-placement="top" title="Refund this payment">
                                    <i class="fa fa-undo"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td>
                        <?= __('All milestone payments will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($job_milestone_payment) && count($job_milestone_payment) > 0) : ?>
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
                                                                                        echo l('dashboard/home/job_milestone_payment/') . $prev . '/' . $limit;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/home/job_milestone_payment/') . $i . '/' . $limit; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/home/job_milestone_payment/') . $next . '/' . $limit;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>