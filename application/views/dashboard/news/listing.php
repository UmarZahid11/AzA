<div class="dashboard-content posted-theme">
    <div style="float:right;">
        <div class="side-large-text"><?= $news_count ?></div>
    </div>
    <i class="fa-solid fa-newspaper-o"></i>
    <h4><?= ($userid && $userid == $this->userid ? ('My' . ' ') : '') . __('Posted News') ?></h4>
    <hr/>
    <div class="row">
        <div class="col-md-6">
            <small class="line-height-2"><?= record_detail($offset, $news, $news_count) ?></small>
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
        <?php if (isset($news) && count($news) > 0) : ?>
            <tbody>
                <?php foreach ($news as $key => $value) : ?>
                    <tr>
                        <td>
                            <div class="job-title-bc">
                                <?php if(isset($value['news_attachment'])): ?>
                                    <img class="w-25" src="<?= get_image($value['news_attachment_path'], $value['news_attachment']) ?>" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php else: ?>
                                    <img class="w-25" src="<?= g('images_root') ?>image-placeholder.png" alt="" onerror="this.onerror=null;this.src='<?=g('images_root').'not-found.jpg'?>';" />
                                <?php endif; ?>
                                <div class="mx-3">
                                    <h6>
                                        <a href="<?= l('dashboard/news/detail/') . ($value['news_slug'] ? $value['news_slug'] : '') ?>" target="_blank">
                                            <?= isset($value['news_title']) ? $value['news_title'] : '..' ?>
                                        </a>
                                    </h6>
                                    <small data-toggle="tooltip" data-bs-placement="right" title="<?= isset($value['news_short_desc']) ? ($value['news_short_desc']) : '..' ?>" >
                                        <?= isset($value['news_short_desc']) ? (substr(($value['news_short_desc']), 0, 50) . ' ...') : '..' ?>
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['news_author'] ? $value['news_author'] : NA) ?></span>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['news_approved'] ? 'Yes' : 'No') ?></span>
                        </td>
                        <td>
                            <p>
                                <?= (isset($value['news_createdon']) && (strtotime($value['news_createdon']) !== false)) ? date("M d, Y", strtotime($value['news_createdon'])) : 'Not Available' ?>
                            </p>
                        </td>
                        <td>
                            <span class="stats"><?= ($value['news_status'] ? 'Active' : 'Inactive') ?></span>
                        </td>
                        <td>
                            <?php if($this->userid == $value['news_userid']): ?>
                                <a href="<?= l('dashboard/news/post/') . $value['news_slug'] . '/edit' ?>" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;|&nbsp;
                                <a href="javascript:;" class="delete_news" data-id="<?= $value['news_id'] ?>"><i class="fa fa-trash-can"></i></a>
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
                        <?= __('Your posted newss will be shown here.') ?>
                    </td>
                </tr>
            </tbody>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($news) && count($news) > 0) : ?>
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
                                                                                        echo l('dashboard/news/listing/') . $prev;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/news/listing/') . $i; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/news/listing/') . $next;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>


<script>
    $(document).ready(function() {
        $('body').on('click', '.delete_news', function() {
            var data = {
                _token: $('meta[name=csrf-token]').attr("content"),
                id: $(this).data('id')
            }
            var url = base_url + 'dashboard/news/delete'
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this news!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {

                    new Promise((resolve, reject) => {
                        $.ajax({
                            type: "POST",
                            url: url,
                            data: data,
                            dataType: 'JSON',
                            async: true,
                            success: function(response) {
                                resolve(response)
                            },
                            complete: function(xhr, txt) {
                                hideLoader()
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            }
                        });
                    }).then(
                        function(response) {
                            if (response.status) {
                                hideLoader();
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                            } else {
                                hideLoader();
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