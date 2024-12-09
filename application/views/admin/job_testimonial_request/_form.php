<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" integrity="sha512-xmGTNt20S0t62wHLmQec2DauG9T+owP9e6VU8GigI0anN7OXLip9i7IwEhelasml2osdxX71XcYm6BQunTQeQg==" crossorigin="anonymous" />

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

    .ui-autocomplete {
        width: 25% !important;
        left: 415px !important;
        background: white !important;
    }
</style>

<? global $config;

$model_heads = explode(",", $dt_params['dt_headings']);

?>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Record </small></h1>

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
    $(document).ready(function() {

        Metronic.init(); // init metronic core components

        QuickSidebar.init(); // init quick sidebar

        Demo.init(); // init demo features

        UIAlertDialogApi.init(); //UI Alert API

        <? if (isset($error))
            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";
        ?>
    });
</script>

<script>
    $(document).ready(function() {
        document.getElementById("job_testimonial_request-job_testimonial_request_extention").min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];

        if ($('#job_testimonial_request-job_testimonial_request_current_status').val() == '<?= REQUEST_EXTENDED ?>') {
            $("#job_testimonial_request-job_testimonial_request_extention").attr('disabled', false)
            $("#job_testimonial_request-job_testimonial_request_extention").attr('required', true)
        } else {
            $("#job_testimonial_request-job_testimonial_request_extention").attr('disabled', true)
            $("#job_testimonial_request-job_testimonial_request_extention").attr('required', false)
        }

        $('#job_testimonial_request-job_testimonial_request_current_status').on('change', function() {
            if ($(this).val() == '<?= REQUEST_EXTENDED ?>') {
                $("#job_testimonial_request-job_testimonial_request_extention").attr('disabled', false)
                $("#job_testimonial_request-job_testimonial_request_extention").attr('required', true)
            } else {
                $("#job_testimonial_request-job_testimonial_request_extention").attr('disabled', true)
                $("#job_testimonial_request-job_testimonial_request_extention").attr('required', false)
            }
        })
    })
</script>