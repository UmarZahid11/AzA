<div class="all-mail-area mt-4">

    <?php if (isset($message) && count($message) > 0) : ?>
        <?php foreach ($message as $key => $value) : ?>
            <div class="mail-tile">
                <div class="cont-mal">
                    <a class="me-2" target="_blank" href="<?= l('dashboard/home/message/details/') . $value['chat_id'] ?>">

                        <i class="fa-solid fa-envelope"></i>
                        <?php $chat_signup = ''; ?>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <?php $chat_signup .= $this->model_signup->signupName($value, false); ?>
                        <?php else : ?>
                            <?php $chat_signup .= $this->model_signup->signupName($value); ?>
                        <?php endif; ?>


                        <?php
                        if (isset($type)) {
                            switch ($type) {
                                case 'sent':
                                    echo 'Message to: ' . $chat_signup;
                                    break;
                                case 'inbox':
                                    echo 'Message from: ' . $chat_signup;
                                    break;
                                default:
                                    echo 'message';
                            }
                        }
                        ?>
                    </a>

                </div>
                <input type="hidden" name="type" value="<?= isset($type) ? $type : '' ?>" />
                <!-- <span></span> -->
                <small><?= timeago($value['chat_createdon']) ?></small>
                <button class="detelt-mail delete_chat" data-id="<?= $value['chat_id'] ?>"><i class="fa-solid fa-trash-can"></i></button>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <?= __('No new messages') ?>
    <?php endif; ?>

    <?php if (isset($message) && count($message) > 0) : ?>
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
                                                                                            echo l('dashboard/home/' . $type . '/') . $prev;
                                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('dashboard/home/' . $type . '/') . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                            echo '#';
                                                                                        } else {
                                                                                            echo l('dashboard/home/' . $type . '/') . $next;
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
        $('body').on('click', '.delete_chat', function() {
            var data = {
                id: $(this).data('id'),
                'type': $('input[name=type]').val()
            };
            var url = base_url + 'dashboard/custom/delete_chat'
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this chat!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
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
                                $(".all-mail-area").load(location.href + " .all-mail-area>*", "");
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