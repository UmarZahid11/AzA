<?php
global $config;

// Define plugins for page
$my_tools = array(
    "datetime-picker" => array(
        "css" => array("css/jquery.datetimepicker.css"),
        "js" => array("js/jquery.datetimepicker.js"),
    ),
    "datatables" => array(
        "css" => array("datatables.min.css"),
        "js" => array("datatables.min.js"),
    ),
    "select2" => array(
        // "css" => array("select2.css"),
        // "js" => array("select2.js"),
        // "js" => array("select2.js", "select2_custom.js"),
        "css" => array("select2.min.css"),
        "js" => array("select2.min.js", "select2_custom.js"),
    ),
    "fb" => array(
        "css" => array("style.css"),
        "js" => array("script.js"),
    ),
    "fancybox" => array(
        "css" => array("jquery.fancybox.min.css"),
        "js" => array("jquery.fancybox.min.js"),
    ),
    "slick" => array(
        "css" => array("slick-theme.css", "slick.css"),
        "js" => array("slick.js"),
    ),
);

?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <title> <?= $title ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="<?= $this->session->userdata['csrf_token'] ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_favicon']) ?>">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    <?php foreach ($meta_data as $meta_name => $meta_val) : ?>
        <meta name="<?= $meta_name ?>" content="<?= $meta_val ?>">
    <?php endforeach; ?>

    <!-- Loading css file -->
    <? foreach ($css_files as $css) : ?>
        <link href="<?= g('css_root') . $css ?>" rel="stylesheet" type="text/css" />
    <?php endforeach; ?>

    <?php
    // Load js file
    if (is_array($js_files_init)) {
        foreach ($js_files_init as $js) {
            echo '<script src="' . g('js_root') . $js . '"></script>';
        }
    }

    // Load additional files css
    if (is_array($additional_tools) && count($additional_tools)) {
        foreach ($additional_tools as $tool) {
            if (isset($my_tools[$tool]['css']) && is_array($my_tools[$tool]['css'])) {
                foreach ($my_tools[$tool]['css'] as $script) {
                    if ($script) {
                        echo '<link  href="' . g('plugins_root') . $tool . "/" . $script . '" rel="stylesheet" />';
                    }
                }
            }
        }
    }

    $tool_activators = "";

    // Load additional files js
    if (isset($additional_tools) && array_filled($additional_tools)) {
        foreach ($additional_tools as $tool) {
            if (isset($my_tools[$tool]['js']) && is_array($my_tools[$tool]['js'])) {
                foreach ($my_tools[$tool]['js'] as $script) {
                    $tool_activators .= "toolset.tool_" . str_replace("-", "_", $tool) . " = true;";
                    echo '<script src="' . g('plugins_root') . $tool . "/" . $script . '"></script>';
                }
            }
        }
    }
    ?>

    <script type="text/javascript">
        var base_url = "<?php echo base_url(); ?>";
    </script>

    <script type="text/javascript">
        var base_url_other = "<?php echo $config['base_url_other']; ?>";
    </script>

</head>

<body class="d-flex side-bar-active">

    <div id="loader"></div>

    <div id="preloader" style="display:none;">
        <div class="loader">
            <div class="loader__dot"></div>
            <div class="loader__dot"></div>
            <div class="loader__dot"></div>
            <div class="loader__dot"></div>
        </div>
    </div>

    <!-- Wrapper Start
        ================================================== -->
    <?php $this->load->view("_layout/dashboard/sidebar"); ?>

    <div class="main-inner-dash">
        <?php $this->load->view("_layout/dashboard/topbar"); ?>
        <?php echo $content_block; ?>
        <?php $this->load->view("_layout/dashboard/footer"); ?>
    </div>
    <!-- Wrapper End
        ================================================== -->

    <!-- Load js files
        ================================================== -->
    <?php foreach ($js_files as $js) : ?>
        <script src="<?= g('js_root') . $js ?>"></script>
    <?php endforeach; ?>
    <!-- End load js files
        ================================================== -->

    <script>
        $(document).ready(function() {
            <? if ((isset($_GET['msgtype'])) && ($_GET['msgtype']) && ($_GET['msg'])) { ?>
                AdminToastr.<?= $_GET['msgtype'] ?>("<?= $_GET['msg'] ?>", "<?= $_GET['msgtype'] ?>", {
                    positionClass: "toast-bottom-full-width"
                });
            <? } ?>
        });
    </script>

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <?php if ($this->session->flashdata('activation_message')) {
        echo '<script>swal("' . __('Great') . '", "' . $this->session->flashdata('activation_message') . '", "success")</script>';
    } ?>

    <?php if ($this->session->flashdata('stripe_error')) {
        echo '<script>swal("' . __('Stripe Error') . '", "' . $this->session->flashdata('stripe_error') . '", "error")</script>';
    } ?>

    <?php if ($this->session->flashdata('error')) {
        echo '<script>swal("' . __('Error') . '", "' . $this->session->flashdata('error') . '", "error")</script>';
    } ?>

    <?php if ($this->session->flashdata('xml_error')) {
        echo '<script>swal("' . __('Error') . '", "' . $this->session->flashdata('xml_error') . '", "error")</script>';
    } ?>

    <?php if ($this->session->flashdata('success')) {
        echo '<script>swal("' . __('Success') . '", "' . $this->session->flashdata('success') . '", "success")</script>';
    } ?>

    <?php if ($this->session->flashdata('box_success')) {
        echo '<script>toastr.success("' . $this->session->flashdata('box_success') . '")</script>';
    } ?>

    <?php if ($this->session->flashdata('box_recreate')) : ?>
        <script>
            swal({
                title: "<?= __('Warning') ?>",
                text: "<?= $this->session->flashdata('box_recreate') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Create new user') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    let data = {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                    };
                    let url = base_url + 'dashboard/box/reCreateUser';

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
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                        })
                    }).then(
                        function(response) {
                            if (response.status) {
                                AdminToastr.success(response.txt);
                            } else {
                                AdminToastr.error(response.txt);
                            }
                        }
                    )
                }
            });
        </script>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            //
            $("img.lazy").lazyload();

            $(function() {
                $('[data-toggle="tooltip"]').tooltip({
                    html: true,
                    delay: {
                        show: 100,
                        hide: 500
                    }
                })
            })

            if ($('.upgradeBtn').length > 0) {
                $('.upgradeBtn').tooltip('show')
            }
        })
    </script>

    <script type="text/javascript">
        window.onload = function() {
            $("#loader").fadeOut(500);
        };
    </script>

    <?php $this->load->view("_layout/common-scripts"); ?>

    <?php $this->load->view('_layout/common-footer.php'); ?>

</body>

</html>