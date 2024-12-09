<style>
    .center {
        margin-left: auto;
        margin-right: auto;
        display: block
    }

    .thumbnail {
        display: inline-block;
        border: 1px solid white;
        cursor: pointer;
    }

    .thumbnail:focus {
        outline: 0;
        border: 1px solid orange;
    }

    .playing {
        border: 1px solid orange;
    }
    
    .video-gallery {
      padding:40px 0;
    }

</style>

<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

<div class="dashboard-content">

    <i class="fa-regular fa-quote-left"></i>
    <h4><?= __('Testimonials') . (isset($signup) ? ' by "' . $this->model_signup->profileName($signup, FALSE) . '"' : '') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . TESTIMONIAL_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Testimonial Tutorial</a>
    <hr />

    <?php if (isset($userId) && $userId == $this->userid) : ?>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Add/Edit Testimonials
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="create-profile-form">
                            <form class="dropzone" id="my-dropzone">
                                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                            </form>
                        </div>
                        <small class="text-custom font-11">Note: <?= GENERAL_ATTACHMENT_SIZE_DESCIPTION ?></small>
                        <hr />
                    </div>
                </div>
            </div>
        </div>
    
    <?php endif; ?>
    
    <!--<div class="container testimonial_listing">-->
    <!--    <?php if (isset($signup_testimonial) && count($signup_testimonial) > 0) : ?>-->
    <!--        <video id="primary-player" controls controlsList="nodownload" width=800 class="cld-video-player center">-->
    <!--        </video>-->
    <!--        <div class="text-center">-->
    <!--            <hr />-->
    <!--            <?php foreach ($signup_testimonial as $key => $value) : ?>-->
    <!--                <video class="thumbnail w-25" src="<?= get_image($value['signup_testimonial_attachment_path'], $value['signup_testimonial_attachment']) ?>">-->
    <!--                </video>-->
    <!--            <?php endforeach; ?>-->
    <!--        </div>-->
    <!--    <?php else : ?>-->
    <!--        <p><?= __('No testimonial available') ?>.</p>-->
    <!--    <?php endif; ?>-->
    <!--</div>-->
    
    <div id="videoGallery" class="video-gallery testimonial_listing">
        <input type="hidden" name="userId" value="<?= isset($userId) ? $userId : 0 ?>" />
        <?php if (isset($signup_testimonial) && count($signup_testimonial) > 0) : ?>
            <!-- Thumbnails -->
            <div id="thumbnails" class="list row small-up-1 medium-up-2 large-up-3">
                <?php foreach ($signup_testimonial as $key => $value) : ?>
                    <div class="col-md-4 mb-2">
                        <a data-fancybox href="<?= get_image($value['signup_testimonial_attachment_path'], $value['signup_testimonial_attachment']) ?>">
                            <!--<img src="https://img.youtube.com/vi/DUWTT75xt6w/mqdefault.jpg" width="800" />-->
                            <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="800" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            <span class="text-dark"><strong>Creator: <?= $this->model_signup->profileName($value) ?></strong></span><br/>
                            <span class="text-dark">Uploaded: <?= date('d M, Y', strtotime($value['signup_testimonial_createdon'])) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- End Row -->
        <?php else: ?>
            <p><?= __('No testimonial available') ?>.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>

<script>
    if ($('input[name=userId]').val()) {
        // Dropzone.autoDiscover = false;
        Dropzone.options.myDropzone = {
            method: 'POST',
            url: '<?= l('dashboard/profile/update_testimonial') ?>',
            uploadMultiple: true,
            paramName: "signup_testimonial",
            maxFilesize: 20, // MB
            maxFiles: 3,
            acceptedFiles: 'video/*',
            addRemoveLinks: true,
            init: function() {
                myDropzone = this;
                var data = {
                    'userId': $('input[name=userId]').val()
                }
                var url = base_url + 'dashboard/profile/get_testimonial'

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
                            showLoader()
                        },
                        complete: function() {
                            hideLoader()
                        }
                    })
        		}).then(
        		    function(response) {
                        $.each(response, function(key, value) {
                            var mockFile = {
                                name: value.name,
                                size: value.size
                            };
                            myDropzone.emit("addedfile", mockFile);
                            myDropzone.emit("complete", mockFile);
                        });
        		    }
    		    )
                myDropzone.on("removedfile", function(file) {

                    var data = {
                        'userId': $('input[name=userId]').val(),
                        'signup_testimonial_attachment': file.name
                    }
                    var url = base_url + 'dashboard/profile/remove_testimonial'

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
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
            		}).then(
            		    function(response) {
            		        if (response.status) {
                                $(".testimonial_listing").load(location.href + " .testimonial_listing>*", function(){
                                    trigger_primary_player();
                                });
            		        }
            		    }
        		    )
                });
            },
            done: function() {},
            complete: function() {
                $(".testimonial_listing").load(location.href + " .testimonial_listing>*", function () {
                    trigger_primary_player();
                });
            },
        };
    }

    //
    function trigger_primary_player() {
        setTimeout(function() {
            $('#primary-player').attr('src', $('.thumbnail')[0].src);
            $('#primary-player').trigger('play')
        }, 1000)
    }

    //
    $(document).ready(function() {
        trigger_primary_player();
    })

    //
    $('body').on('click', '.thumbnail', function() {
        $('#primary-player').attr('src', $(this).attr('src'));
        setTimeout(function() {
            $('#primary-player').trigger('play')
        }, 1000)
    })
</script>