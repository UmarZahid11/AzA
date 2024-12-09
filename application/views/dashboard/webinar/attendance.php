<div class="dashboard-content posted-theme">
    <i class="fa fa-desktop"></i>
    <h4><?= __('Webinars Attended') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $webinar_attendance, $webinar_attendance_count) ?></small>
        </div>
    </div>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Topic') ?></th>
                <th class="col-2"><?= __('Agenda') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Start time') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($webinar_attendance) && count($webinar_attendance) > 0) : ?>
            <tbody>
                <?php foreach ($webinar_attendance as $key => $value) : ?>
                    <tr>
                        <td>
                            <p><?= isset($value['webinar_topic']) ? $value['webinar_topic'] : 'Not Available' ?></p>
                        </td>
                        <td>
                            <p data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['webinar_agenda']) ? ($value['webinar_agenda']) : 'Not Available' ?>"><?= isset($value['webinar_agenda']) ? strip_string($value['webinar_agenda'], 30) : 'Not Available' ?></p>
                        </td>
                        <td>
                            <?php switch ($value['webinar_current_status']) {
                                case ZOOM_WEBINAR_PENDING:
                                    echo 'Pending';
                                    break;
                                case ZOOM_WEBINAR_STARTED:
                                    echo 'Started';
                                    break;
                                case ZOOM_WEBINAR_ENDED:
                                    echo 'Ended';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <p><?= isset($value['webinar_start_time']) && validateDate($value['webinar_start_time'], 'Y-m-d\TH:i') ? date('d M, Y h:i a', strtotime($value['webinar_start_time'])) : 'Not Available' ?></p>
                        </td>
                        <td>
                            <a href="<?= l('dashboard/webinar/detail/') . JWT::encode($value['webinar_id'])  ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View webinar details.") ?>" data-id="<?= $value['webinar_id'] ?>"><i class="fa-regular fa-file-text"></i></a>
                            <?php if (isset($value['webinar_start_url']) && $this->model_signup->hasPremiumPermission() && $this->userid == $value['webinar_userid']) : ?>
                                <?php if ($value['webinar_current_status'] == ZOOM_WEBINAR_PENDING) : ?>
                                    <a href="<?= l('dashboard/webinar/save/update/' .  JWT::encode($value['webinar_id'])) ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit webinar.") ?>" data-id="<?= $value['webinar_id'] ?>"><i class="fa-regular fa-pencil"></i></a>
                                    <a href="<?= isset($value['webinar_start_url']) ? $value['webinar_start_url'] : '#' ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Start webinar.") ?>" data-id="<?= $value['webinar_id'] ?>"><i class="fa-regular fa-link"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($value['webinar_join_url']) && $this->model_signup->hasPremiumPermission() && $this->userid !== (int) $value['webinar_userid']) : ?>
                                <?php if ($value['webinar_current_status'] == ZOOM_WEBINAR_STARTED) : ?>
                                    <a href="<?= isset($value['webinar_join_url']) ? $value['webinar_join_url'] : '#' ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Join webinar.") ?>" data-id="<?= $value['webinar_id'] ?>"><i class="fa-regular fa-link"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($value['webinar_start_url']) && $this->model_signup->hasPremiumPermission() && $this->userid == $value['webinar_userid']) : ?>
                                <button class="actns delete_webinar" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Delete webinar.") ?>" data-id="<?= $value['webinar_id'] ?>"><i class="fa-regular fa-trash-can"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <?= __('No attended webinars available.') ?>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($webinar_attendance_count) && ($webinar_attendance_count) > 0) : ?>
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
                                                                                        echo l('dashboard/webinar/listing/') . $prev;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/webinar/listing/') . $i; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/webinar/listing/') . $next;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_webinar', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this webinar!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).then((isConfirm) => {
                if (isConfirm) {

                    var data = {
                        id: $(this).data('id')
                    }
                    var url = base_url + 'dashboard/webinar/delete';
                    
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
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                            } else {
                                swal("Error", response.txt, "error").then(() => {
                                    if (response.url != undefined && response.url != '') {
                                        location.href = response.url;
                                    }
                                });
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