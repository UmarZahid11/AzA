<div class="dashboard-content posted-theme">

    <div class="float-right" style="display: -webkit-inline-box;">
        <a data-fancybox data-animation-duration="700" data-src="#createFolderModal" href="javascript:;" class="btn btn-outline-custom">Create folder</a>&nbsp;
        <form id="boxFileUpload" action="javascript:;" enctype="multipart/form-data">
            <input type="hidden" name="folder_id" value="<?= isset($folder_id) && $folder_id ? $folder_id : '0' ?>" />
            <label class="btn btn-outline-custom">
                <span id="uploadFileBtn"><?= __('Upload file') ?></span>
                <input type="file" name="file" class="d-none" />
            </label>
        </form>
    </div>

    <i class="fa fa-box"></i>
    <h4>Box</h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . BOX_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Box Tutorial</a>

    <hr />

    <?php if (isset($folder_information) && property_exists($folder_information, 'path_collection')) : ?>
        <?php if (property_exists($folder_information->path_collection, 'entries')) : ?>
            <?php foreach ($folder_information->path_collection->entries as $key => $value) : ?>
                <small><a href="<?= l('dashboard/box/index/0/' . $value->id) ?>"><?= $value->name . '&nbsp;' . (array_key_last($folder_information->path_collection->entries) == $key ? '<i class="fa fa-caret-right"></i>' : '<i class="fa fa-caret-right"></i>') ?></a></small>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($folder_information) && property_exists($folder_information, 'name')) : ?>
        <small><a href="<?= l('dashboard/box/index/0/' . $folder_information->id) ?>"><?= $folder_information->name; ?></a></small>
        <hr />
    <?php endif; ?>

    <div class="card">
        <?php if (isset($items_error) && $items_error && isset($items_error_message) && !empty($items_error_message)) : ?>
            <?php foreach ($items_error_message as $key => $value) : ?>
                <div class="alert alert-danger alert-dismissible font-13">
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    <?php echo $value; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($folder_items) && property_exists($folder_items, 'total_count') && $folder_items->total_count) : ?>
            <?php if (property_exists($folder_items, 'entries')) : ?>
                <table class="table table-striped font-13">
                    <tbody>
                        <?php if (property_exists($folder_items, 'entries') && is_array($folder_items->entries) && !empty($folder_items->entries)) : ?>
                            <?php foreach ($folder_items->entries as $key => $entries) : ?>
                                <?php if (multiple_property_exists($entries, ['type', 'id', 'name'])) : ?>
                                    <?php //if($entries->owned_by->id == $this->user_data['signup_box_id']): ?>
                                        <tr>
                                            <td>
                                                <?php switch ($entries->type) {
                                                    case 'folder':
                                                        echo '<i class="fa-regular fa-folder"></i> ' . '<a href="' . l('dashboard/box/index/' . $offset . '/' . $entries->id) . '">' . $entries->name . '</a>';
                                                        break;
                                                    case 'file':
                                                        echo '<i class="fa-regular fa-file"></i> ' . '<a href="' . l('dashboard/box/preview/' . (isset($folder_id) && $folder_id ? $folder_id : '0') . '/' . $entries->id) . '">' . $entries->name . '</a>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if (property_exists($entries, 'created_at')) : ?>
                                                    <span>
                                                        <small><?= validateDate($entries->created_at, 'Y-m-d\TH:i:sP') ? date('d M, Y h:i a', strtotime($entries->created_at)) : $entries->created_at ?></small>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <!--<td>-->
                                                <!--Owned By: <?//= $entries->owned_by->name ?>-->
                                            <!--</td>-->
                                            <td>
                                                <a href="javascript:;" data-fancybox data-animation-duration="700" data-src="#updateModal<?= $key ?>" class="edit_folder" data-toggle="tooltip" data-bs-placement="top" title="Edit details."><i class="fa-regular fa-edit"></i></a> |
                                                <a href="javascript:;" class="delete" data-method="delete" data-type="<?= $entries->type ?>" data-id="<?= $entries->id ?>" data-toggle="tooltip" data-bs-placement="bottom" title="Delete this <?= $entries->type ?>."><i class="fa-regular fa-trash-can"></i></a>
                                            </td>
                                        </tr>
                                    <?php //endif; ?>
                                <?php else : ?>
                                    <small><?= __(ERROR_MESSAGE) ?></small>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <small><?= __('There are no more items in this folder.') ?></small>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if (property_exists($folder_items, 'entries') && is_array($folder_items->entries) && !empty($folder_items->entries)) : ?>
                    <?php foreach ($folder_items->entries as $key => $entries) : ?>
                        <?php if (multiple_property_exists($entries, ['type', 'id', 'name'])) : ?>
                            <div class="grid">
                                <div style="display: none;" id="updateModal<?= $key ?>" class="animated-modal">
                                    <h4><?= __('Rename') ?> <?= ucfirst($entries->type) ?></h4>
                                    <form id="updateForm<?= $key ?>" class="updateForm" action="javascript:;" data-id="<?= $entries->id ?>" method="POST" novalidate>
                                        <input type="hidden" name="_token" value="" />
                                        <input type="hidden" name="id" value="<?= $entries->id ?>" />
                                        <input type="hidden" name="type" value="<?= $entries->type ?>" />
                                        <input type="hidden" name="method" value="put" />
                                        <div class="form-group">
                                            <label><?= __('Name') ?></label>
                                            <input type="text" class="form-control" name="name" value="<?php echo trim($entries->name); ?>" maxlength="200" required />
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-custom mt-2" id="updateFormBtn<?= $entries->id ?>"><?= __('Save') ?></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

                <?php if (property_exists($folder_items, 'entries') && is_array($folder_items->entries) && !empty($folder_items->entries)) : ?>
                    <div class="row">
                        <div class="col-lg-12">

                            <nav aria-label="Page navigation example mt-5">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php if ($offset <= 1) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($offset <= 1) {
                                                                                                        echo '#';
                                                                                                    } else {
                                                                                                        echo l('dashboard/box/index/' . $prev . '/' . $folder_id . '/' . $limit);
                                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                                    </li>

                                    <?php for ($i = 1; $i <= $offset; $i++) : ?>
                                        <li class="page-item <?php if ($offset == $i) {
                                                                    echo 'active';
                                                                } ?>">
                                            <a class="page-link" href="<?= l('dashboard/box/index/' . $i . '/' . $folder_id . '/' . $limit); ?>"> <?= $i; ?> </a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/box/index/' . $next . '/' . $folder_id . '/' . $limit); ?>">
                                            <i class="far fa-chevron-right"></i>
                                        </a>
                                    </li>

                                </ul>
                            </nav>

                        </div>
                    </div>
                <?php else : ?>
                    <a href="<?= l('dashboard/box/index/' . $prev . '/' . $folder_id) ?>"><i class="fa fa-arrow-left"></i> Go back</a>
                <?php endif; ?>

            <?php else : ?>
                <small><?= isset($items_error) && $items_error && isset($items_error_message) && $items_error_message ? $items_error_message : __('The folder is currently empty.') ?></small>
            <?php endif; ?>
        <?php else : ?>
            <small><?= isset($items_error) && $items_error && isset($items_error_message) && $items_error_message ? $items_error_message : __('The folder is currently empty.') ?></small>
        <?php endif; ?>
    </div>

    <div class="grid">
        <div style="display: none;" id="createFolderModal" class="animated-modal">
            <h4><?= __('Create new folder') ?></h4>
            <form id="createFolderForm" method="POST" action="javascript:;">
                <input type="hidden" name="_token" value="<?= $this->csrf_token ?>" />
                <input type="hidden" name="folder_id" value="<?= $folder_id ?>" />

                <label>
                    <?= __('Folder Name') ?>
                </label>
                <input class="form-control font-13" type="text" value="" name="folder_name" placeholder="<?= __('Enter folder name') ?>" maxlength="255" required />
                <button type="submit" id="createFolderFormBtn" class="btn btn-custom mt-2">Save</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('change', 'input[name=file]', function() {
            
            var uploadFileBtn = '#uploadFileBtn'
            var data = new FormData(document.getElementById('boxFileUpload'))
            data.append('_token', $('meta[name="csrf-token"]').attr('content'))
            var url = base_url + 'dashboard/box/upload'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    enctype: 'multipart/form-data',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    async: true,
                    success: function(response) {
                        resolve(response)
                    },
                    beforeSend: function() {
                        $(uploadFileBtn).addClass('disabled')
                        $(uploadFileBtn).html('Uploading ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                    },
                    complete: function() {
                        $(uploadFileBtn).removeClass('disabled')
                        $(uploadFileBtn).html('Upload file')
                    }
                });
            }).then(
                function(response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                        AdminToastr.success(response.txt, 'Success');
                    }
                    if (response.refresh) {
                        location.reload();
                    }
                }
            )
        })

        $('body').on('submit', '#createFolderForm', function() {
            
            var createFolderFormBtn = '#createFolderFormBtn'
            var data = $(this).serialize()
            var url = base_url + 'dashboard/box/createFolder'

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
                        $(createFolderFormBtn).removeClass('disabled')
                        $(createFolderFormBtn).html('Save')
                    },
                    beforeSend: function() {
                        $(createFolderFormBtn).addClass('disabled')
                        $(createFolderFormBtn).html('Saving ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('.fancybox-close-small').trigger('click');
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
                    if (response.refresh) {
                        location.reload();
                    }
                }
            )
        })

        $('body').on('submit', '.updateForm', function(event) {

            if (!$('.updateForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.updateForm').addClass('was-validated');
                $('.updateForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.updateForm').removeClass('was-validated');
            }

            var updateFormBtn = '#updateFormBtn' + $(this).data('id')
            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'dashboard/box/affect'

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
                        $(updateFormBtn).removeClass('disabled')
                        $(updateFormBtn).html('Save')
                    },
                    beforeSend: function() {
                        $(updateFormBtn).addClass('disabled')
                        $(updateFormBtn).html('Saving ...')
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    }
                });
            }).then(
                function(response) {
                    if (response.status == 0) {
                        AdminToastr.error(response.txt);
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt);
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('.fancybox-close-small').trigger('click');
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    }
                    if (response.refresh) {
                        location.reload();
                    }
                }
            )
            return false;
        })

        $('body').on('click', '.delete', function() {
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
                'id': $(this).data('id'),
                'type': $(this).data('type'),
                'method': $(this).data('method'),
            }
            var url = base_url + 'dashboard/box/affect'
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: 'You are about to delete this ' + $(this).data('type') + '!',
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
                            if (response.status == 0) {
                                AdminToastr.error(response.txt);
                            } else if (response.status == 1) {
                                AdminToastr.success(response.txt);
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                    $('.fancybox-close-small').trigger('click');
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                });
                            }
                            if (response.refresh) {
                                location.reload();
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