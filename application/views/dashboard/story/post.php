<style>
    .select2-container-multi .select2-choices {
        border: 0px !important;
    }

    span.tag.label.label-info {
        padding: 2px 7px;
        background: #f89300;
    }

    .bootstrap-tagsinput {
        width: 100% !important;
    }
</style>


<div class="dashboard-content">
    <i class="fa-light fa-pen-ruler"></i>
    <h4><?= __('Post New Story') ?> </h4>
    <hr>
    <div class="create-profile-form">
        <form id="storyForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" />
            <div class="row">
                <div class="col-md-6">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type="file" name="file" id="storyUpload" accept="image/*" />
                            <label for="storyUpload"></label>
                        </div>
        
                        <div class="avatar-preview">
                            <?php if (isset($story['story_image']) && $story['story_image'] != "") : ?>
                                <div id="imagePreview" style="background-image: url(<?= get_image($story['story_image_path'], $story['story_image']) ?>);">
                                </div>
                            <?php else : ?>
                                <div id="imagePreview" style="background-image: url(<?= g('dashboard_images_root') ?>upload-img.jpg);">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form__container" id="upload-container"><?= __('Drop or click to upload story video') ?>
                        <input type="file" name="story_video" class="form__file" id="upload-story-video" accept="video/*" />
                    </label>
                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>
                    <hr />
                    <div class="videoDiv">
                        <?php if (isset($story['story_video']) && $story['story_video']) : ?>
                            <a data-fancybox href="<?= get_image($story['story_image_path'], $story['story_video']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="100" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 15px; color: #fff !important;" href="javascript:;" data-id="<?= isset($story['story_id']) && $story['story_id'] ? (int) $story['story_id'] : 0 ?>" data-param="story_video" data-toggle="tooltip" data-bs-placement="top" title="Delete story video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <label><?= __('Title') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Title" name="story[story_title]" required maxlength="100" value="<?= isset($story['story_title']) ? $story['story_title'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Story title') ?></small>

                    <input type="hidden" class="slug" name="story[story_slug]" value="<?= isset($story['story_slug']) ? $story['story_slug'] : '' ?>" />
                    <input type="hidden" name="story[story_userid]" value="<?= $this->userid ?>" />

                    <?php if (isset($story['story_id']) && intVal($story['story_id']) > 0) : ?>
                        <input type="hidden" name="story_id" value="<?= $story['story_id'] ?>" />
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <label><?= __('Author') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Author" name="story[story_author]" required maxlength="100" value="<?= isset($story['story_author']) ? $story['story_author'] : $this->model_signup->signupName($this->user_data, false) ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Story Author') ?></small>

                </div>
                <div class="col-12">
                    <label><?= __('Short details') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter short details" name="story[story_short_detail]" required maxlength="150" value="<?= isset($story['story_short_detail']) ? $story['story_short_detail'] : '' ?>" />
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Short detail') ?></small>
                </div>
                <div class="col-12">
                    <label><?= __('Details') ?> <span class="text-danger">*</span></label>
                    <textarea id="story_detail" class="form-control" name="story[story_detail]"><?= isset($story['story_detail']) ? $story['story_detail'] : '' ?></textarea>
                    <small class="invalid-feedback descriptionEditor"><?= sprintf(__('%s is a required field.'), 'Story description') ?></small>
                </div>

                <div class="col-md-6">
                    <label><?= __('Status') ?></label>
                    <div class="slect-in">
                        <select class="form-select" name="story[story_status]">
                            <option value="<?= STATUS_ACTIVE ?>" <?= isset($story['story_status']) && $story['story_status'] == STATUS_ACTIVE  ? 'selected' : '' ?>><?= __('ACTIVE') ?></option>
                            <option value="<?= STATUS_INACTIVE ?>" <?= isset($story['story_status']) && $story['story_status'] == STATUS_INACTIVE ? 'selected' : '' ?>><?= __('INACTIVE') ?></option>
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
    
    async function saveStory() {
        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById("storyForm"));
        var url = "<?php echo l('dashboard/story/save'); ?>";

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

        var storyEditor;

        // initiate Ckeditor
        // ckeditor for story detail
        ClassicEditor
            .create(document.querySelector('#story_detail'))
            .then(editor => {
                console.log(editor);
                storyEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        $('input[name="story[story_title]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(generateSlug($(this).val()))
        })

        function generateSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        $("#storyUpload").change(function() {
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

        $('#upload-story-video').on('change', function() {
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
                const input = document.getElementById('upload-story-video')
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
        
        $("#storyForm").on('submit', function() {
            var size_error = false;
            var length_error = false;

            if (!$('#storyForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#storyForm').addClass('was-validated');
                $('#storyForm').find(":invalid").first().focus();
                if (storyEditor.getData() == '<p>&nbsp;</p>') {
                    $('.descriptionEditor.invalid-feedback').show()
                } else {
                    $('.descriptionEditor.invalid-feedback').hide()
                }
                return false;
            } else {
                if (storyEditor.getData() == '<p>&nbsp;</p>') {
                    if (storyEditor.getData() == '<p>&nbsp;</p>') {
                        $('.descriptionEditor.invalid-feedback').show()
                    } else {
                        $('.descriptionEditor.invalid-feedback').hide()
                    }
                    return false;
                }
                $('#storyForm').removeClass('was-validated');
            }
            
            $('#upload-story-video').each(function(index, ele) {
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
                saveStory().then(
                    function(response) {
                        if (response.status == 0) {
                            AdminToastr.error(response.txt, 'Error');
                        }
                        else if (response.status == 1) {
                            AdminToastr.success(response.txt, 'Success');
                            if(response.type) {
                                if(response.type == 'insert') {
                                    location.href = "<?= l('dashboard/story/detail') ?>" + '/' + $('.slug').val()
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