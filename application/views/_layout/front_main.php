<?php global $config;
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
        "css" => array("select2.css"),
        "js" => array("select2.js", "select2_custom.js"),
    ),
    "fb" => array(
        "css" => array("style.css"),
        "js" => array("jquery.fancybox.min.js"),
    ),
    "fancybox" => array(
        "css" => array("jquery.fancybox.min.css"),
        "js" => array("jquery.fancybox.min.js"),
    ),
    "owl-carousel" => array(
        "css" => array("owl.carousel.css", "owl.theme.css"),
        "js" => array("owl.carousel.js"),
    ),
    "slick" => array(
        "css" => array("slick.css", "slick-theme.css"),
        "js" => array("slick.js"),
    ),
);

?>

<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo $this->session->userdata['csrf_token'] ?>" />
    <title><?php echo $title ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_favicon']) ?>">

    <?php foreach ($meta_data as $meta_name => $meta_val) : ?>
        <meta name="<?= $meta_name ?>" content="<?= $meta_val ?>">
    <?php endforeach; ?>

    <!-- Loading css file
        ================================================== -->
    <? foreach ($css_files as $css) { ?>
        <link href="<?= g('css_root') . $css ?>" rel="stylesheet" type="text/css" />
    <? } ?>
    <!-- End loading css file
        ================================================== -->

    <?php
    // Load js file
    if (is_array($js_files_init)) {
        foreach ($js_files_init as $js) { ?>
            <script src="<?= g('js_root') . $js ?>"></script>
    <?
        }
    }

    // Load additional files css
    if (is_array($additional_tools) && count($additional_tools)) {
        foreach ($additional_tools as $tool) {
            if (is_array($my_tools[$tool]['css'])) {
                foreach ($my_tools[$tool]['css'] as $script) {
                    if ($script) {
                        echo '<link rel="stylesheet" href="' . g('plugins_root') . $tool . "/" . $script . '" />';
                    }
                }
            }
        }
    }

    // Load additional files js
    if (array_filled($additional_tools)) {
        foreach ($additional_tools as $tool) {
            if (is_array($my_tools[$tool]['js'])) {
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

<body>
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
    <?php
    if ($this->router->method !== 'redirecting') {
        $this->load->view("_layout/header");
    }

    // Page content Start
    echo $content_block;

    if ($this->router->method !== 'redirecting') {
        $this->load->view("_layout/footer");
    }
    ?>
    <!-- Wrapper End
        ================================================== -->

    <!-- Load js files
        ================================================== -->
    <?php foreach ($js_files as $js) : ?>
        <script src="<?= g('js_root') . $js ?>"></script>
    <?php endforeach; ?>
    <!-- End load js files
        ================================================== -->

    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <?php if ($this->session->flashdata('stripe_error')) {
        echo '<script>swal("' . __('Stripe Error') . '", "' . $this->session->flashdata('stripe_error') . '", "error")</script>';
    } ?>

    <?php if ($this->session->flashdata('error')) {
        echo '<script>swal("' . __('Error') . '", "' . $this->session->flashdata('error') . '", "error")</script>';
    } ?>

    <?php if ($this->session->flashdata('success')) {
        echo '<script>swal("' . __('Success') . '", "' . $this->session->flashdata('success') . '", "success")</script>';
    } ?>

    <?php if ($this->session->flashdata('box_success')) {
        echo '<script>toastr.success("' . $this->session->flashdata('box_success') . '")</script>';
    } ?>

    <?php if ($this->session->flashdata('plaid_reuathenticate')) : ?>
        <script>
            swal({
                title: "<?= __('Warning') ?>",
                text: "<?= $this->session->flashdata('plaid_reuathenticate') ?>",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('Cancel') ?>", "<?= __('Re-authenticate') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    let data = {};
                    let url = base_url + 'plaid/remove_authentication';

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
                                location.href = response.redirect_url;
                            } else {
                                swal("Error", response.txt, "error");
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
                $('[data-toggle="tooltip"]').tooltip()
            })
        })
    </script>

    <?php $this->load->view("_layout/common-scripts"); ?>

    <?php if ($this->router->method !== 'redirecting') : ?>
        <?php $this->load->view('_layout/common-footer.php'); ?>
    <?php endif; ?>

    <script>
        window.onload = function() {
            // Loading Div
            $("#loader").fadeOut(500);

            // ADA plugin
            window.micAccessTool = new MicAccessTool({
                link: 'http://your-awesome-website.com/your-accessibility-declaration.pdf',
                contact: 'mailto:admin@contractorslicense.com',
                buttonPosition: 'left',
                forceLang: 'en-IL'
            });
        }
    </script>

</body>

</html>