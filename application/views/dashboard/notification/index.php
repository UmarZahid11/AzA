<div class="dashboard-content">
    <i class="fa-regular fa-bell"></i>
    <h3 class="mb-0"><?= __('Notifications') ?></h3>
    <p class="mb-4"><?= __('History of all your recieved notifications.') ?></p>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $notification, $notification_count) ?></small>
        </div>
    </div>
    <hr />
    <div class="all-mail-area mt-4">
        <?php if (isset($notification) && count($notification) > 0) : ?>
            <?php foreach ($notification as $key => $value) : ?>
                <div class="mail-tile">
                    <div class="cont-mal">
                        <?php if (isset($value['notification_reference_id'])) : ?>
                            <a href="<?= $this->model_notification->notificationRedirection($value) ?>">
                        <?php else : ?>
                            <a href="javascript:;">
                        <?php endif; ?>

                        <i class="fa-solid fa-bell"></i>
                        <?php
                        if (isset($value['signup_type']) && $value['signup_id']) {
                            $from_signup = "";
                        }
                        ?>
                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <?php $from_signup .= $this->model_signup->profileName($value, false); ?>
                        <?php else : ?>
                            <?php $from_signup .= $this->model_signup->profileName($value); ?>
                        <?php endif; ?>

                        <?= isset($value['notification_comment']) ? $from_signup . ' ' . $value['notification_comment'] : '' ?>
                        </a>
                    </div>
                    <small><?= timeago($value['notification_createdon']) ?></small>
                    <button class="detelt-mail delete_notification" data-id="<?= $value['notification_id'] ?>"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <?= __('No new notifications') ?>
        <?php endif; ?>
    </div>

    <?php if (isset($notification_count) && ($notification_count) > 0) : ?>
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
                                                                                            echo l('dashboard/home/notification/') . $prev;
                                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('dashboard/home/notification/') . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                            echo '#';
                                                                                        } else {
                                                                                            echo l('dashboard/home/notification/') . $next;
                                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_notification', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this notification!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {

                    var data = {
                        id: $(this).data('id')
                    }
                    var url = base_url + 'dashboard/custom/delete_notification'
                    
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