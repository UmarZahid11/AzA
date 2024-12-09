<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <div class="side-large-text"><?= $story_count ?></div>
    </div>
    <i class="fa-solid fa-file"></i>
    <h4><?= ($userid && $userid == $this->userid ? ('My' . ' ') : '') . __('Posted Stories') ?></h4>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $story, $story_count) ?></small>
        </div>
    </div>
    <hr/>
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-4"><?= __('Title') ?></th>
                <th><?= __('Author') ?></th>
                <!--<th><?= __('Approved') ?></th>-->
                <th><?= __('Posted on') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($story) && count($story) > 0) : ?>
            <tbody>
                <?php foreach ($story as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <?php if(isset($value['story_image'])): ?>
                                    <img class="w-25" src="<?= get_image($value['story_image_path'], $value['story_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php else: ?>
                                    <img class="w-25" src="<?= g('dashboard_images_root') ?>image-placeholder.png" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php endif; ?>
                                <div class="mx-3">
                                    <h6>
                                        <a href="<?= l('dashboard/story/detail/') . ($value['story_slug'] ? $value['story_slug'] : '') ?>" target="_blank">
                                            <?= isset($value['story_title']) ? $value['story_title'] : '..' ?>
                                        </a>
                                    </h6>
                                    <small data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['story_short_detail']) ? ($value['story_short_detail']) : '..' ?>" >
                                        <?= isset($value['story_short_detail']) ? (substr(($value['story_short_detail']), 0, 50) . ' ...') : '..' ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['story_author'] ? $value['story_author'] : NA) ?></span>
                        </td>
                        <!--<td>-->
                        <!--    <span class="stats"><?//= ($value['story_approved'] ? 'Yes' : 'No') ?></span>-->
                        <!--</td>-->
                        <td>
                            <p>
                                <?= (isset($value['story_createdon']) && (strtotime($value['story_createdon']) !== false)) ? date("M d, Y", strtotime($value['story_createdon'])) : 'Not Available' ?>
                            </p>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['story_status'] ? 'Active' : 'Inactive') ?></span>
                        </td>
                        <td>
                            <?php if($this->userid == $value['story_userid']): ?>
                                <a href="<?= l('dashboard/story/post/') . $value['story_slug'] . '/edit' ?>" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;
                                <a href="javascript:;" class="delete_story" data-id="<?= $value['story_id'] ?>"><i class="fa fa-trash-can"></i></a>
                            <?php else: ?>
                             ...
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td>
                        <?= __('Your posted stories will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($story) && count($story) > 0) : ?>
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
                                                                                        echo l('dashboard/story/listing/') . $prev;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/story/listing/') . $i; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/story/listing/') . $next;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>


<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_story', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this story!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        id: $(this).data('id')
                    }
                    var url = base_url + 'dashboard/story/delete'

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