<style>
    span.ui-helper-hidden-accessible {
        display: none;
    }

    .ui-menu .ui-menu-item a {
        color: #454545;
        z-index: 9999;
    }

    .ui-menu-item {
        border: 1px solid #ccc;
        padding: 5px 5px;
        list-style-type: none !important;
    }

</style>

<? global $config;

$model_heads = explode(",", $dt_params['dt_headings']);

?>

<div class="inner-page-header">

  <h1><?= humanize($class_name) ?> <small>Record</small></h1>

</div>



<div class="row">

  <div class="col-md-12">

    <!-- BEGIN VALIDATION STATES-->

    <div class="portlet box green">

      <div class="portlet-title">

        <div class="caption">

          <i class="fa fa-briefcase"></i><?= humanize($class_name) ?>

          <small>Add Details to <?= humanize($class_name) ?></small>

        </div>

      </div>

      <div class="portlet-body form">

        <!-- BEGIN FORM-->

        <? $this->load->view("admin/widget/form_generator"); ?>

        <!-- END FORM-->

      </div>

      <!-- END VALIDATION STATES-->

    </div>

  </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>

<script>
    async function invalidFileLength(file) {
        const video = await loadVideo(file)
        if (video.duration <= 120) {
            return false;
        } else {
            console.log("Invalid Video! video is greater than 120 second");
            return true;
        }

        // var video = document.createElement('video');
        // video.preload = 'metadata';
        //     video.onloadedmetadata = function() {
        //     window.URL.revokeObjectURL(video.src);
        //     if (video.duration <= 120) {
        //         return false;
        //     } else {
        //         console.log("Invalid Video! video is less than 120 second");
        //         return true;
        //     }
        // }
    }
    
    const loadVideo = file => new Promise((resolve, reject) => {
        try {
            let video = document.createElement('video')
            video.preload = 'metadata'
    
            video.onloadedmetadata = function () {
                resolve(this)
            }
    
            video.onerror = function () {
                reject("Invalid video. Please select a video file.")
            }
    
            video.src = window.URL.createObjectURL(file)
        } catch (e) {
            reject(e)
        }
    })

    $(document).ready(function() {

        Metronic.init(); // init metronic core components
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        UIAlertDialogApi.init(); //UI Alert API

        <? if (isset($error))
            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";
        ?>

        $('input[name="announcement[announcement_attachment_video]"]').on('change', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('input[name="announcement[announcement_attachment_video]"]').each(function(index, ele) {
                for (var i = 0; i < ele.files.length; i++) {
                    const file = ele.files[i];
                    invalidFileLength(file).then(
                        function(failed) {
                            if(failed) {
                                toastr.error('The uploaded video length exceeds system\'s limit.');
                                file.value = '';
                                $('.uploadfile-preview').css("max-width", "150px")
                                $('.uploadfile-preview').html('<img src="<?= g('admin_images_root') ?>video-icon.png" alt="">')
                            } else {
                                $('.uploadfile-preview').css("max-width", "350px")
                            }
                        }
                    )
                }
            })
        })
    });
</script>
