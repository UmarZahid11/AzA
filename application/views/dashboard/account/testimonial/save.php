
<div class="dashboard-content">
    <i class="fa-light fa-comments"></i>
    <h4><?= __('Save Testimonial') ?> </h4>
    <hr />

    <div class="create-profile-form">
        <form id="testimonialForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" />

            <?php if (isset($account_testimonial['account_testimonial_id'])) : ?>
                <input type="hidden" name="account_testimonial_id" value="<?= ($account_testimonial['account_testimonial_id']) ?>" />
            <?php endif; ?>
            <input type="hidden" name="account_testimonial[account_testimonial_to]" value="<?= $to ?>" />
            <input type="hidden" name="account_testimonial[account_testimonial_signup_id]" value="<?= $this->userid ?>" />

            <div class="row">
                <!-- <div class="col-12 mb-4">
                    <label><?= __('Enter description') ?> <span class="text-danger">*</span></label>
                    <textarea class="form-control" placeholder="Enter description" name="account_testimonial[account_testimonial_text]" required minlength="10" maxlength="5000"><?= isset($account_testimonial['account_testimonial_text']) ? $account_testimonial['account_testimonial_text'] : '' ?></textarea>
                </div> -->

                <div class="form-group mb-2">
                    <label>
                        <?= __('Upload a video of yourself providing a video testimonial') ?>&nbsp;(<small><?= __('The size limit for the attachment is 2 MB with a maximum duration of 2 minutes.') ?>):</small>
                        <span data-toggle="tooltip" data-bs-placement="top" title="A detailed video, ideally less than 3 minutes.">
                            <i class="fa fa-circle-question"></i>
                        </span>
                    </label>
                    <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop video') ?>
                        <input type="file" name="account_testimonial_attachment" class="form__file" id="upload-testimonial-video" accept="video/*" />
                    </label>

                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>

                    <div class="videoDiv">
                        <?php if (isset($account_testimonial['account_testimonial_attachment']) && $account_testimonial['account_testimonial_attachment']) : ?>
                            <a data-fancybox href="<?= get_image($account_testimonial['account_testimonial_attachment_path'], $account_testimonial['account_testimonial_attachment']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($account_testimonial['account_testimonial_id']) && $account_testimonial['account_testimonial_id'] ? (int) $account_testimonial['account_testimonial_id'] : 0 ?>" data-param="account_testimonial_attachment" data-toggle="tooltip" data-bs-placement="top" title="Delete this video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="form-group mb-2">
                    <label>Write something</label>
                    <textarea class="form-control" name="account_testimonial[account_testimonial_text]" maxlength="100"><?= isset($account_testimonial['account_testimonial_text']) ? $account_testimonial['account_testimonial_text'] : '' ?></textarea>
                </div>
                
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-custom" id="testimonial-submit">
                        <?= __('Save') ?>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    var formId = 'testimonialForm';
    var formIdElement = '#' + formId;

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
    
    /**
     * Method submitTestimonial
     *
     * @return void
     */
    async function submitTestimonial(event) {
        
        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById(formId))
        var url = base_url + 'dashboard/account/testimonial/saveData';
        //
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
                    $('#testimonial-submit').attr('disabled', true)
                    $('#testimonial-submit').html('Saving ...')
                },
                complete: function() {
                    $('#testimonial-submit').attr('disabled', false)
                    $('#testimonial-submit').html('Save')
                }
            });
        })
    }

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/account/testimonial/deleteAttachment'
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

        const dt = new DataTransfer();

        $('#upload-testimonial-video').on('change', function() {
            for (var i = 0; i < this.files.length; i++) {
                let fileBloc = $('<span/>', {
                        class: 'file-block'
                    }),
                    fileName = $('<span/>', {
                        class: 'name',
                        text: this.files.item(i).name
                    });
                if (this.files.item(i).size < 10000000) {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }

                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };

            // dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                // let name = $(this).parent().next('span.name').text();
                let name = $(this).parent().find('span.name').html()
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        $(this).parent().remove();
                        dt.items.remove(i);
                        continue;
                    }
                }
                const input = document.getElementById('upload-testimonial-video')
                input.files = dt.files;
            });
        });

        $('#' + formId).on('submit', function(event) {
            event.preventDefault();
    
            var no_videos_error = false;
            var size_error = false;
            var length_error = false;

            if (!$(formIdElement)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(formIdElement).addClass('was-validated');
                $(formIdElement).find(":invalid").first().focus();
                return false;
            } else {
                $(formIdElement).removeClass('was-validated');
            }
            
            $('#upload-testimonial-video').each(function(index, ele) {
                if(ele.files.length == 0) {
                    no_videos_error = true;
                }
                
                if(!no_videos_error) {
                    for (var i = 0; i < ele.files.length; i++) {
                        const file = ele.files[i];
                        if(invalidFileLength(file)) {
                            length_error = true;
                        }
                        if (file.size > <?= MAX_FILE_SIZE ?>) {
                            size_error = true;
                        }
                    }
                }
            })

            if(!no_videos_error || $('input[name=account_testimonial_id]').length != '') {
                if(!size_error && !length_error) {
                    submitTestimonial(event).then(
                        function(response) {
                            if (response.status) {
                                AdminToastr.success(response.txt);
                                if (response.refresh) {
                                    if(response.redirect_url) {
                                        location.href = response.redirect_url;
                                    } else {
                                        location.reload()
                                    }
                                }
                            } else {
                                AdminToastr.error(response.txt);
                            }
                        }
                    );
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
            } else {
                $.dialog({
                    backgroundDismiss: true,
                    title: '<?= __("Error") ?>',
                    content: '<?= ERROR_MESSAGE_FILE_UPLOAD ?>',
                });
            }
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
                        '_token': $('meta[name="csrf-token"]').attr('content'),
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
    })
</script>