<div class="dashboard-content posted-theme">
    <i class="fa fa-code-pull-request"></i>
    <h4>
        Meeting requests
    </h4>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $meeting_requests, $meeting_requests_count) ?></small>
        </div>
    </div>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th><?= __('Requestor') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($meeting_requests) && count($meeting_requests) > 0) : ?>
            <tbody>
                <?php foreach ($meeting_requests as $key => $meeting_request) : ?>
                    <tr>
                        <td>
                            <?= $meeting_request['meeting_request_signup_id'] ?>
                        </td>
                        <td>
                            <?= $meeting_request['meeting_request_current_status'] ?>
                        </td>
                        <td>
                            ...
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($meeting_requests_count) && ($meeting_requests_count) > 0) : ?>
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
                                                                                        echo l('dashboard/meeting/request/listing/') . '/' . $prev . '/' . $limit . '/' . $userid;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/meeting/request/listing/') . '/' . $i . '/' . $limit . '/' . $userid; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/meeting/request/listing/') . '/' . $next . '/' . $limit . '/' . $userid;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>
