<?php global $config;

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

                    <i class="fa fa-edit"></i><?= humanize($class_name) ?>

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

<script>
    $(document).ready(function() {

        Metronic.init(); // init metronic core components

        QuickSidebar.init(); // init quick sidebar

        Demo.init(); // init demo features

        UIAlertDialogApi.init(); //UI Alert API

        <? if (isset($error))

            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";

        ?>
        
        document.getElementById('coaching-coaching_start_time').min = new Date().toISOString().slice(0, -8);

    });
</script>