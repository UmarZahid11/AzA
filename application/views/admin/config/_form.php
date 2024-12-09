<?php

global $config;
$model_heads = explode(",", (isset($dt_params['dt_headings'])) ? $dt_params['dt_headings'] : '');

?>

<style>
    .form-login input[type="text"], input[type="email"], input[type="password"] {
        padding: 0px 0 0 11px;
        color: #3f3f3f;
    }
</style>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Record</small></h1>

</div>

<div class="row">

    <div class="col-md-12">

        <!-- BEGIN VALIDATION STATES-->

        <div class="portlet box green">

            <div class="portlet-title">

                <div class="caption">

                    <i class="fa fa-cog"></i><?= humanize($class_name) ?>

                    <small>Add Details to <?= humanize($class_name) ?></small>

                </div>

                <div class="tools">

                    <a href="javascript:;" class="collapse">

                    </a>

                    <!-- <a href="#portlet-config" data-toggle="modal" class="config">

                    </a> -->

                    <a href="javascript:;" class="reload">

                    </a>

                    <!-- <a href="javascript:;" class="remove">

                    </a> -->

                </div>

            </div>

            <div class="portlet-body form">

                <!-- BEGIN FORM-->

                <form class="cmxform form-horizontal tasi-form" id="<?= (isset($form_id)) ? $form_id : '' ?>" method="POST" action="" enctype="multipart/form-data">

                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />

                    <div class="form-body">

                        <div class="alert alert-danger display-hide">

                            <button class="close" data-close="alert"></button>

                            You have some form errors. Please check below.

                        </div>

                        <div class="alert alert-success display-hide">

                            <button class="close" data-close="alert"></button>

                            Your form validation is successful!

                        </div>

                        <h3 class="form-section">SET CONFIGURATION PARAMETERS</h3>

                        <div>

                            <?php foreach ($configuration as $key => $argv) : ?>
                                <h4><?= strtoupper($key) ?></h4>
                                <hr />
                                
                                <div class="row">

                                    <?php foreach ($argv as $syscon) : ?>
                                        
                                        <div class="col-md-6">

                                            <div class="form-group" data-toggle="tooltip" title="<?= isset($syscon['config_comment']) && $syscon['config_comment'] ? '(' . $syscon['config_comment'] . ')' : '' ?>">

                                                <label class="col-md-3 control-label " for="con<?= $syscon['config_id'] ?>">
        
                                                    <?= humanize($syscon['config_variable']) ?> <?= $syscon['config_isrequired'] ? '<span class="text-danger">*</span>' : ''; ?>
        
                                                </label>
        
                                                <div class="col-md-9">
        
                                                    <?php if (in_array($syscon['config_constraint'], ['text', 'checkbox', 'number', 'email', 'url'])) : ?>
        
                                                        <input type="<?= $syscon['config_constraint'] ?>" <?= $syscon['config_isrequired'] ? 'required' : ''; ?>
                                                            <?= $syscon['config_constraint'] == 'number' ? 'step="0.1"' : '' ?>
                                                            <?= $syscon['config_constraint'] == 'number' ? ('min="' . (isset($syscon['config_min_value']) ? (int) $syscon['config_min_value'] : '0') . '"') : '' ?>
                                                            <?= $syscon['config_constraint'] == 'number' ? ('max="' . (isset($syscon['config_max_value']) ? (int) $syscon['config_max_value'] : '') . '"') : '' ?>
                                                            name="config_attr[<?= $syscon['config_id'] ?>]"
                                                            value="<?= $syscon['config_value'] ?>" id="con<?= $syscon['config_id'] ?>"
                                                            <?= $syscon['config_isreadonly'] ? 'readonly' : ''; ?>
                                                            <?= ($syscon['config_constraint'] && ($syscon['config_constraint'] && ($syscon['config_value'] == '1')) == 'checkbox') ? 'checked' : '' ?>
                                                            class="form-control"
                                                        />
        
                                                    <?php elseif ($syscon['config_constraint'] == 'toggle') : ?>
        
                                                        <select class="form-control" name="config_attr[<?= $syscon['config_id'] ?>]">
                                                            <option value="1" <?= (isset($syscon['config_value']) && ($syscon['config_value'] == '1') ? 'selected' : '') ?>>Yes</option>
                                                            <option value="0" <?= (isset($syscon['config_value']) && ($syscon['config_value'] == '0') ? 'selected' : '') ?>>No</option>
                                                        </select>
        
                                                    <?php endif; ?>
        
                                                </div>
                                                
                                            </div>
                                            
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                                <hr />

                            <?php endforeach; ?>

                        </div>

                        <script>
                            $().ready(function() {

                                // validate the <?= (isset($form_id)) ? $form_id : '' ?>

                                $("#<?= (isset($form_id)) ? $form_id : '' ?>").validate({

                                    rules: {
                                        <?= rtrim((isset($validation_string)) ? $validation_string : '', ",") ?>
                                    },

                                    errorElement: 'span',

                                    errorClass: 'has-error help-block',

                                    highlight: function(element, errorClass, validClass) {

                                        $(element).closest(".form-group").addClass("has-error");

                                    },

                                    unhighlight: function(element, errorClass, validClass) {

                                        $(element).closest(".form-group").removeClass("has-error");

                                    },

                                    invalidHandler: function(event, validator) {

                                        // 'this' refers to the form AdminToastr

                                        var errors = validator.numberOfInvalids();

                                        console.log(errors);

                                        if (errors) {

                                            var message = 'Failed to validate form. Total of ' + errors + ' invalid fields found.';

                                            AdminToastr.error(message, "Form Submission Failed");

                                        }

                                    }

                                });

                            });
                        </script>

                        <div class="form-actions">

                            <div class="row">

                                <div class="col-md-offset-3 col-md-9">

                                    <button type="submit" class="btn green">Save</button>

                                    <!--<button type="button" class="btn default">Cancel</button>-->

                                </div>

                            </div>

                        </div>

                </form>

                <!-- END FORM-->

            </div>

            <!-- END VALIDATION STATES-->

        </div>

    </div>

</div>

</div>

<script>
    $(document).ready(function() {

        Metronic.init(); // init metronic core components

        QuickSidebar.init(); // init quick sidebar

        Demo.init(); // init demo features

        UIAlertDialogApi.init(); //UI Alert API

        <? if ((isset($error)))

            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";

        ?>

    });
</script>