<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <span class="dropdown float-right">
            <button><i class="fa fa-bars"></i></button>
            <label>
                <input type="checkbox" />
                <ul>
                    <li>
                        <a href="<?= l('dashboard/coaching') ?>" data-toggle="tooltip" title="View all coachings in calendar view."><i class="fa fa-eye"></i> See calendar view</a>
                    </li>
                </ul>
            </label>
        </span>
    </div>
    <i class="fa fa-desktop"></i>
    <h4><?= 'Coaching' ?></h4>
    <hr />
    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <div class="row">
            <div class="col-md-6">
                <small class="line-height-2"><?= record_detail($offset, $coachings, $coachings_count) ?></small>
            </div>
            <div class="offset-2 col-md-4">
                <div class="search-box-table">
                    <i class="fa-regular fa-magnifying-glass"></i>
                    <form class="searchForm" action="javascript:;">
                        <input type="text" class="form-control" name="coaching_search" placeholder="Search" value="<?= isset($search) ? $search : '' ?>" />
                    </form>
                </div>
            </div>
        </div>

        <hr />
    <?php endif; ?>

    <table class="style-1">
        <thead>
            <tr>
                <th><?= __('Name') ?></th>
                <th><?= __('Start time') ?></th>
                <th><?= __('Duration (minutes)') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($coachings) && count($coachings) > 0) : ?>
            <tbody>
                <?php foreach ($coachings as $key => $coaching) : ?>
                    <tr>
                        <td>
                            <a href="<?= l('dashboard/coaching/detail/' . JWT::encode($coaching['coaching_id'])); ?>">
                                <?= isset($coaching['coaching_title']) && $coaching['coaching_title'] ? $coaching['coaching_title'] : NA ?>
                            </a>
                        </td>
                        <td>
                            <?= isset($coaching['coaching_start_time']) && isValidDate($coaching['coaching_start_time'], 'Y-m-d H:i:s') ? ($coaching['coaching_start_time']  . ' (' . date('d M, Y h:i a', strtotime($coaching['coaching_start_time'])) . ')') : NA ?>
                        </td>
                        <td>
                            <?= isset($coaching['coaching_duration']) && $coaching['coaching_duration'] ? $coaching['coaching_duration'] : NA ?>
                        </td>
                        <td>
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("View detail") ?>" href="<?= l('dashboard/coaching/detail/' . JWT::encode($coaching['coaching_id'])) ?>"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No coachings available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($coachings_count) && ($coachings_count) > 0) : ?>
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
                                                                                        echo l('dashboard/coaching/listing/') . $prev . '/' . $limit . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/coaching/listing/') . $i . '/' . $limit . '/' . $search; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/coaching/listing/') . $next . '/' . $limit . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('submit', '.searchForm', function() {
            location.href = base_url + 'dashboard/coaching/listing/' + '<?= $page ?>' + '/' + '<?= $limit ?>' + '/' + $('input[name=coaching_search]').val();
        })
    });
</script>