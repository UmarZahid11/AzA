<div class="dashboard-content">
    <i class="fa-light fa-comments"></i>
    <h4><?= __('Save Endorsement') ?> </h4>
    <hr />

    <div class="create-profile-form">
        <form id="endorsementForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" />

            <?php if (isset($endorsement['endorsement_id'])) : ?>
                <input type="hidden" name="endorsement_id" value="<?= ($endorsement['endorsement_id']) ?>" />
            <?php endif; ?>
            <input type="hidden" name="endorsement[endorsement_to]" value="<?= $to ?>" />
            <input type="hidden" name="endorsement[endorsement_signup_id]" value="<?= $this->userid ?>" />

            <div class="row">
                <div class="col-12 mb-4">
                    <label><?= __('Enter description') ?> <span class="text-danger">*</span></label>
                    <textarea class="form-control" placeholder="Enter description" name="endorsement[endorsement_text]" required minlength="10" maxlength="5000"><?= isset($endorsement['endorsement_text']) ? $endorsement['endorsement_text'] : '' ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <?= __('Endorsement video') ?>&nbsp;(<small><?= __(GENERAL_ATTACHMENT_SIZE_DESCIPTION) ?>):</small>
                        <span data-toggle="tooltip" data-bs-placement="top" title="A detailed video, ideally less than 3 minutes.">
                            <i class="fa fa-circle-question"></i>
                        </span>
                    </label>
                    <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop video') ?>
                        <input type="file" name="endorsement_attachment" class="form__file" id="upload-endorsement-video" accept="video/*" />
                    </label>

                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>

                    <div class="videoDiv mb-2">
                        <?php if (isset($endorsement['endorsement_attachment']) && $endorsement['endorsement_attachment']) : ?>
                            <a data-fancybox href="<?= get_image($endorsement['endorsement_attachment_path'], $endorsement['endorsement_attachment']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($endorsement['endorsement_id']) && $endorsement['endorsement_id'] ? (int) $endorsement['endorsement_id'] : 0 ?>" data-param="endorsement_attachment" data-toggle="tooltip" data-bs-placement="top" title="Delete this video">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>

                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-custom" id="endorsement-submit">
                        <?= __('Save') ?>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    var formId = 'endorsementForm';
    var formIdElement = '#' + formId;

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/endorsement/deleteAttachment'
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

    /**
     * Method submitEndorsement
     *
     * @return void
     */
    async function submitEndorsement(event) {

        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById(formId))
        var url = base_url + 'dashboard/endorsement/saveData';
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
                    $('#endorsement-submit').attr('disabled', true)
                    $('#endorsement-submit').html('Saving ...')
                },
                complete: function() {
                    $('#endorsement-submit').attr('disabled', false)
                    $('#endorsement-submit').html('Save')
                }
            });
        })
    }

    $(document).ready(function() {

        const dt = new DataTransfer();

        $('#upload-endorsement-video').on('change', function() {
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
                const input = document.getElementById('upload-endorsement-video')
                input.files = dt.files;
            });
        });

        $('#' + formId).on('submit', function(event) {
            event.preventDefault();

            if (!$(formIdElement)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(formIdElement).addClass('was-validated');
                $(formIdElement).find(":invalid").first().focus();
                return false;
            } else {
                $(formIdElement).removeClass('was-validated');
            }

            var size_error = false;
            $('#upload-endorsement-video').each(function(index, ele) {
                for (var i = 0; i < ele.files.length; i++) {
                    const file = ele.files[i];
                    if (file.size > <?= MAX_FILE_SIZE ?>) {
                        size_error = true;
                    }
                }
            })

            if(!size_error) {
                submitEndorsement(event).then(
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
                AdminToastr.error('<?= ERROR_MESSAGE_FILE_EXCEED_LIMIT ?>')
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