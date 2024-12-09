<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <?php if ($this->model_signup->hasPremiumPermission() && $this->userid != $applicant_id) : ?>
            <div class="">
                <!-- side-large-text -->
                <a class="btn btn-custom" href="<?= l('dashboard/meeting/save/create/') . JWT::encode($meeting_reference_id) . '/0/' . $meeting_reference_type ?>">Create Meeting</a>
            </div>
        <?php endif; ?>
    </div>
    <i class="fa fa-desktop"></i>
    <h4>
        <?php
        switch($meeting_reference_type) {
            case MEETING_REFERENCE_PRODUCT:
                echo __('Meetings with requestor ') . '"' . $this->model_signup->profileName($meeting_reference, FALSE) . '"';
                break;
            case MEETING_REFERENCE_APPLICATION:
                echo __('Meetings with applicant ') . '"' . $this->model_signup->profileName($meeting_reference, FALSE) . '"';
                break;
        }
        ?>
    </h4>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $meetings, $meetings_count) ?></small>
        </div>
    </div>
    <hr/>

    <table class="style-1">
        <thead>
            <tr>
                <th><?= $meeting_reference_type == MEETING_REFERENCE_PRODUCT ? __('Requestor') : __('Applicant') ?></th>
                <th class="col-2"><?= __('Topic') ?></th>
                <th class="col-2"><?= __('Agenda') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Start time') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($meetings) && count($meetings) > 0) : ?>
            <tbody>
                <?php foreach ($meetings as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <?php
                                    switch($meeting_reference_type) {
                                        case MEETING_REFERENCE_PRODUCT:
                                            $applicant_id = $value['product_request_signup_id'];
                                            break;
                                        case MEETING_REFERENCE_APPLICATION:
                                            $applicant_id = $value['job_application_signup_id'];
                                            break;
                                    }
                                ?>
                                <?php $applicant = $this->model_signup->find_by_pk($applicant_id); ?>

                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <a href="<?= l('dashboard/profile/detail/') . JWT::encode($applicant['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $applicant['signup_type'] ?>" target="_blank">
                                    <?php else : ?>
                                        <a href="javascript:;">
                                        <?php endif; ?>

                                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                            <img src="<?= get_user_image($applicant['signup_logo_image_path'], $applicant['signup_logo_image']) ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                        <?php else : ?>
                                            <img src="<?= g('images_root') . 'logo.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                        <?php endif; ?>
                                        </a>

                                        <div>
                                            <h6>
                                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                    <a class="font-12" href="<?= l('dashboard/profile/detail/') . JWT::encode($applicant['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $applicant['signup_type'] ?>" target="_blank">
                                                    <?php else : ?>
                                                        <a class="font-12" href="javascript:;">
                                                        <?php endif; ?>
                                                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                                            <?= $this->model_signup->listingName($applicant, false) ?>
                                                        <?php else : ?>
                                                            <?= $this->model_signup->listingName($applicant) ?>
                                                        <?php endif; ?>
                                                        </a>

                                            </h6>
                                            <small data-toggle="tooltip" data-bs-placement="right" title="<?= isset($applicant['signup_profession']) ? ($applicant['signup_profession']) : 'Not Available' ?>"><?= isset($applicant['signup_profession']) ? strip_string($applicant['signup_profession'], 30) : '' ?></small>
                                        </div>
                            </div>
                        </td>
                        <td>
                            <p><?= isset($value['meeting_topic']) ? $value['meeting_topic'] : 'Not Available' ?></p>
                        </td>
                        <td>
                            <p data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['meeting_agenda']) ? ($value['meeting_agenda']) : 'Not Available' ?>"><?= isset($value['meeting_agenda']) ? strip_string($value['meeting_agenda'], 30) : 'Not Available' ?></p>
                        </td>
                        <td>
                            <?php switch($value['meeting_current_status']) {
                                case ZOOM_MEETING_PENDING:
                                    echo 'Pending';
                                    break;
                                case ZOOM_MEETING_STARTED:
                                    echo 'Started';
                                    break;
                                case ZOOM_MEETING_ENDED:
                                    echo 'Ended';
                                    break;
                            }
                            ?>
                        </td>
                        <td>
                            <p><?= isset($value['meeting_start_time']) && validateDate($value['meeting_start_time'], 'Y-m-d\TH:i') ? date('d M, Y h:i a', strtotime($value['meeting_start_time'])) : 'Not Available' ?></p>
                        </td>
                        <td>
                            <a href="<?= l('dashboard/meeting/detail/') . JWT::encode($value['meeting_id'])  ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("View meeting details.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-file-text"></i></a>
                            <?php if (isset($value['meeting_start_url']) && $this->model_signup->hasPremiumPermission() && $this->userid == $value['meeting_signup_id']) : ?>
                                <?php if($value['meeting_current_status'] == ZOOM_MEETING_PENDING): ?>

                                    <?php if($value['meeting_reference_type'] == MEETING_REFERENCE_APPLICATION): ?>
                                        <a href="<?= l('dashboard/meeting/save/update/') . JWT::encode($meeting_reference_id) . '/' . $value['meeting_id'] . '/' . $value['meeting_reference_type'] ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit meeting.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-pencil"></i></a>
                                    <?php elseif($value['meeting_reference_type'] == MEETING_REFERENCE_PRODUCT): ?>
                                        <a href="<?= l('dashboard/product/handle/') . JWT::encode($meeting_reference_id) ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit meeting.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-pencil"></i></a>
                                    <?php endif; ?>

                                    <a href="<?= isset($value['meeting_start_url']) ? $value['meeting_start_url'] : '#' ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Start meeting.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-link"></i></a>

                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($value['meeting_join_url']) && $this->model_signup->hasPremiumPermission() && $this->userid == $value['signup_id']) : ?>
                                <?php if($value['meeting_current_status'] == ZOOM_MEETING_STARTED): ?>
                                    <a href="<?= isset($value['meeting_join_url']) ? $value['meeting_join_url'] : '#' ?>" target="_blank" class="actns" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Join meeting.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-link"></i></a>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if (isset($value['meeting_start_url']) && $this->model_signup->hasPremiumPermission() && $this->userid == $value['meeting_signup_id']) : ?>
                                <button class="actns delete_meeting" data-toggle="tooltip" data-bs-placement="top" title="<?= __("Delete meeting.") ?>" data-id="<?= $value['meeting_id'] ?>"><i class="fa-regular fa-trash-can"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No meetings available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($meetings_count) && ($meetings_count) > 0) : ?>
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
                                                                                        echo l('dashboard/meeting/listing/') . JWT::encode($meeting_reference_id) . '/' . $prev . '/' . $limit . '/' . $meeting_reference_type;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/meeting/listing/') . JWT::encode($meeting_reference_id) . '/' . $i . '/' . $limit . '/' . $meeting_reference_type; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/meeting/listing/') . JWT::encode($meeting_reference_id) . '/' . $next . '/' . $limit . '/' . $meeting_reference_type;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_meeting', function() {

            var data = {
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/meeting/delete';

            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this meeting!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).then((isConfirm) => {
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