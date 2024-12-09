<div class="dashboard-content">
    <i class="fa-regular fa-comments"></i>
    <?php if ($this->model_signup->hasPremiumPermission() && $this->userid != $userid) : ?>
        <span class="dropdown float-right">
            <button><i class="fa fa-bars"></i></button>
            <label>
                <input type="checkbox" class="cursor-pointer" />
                <ul>
                    <li><a href="<?= l('dashboard/endorsement/save/' . JWT::encode($userid)) ?>"><?= __('Add endorsement') ?></a></li>
                </ul>
            </label>
        </span>
    <?php endif; ?>

    <h4>
        <?= $this->userid == $userid ? 'My' : ('"' . $this->model_signup->profileName($user) . '"') ?>
        <?= ($this->userid == $userid) ? ($to ? 'Received' : 'Sent') : ''; ?>
        Endorsements</h4>
    <hr />

    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $endorsements, $endorsements_count) ?></small>
        </div>
        <!-- <div class="offset-2 col-md-4">
            <div class="search-box-table">
                <i class="fa-regular fa-magnifying-glass"></i>
                <form class="searchForm" action="javascript:;">
                    <input type="text" class="form-control" name="job_title" placeholder="Search jobs" value="<?//= isset($search) ? $search : '' ?>" />
                </form>
            </div>
        </div> -->
    </div>
    <hr />

    <table class="style-1">
        <thead>
            <tr>
                <th>#</th>
                <th><?= $to ? 'From' : 'To' ?></th>
                <th class="col-4"><?= __('Description') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($endorsements) && count($endorsements) > 0) : ?>
            <tbody>
                <?php foreach ($endorsements as $key => $endorsement) : ?>
                    <tr>
                        <td><?= $endorsement['endorsement_id'] ?></td>
                        <td>
                            <a href="<?= l('dashboard/profile/detail/' . JWT::encode($endorsement['signup_id']) . '/' . $endorsement['signup_type']) ?>" target="_blank">
                                <?= $this->model_signup->profileName($endorsement) ?>
                            </a>
                        </td>
                        <td><?= $endorsement['endorsement_text'] ?></td>
                        <td>
                            <?php if($endorsement['endorsement_signup_id'] == $this->userid): ?>
                                <a href="<?= l('dashboard/endorsement/save/' . JWT::encode($endorsement['endorsement_to']) . '/' . $endorsement['endorsement_id']) ?>" target="_blank">Edit</a>
                            <?php else: ?>
                                ...
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No endorsement available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($endorsement_count) && ($endorsement_count) > 0) : ?>
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
                                                                                        echo l('dashboard/endorsement/listing/') . $prev . '/' . $limit . '/' . JWT::encode($userid) . '/' . $to;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/endorsement/listing/') . $i . '/' . $limit . '/' . JWT::encode($userid) . '/' . $to; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/endorsement/listing/') . $next . '/' . $limit . '/' . JWT::encode($userid) . '/' . $to;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>
