<style>
    .select2-container-multi .select2-choices {
        border: 0px !important;
    }

    span.tag.label.label-info {
        padding: 2px 7px;
    }

    .bootstrap-tagsinput {
        width: 100% !important;
    }
</style>


<div class="dashboard-content">
    <i class="fa-light fa-newspaper-o"></i>
    <h4><?= __('Post News') ?> </h4>
    <hr>
    <div class="create-profile-form">
        <form id="newsForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="" />
            <div class="row">
                <div class="col-md-6">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type="file" name="file" id="newsUpload" accept="image/*" />
                            <label for="newsUpload"></label>
                        </div>
        
                        <div class="avatar-preview">
                            <?php if (isset($news['news_attachment']) && $news['news_attachment']) : ?>
                                <div id="imagePreview" style="background-image: url(<?= get_image($news['news_attachment_path'], $news['news_attachment']) ?>);">
                                </div>
                            <?php else : ?>
                                <div id="imagePreview" style="background-image: url(<?= g('dashboard_images_root') ?>upload-img.jpg);">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form__container" id="upload-container"><?= __('Drop or click to upload news video') ?>
                        <input type="file" name="news_video" class="form__file" id="upload-news-video" accept="video/*" />
                    </label>
                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>
                    <hr />
                    <div class="videoDiv">
                        <?php if (isset($news['news_video']) && $news['news_video']) : ?>
                            <a data-fancybox href="<?= get_image($news['news_attachment_path'], $news['news_video']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="100" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 15px; color: #fff !important;" href="javascript:;" data-id="<?= isset($news['news_id']) && $news['news_id'] ? (int) $news['news_id'] : 0 ?>" data-param="news_video" data-toggle="tooltip" data-bs-placement="top" title="Delete news video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="row">

                <?php if (isset($news['news_id']) && intVal($news['news_id']) > 0) : ?>
                    <input type="hidden" name="news_id" value="<?= $news['news_id'] ?>" />
                <?php endif; ?>

                <input type="hidden" name="news[news_userid]" value="<?= $this->userid ?>" />
                <input type="hidden" class="slug" name="news[news_slug]" value="<?= isset($news['news_slug']) ? $news['news_slug'] : '' ?>" />

                <div class="col-6">
                    <label><?= __('Title') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Title" name="news[news_title]" required maxlength="100" value="<?= isset($news['news_title']) ? $news['news_title'] : '' ?>" />
                </div>

                <div class="col-6">
                    <label><?= __('Author') ?></label>
                    <input type="text" class="form-control" placeholder="Enter Author" name="news[news_author]" maxlength="100" value="<?= isset($news['news_author']) ? $news['news_author'] : $this->model_signup->signupName($this->user_data, false) ?>" />
                </div>

                <div class="col-12">
                    <label><?= __('External URL') ?></label>
                    <input type="url" class="form-control" placeholder="Enter external url" name="news[news_url]" maxlength="100" value="<?= isset($news['news_url']) ? $news['news_url'] : '' ?>" />
                </div>

                <div class="col-12">
                    <label><?= __('Short details') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter short details" name="news[news_short_desc]" required maxlength="150" value="<?= isset($news['news_short_desc']) ? $news['news_short_desc'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Short detail') ?></small>
                </div>

                <div class="col-12">
                    <label><?= __('Description') ?> <span class="text-danger">*</span></label>
                    <textarea id="news_desc" class="form-control" name="news[news_desc]"><?= isset($news['news_desc']) ? $news['news_desc'] : '' ?></textarea>
                    <small class="invalid-feedback descriptionEditor"><?= sprintf(__('%s is a required field.'), 'News description') ?></small>
                </div>

                <div class="col-md-6">
                    <label><?= __('Tags') ?></label>
                    <input type="text" class="form-control" id="tag" name="tag" maxlength="1000" value="<?= isset($tags) ? $tags : '' ?>" />
                </div>

                <div class="col-md-6">
                    <label><?= __('Status') ?></label>
                    <div class="slect-in">
                        <select class="form-select" name="news[news_status]">
                            <option value="<?= STATUS_ACTIVE ?>" <?= isset($news['news_status']) && $news['news_status'] == STATUS_ACTIVE  ? 'selected' : '' ?>><?= __('ACTIVE') ?></option>
                            <option value="<?= STATUS_INACTIVE ?>" <?= isset($news['news_status']) && $news['news_status'] == STATUS_INACTIVE ? 'selected' : '' ?>><?= __('INACTIVE') ?></option>
                        </select>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-custom publishBtn"><?= __('Publish') ?></button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function invalidFileLength(file) {
        var video = document.createElement('video');
        video.preload = 'metadata';
        video.onloadedmetadata = function() {
            window.URL.revokeObjectURL(video.src);
            console.log(video.duration)
            if (video.duration <= 120) {
                return false;
            } else {
                console.log("Invalid Video! video is less than 120 second");
                return true;
            }
        }
    }
    
    async function saveNews() {
        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById("newsForm"));
        var url = "<?php echo l('dashboard/news/save'); ?>";

        return new Promise((resolve, reject) => {
            //
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: 'json',
                async: true,
                success: function (response) {
                    resolve(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function () {
                    $('.publishBtn').attr('disabled', true)
                    $('.publishBtn').html('Publishing ...')
                },
                complete: function() {
                    $('.publishBtn').attr('disabled', false)
                    $('.publishBtn').html('Publish')
                }
            });
        })
    }

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/news/deleteVideo'
        //
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function(jqXHR, textStatus) {
                    hideLoader()
                },
                beforeSend: function() {
                    showLoader()
                }
            });
        })
    }
    
    $(document).ready(function() {

        // jquery tags input
        var tagInputEle = $('#tag');
        tagInputEle.tagsinput({
            maxTags: 5,
        });

        var newsEditor;

        // initiate Ckeditor
        // ckeditor for news detail
        ClassicEditor
            .create(document.querySelector('#news_desc'))
            .then(editor => {
                newsEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        $('input[name="news[news_title]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(generateSlug($(this).val()))
        })

        function generateSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        $("#newsUpload").change(function() {
            var file_obj = $(this);
            var ext = file_obj.val().split('.').pop().toLowerCase();
            if (ext != '') {
                if ($.inArray(ext, ['png', 'jpg', 'jpeg']) == -1) {
                    file_obj.val('');
                    AdminToastr.error('Extension Not allowed');
                } else {
                    readURL(this, $('#imagePreview'));
                    return false;
                }
            }
        });

        const dt = new DataTransfer();

        $('#upload-news-video').on('change', function() {
            // $("#videoList > #video-names").html('')
            for (var i = 0; i < this.files.length; i++) {
                // 100000000 = 100 MB
                // 10000000 = 10 MB
                // 1000000 = 1 MB
                // 100000 = 100 KB
                let fileBloc = $('<span/>', {
                        class: 'file-block'
                    }),
                    fileName = $('<span/>', {
                        class: 'name',
                        text: this.files.item(i).name
                    });
                $('#video-names').html('')
                if (this.files.item(i).size < 2000000) {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }

                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };

            dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                let name = $(this).parent().find('span.name').html()
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        $(this).parent().remove();
                        dt.items.remove(i);
                        continue;
                    }
                }
                const input = document.getElementById('upload-news-video')
                input.files = dt.files;
            });
        })

        $('body').on('click', '.video-del-btn', function() {
            swal({
                title: "<?= __('Warning') ?>",
                text: 'Delete this video',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'id': $(this).data('id'),
                        'param': $(this).data('param')
                    }
                    deleteAttachment(data).then(
                        function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                                $('.videoDiv').remove();
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
        
        $("#newsForm").on('submit', function() {
            var size_error = false;
            var length_error = false;

            if (!$('#newsForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#newsForm').addClass('was-validated');
                $('#newsForm').find(":invalid").first().focus();
                if (newsEditor.getData() == '<p>&nbsp;</p>') {
                    $('.descriptionEditor.invalid-feedback').show()
                } else {
                    $('.descriptionEditor.invalid-feedback').hide()
                }
                return false;
            } else {
                if (newsEditor.getData() == '<p>&nbsp;</p>') {
                    if (newsEditor.getData() == '<p>&nbsp;</p>') {
                        $('.descriptionEditor.invalid-feedback').show()
                    } else {
                        $('.descriptionEditor.invalid-feedback').hide()
                    }
                    return false;
                }
                $('#newsForm').removeClass('was-validated');
            }
            
            $('#upload-news-video').each(function(index, ele) {
                for (var i = 0; i < ele.files.length; i++) {
                    const file = ele.files[i];
                    if(invalidFileLength(file)) {
                        length_error = true;
                    }
                    if (file.size > 20000000) {
                        size_error = true;
                    }
                }
            }) 
            
            if(!size_error && !length_error) {
                saveNews().then(
                    function(response) {
                        if (response.status == 0) {
                            AdminToastr.error(response.txt, 'Error');
                        }
                        else if (response.status == 1) {
                            AdminToastr.success(response.txt, 'Success');
                            if(response.type) {
                                if(response.type == 'insert') {
                                    location.href = "<?= l('dashboard/news/detail') ?>" + '/' + $('.slug').val()
                                }
                            }
                        }
                    }
                )
            } else {
                if(size_error) {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error") ?>',
                        content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                    });
                }
                if(length_error) {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error") ?>',
                        content: '<?= __("Upload a video of maximum duration of 120 seconds!") ?>',
                    });
                }
            }
        })
    })
</script>