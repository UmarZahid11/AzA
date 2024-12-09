<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <div class="side-large-text"><?= $blog_count ?></div>
    </div>
    <i class="fa-solid fa-file"></i>
    <h4><?= ($userid && $userid == $this->userid ? ('My' . ' ') : '') . __('Posted blogs') ?></h4>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $blog, $blog_count) ?></small>
        </div>
        <div class="offset-2 col-md-4">
            <div class="search-box-table">
                <i class="fa-regular fa-magnifying-glass"></i>
                <form class="searchForm">
                    <input type="text" class="form-control" name="search" placeholder="Search blogs" value="<?= isset($search) ? $search : '' ?>" />
                </form>
            </div>
        </div>
    </div>
    <hr/>
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-4"><?= __('Title') ?></th>
                <th><?= __('Author') ?></th>
                <th><?= __('Approved') ?></th>
                <th><?= __('Posted on') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($blog) && count($blog) > 0) : ?>
            <tbody>
                <?php foreach ($blog as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <?php if(isset($value['blog_image'])): ?>
                                    <img class="w-25" src="<?= get_image($value['blog_image_path'], $value['blog_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php else: ?>
                                    <img class="w-25" src="<?= g('dashboard_images_root') ?>image-placeholder.png" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php endif; ?>
                                <div class="mx-3">
                                    <h6>
                                        <a href="<?= l('dashboard/blog/detail/') . ($value['blog_slug'] ? $value['blog_slug'] : '') ?>" target="_blank">
                                            <?= isset($value['blog_title']) ? $value['blog_title'] : '..' ?>
                                        </a>
                                    </h6>
                                    <small data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['blog_short_detail']) ? ($value['blog_short_detail']) : '..' ?>" >
                                        <?= isset($value['blog_short_detail']) ? (substr(($value['blog_short_detail']), 0, 50) . ' ...') : '..' ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['blog_author'] ? $value['blog_author'] : NA) ?></span>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['blog_approved'] ? 'Yes' : 'No') ?></span>
                        </td>
                        <td>
                            <p>
                                <?= (isset($value['blog_createdon']) && (strtotime($value['blog_createdon']) !== false)) ? date("M d, Y", strtotime($value['blog_createdon'])) : 'Not Available' ?>
                            </p>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['blog_status'] ? 'Active' : 'Inactive') ?></span>
                        </td>
                        <td>
                            <?php if($this->userid == $value['blog_userid']): ?>
                                <a class="" href="<?= l('dashboard/blog/post/') . $value['blog_slug'] . '/edit' ?>" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;
                                <a href="javascript:;" class="delete_blog" data-id="<?= $value['blog_id'] ?>"><i class="fa fa-trash-can"></i></a>
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
                        <?= __('Your posted blogs will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($blog) && count($blog) > 0) : ?>
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
                                                                                        echo l('dashboard/blog/listing/') . $prev . '?search=' . (isset($search) ? $search : '');
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/blog/listing/') . $i . '?search=' . (isset($search) ? $search : ''); ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/blog/listing/') . $next . '?search=' . (isset($search) ? $search : '');
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>


<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_blog', function() {
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this blog!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {

                    var data = {
                        _token: $('meta[name=csrf-token]').attr("content"),
                        id: $(this).data('id')
                    }
                    var url = base_url + 'dashboard/blog/delete'

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