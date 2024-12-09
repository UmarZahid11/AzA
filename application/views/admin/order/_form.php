<?php global $config;
$model_heads = explode(",", $dt_params['dt_headings']);
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-shopping-cart"></i><?= humanize($class_name) ?>
                    <small>Add Details to <?= humanize($class_name) ?></small>

                </div>
                <!--<div class="tools">
          <a href="javascript:;" class="collapse">
          </a>
          <a href="#portlet-config" data-toggle="modal" class="config">
          </a>
          <a href="javascript:;" class="reload">
          </a>
          <a href="javascript:;" class="remove">
          </a>
        </div>-->
            </div>
            <div class="portlet-body form">
                <div class="row note note-danger">
                    <p>
                        <strong>NOTE:</strong> Please make sure only <strong>one "ADD ORDER" page</strong> is open in your browser at a time.
                        And <strong>Do not Add items to Cart from front-end</strong> while this window is open. Thank You!
                    </p>
                </div>
                <!-- BEGIN FORM-->
                <?
                global $config;
                $form = new Tkd_form_helper($class_name);
                $form->title = ucfirst(str_replace("_", " ", $table));
                $form->set_param('form_fields', $form_fields);
                $form->set_param('form_data', $form_data);
                $form->extra_content = $this->load->view("admin/order/product_form", array(), true);
                $form->prepare_form();
                $form->render_form();
                ?>
                <!-- END FORM-->
            </div>
            <!-- END VALIDATION STATES-->
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        Metronic.init(); // init metronic core components
        Layout.init(); // init current layout
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        UIAlertDialogApi.init(); //UI Alert API
        <? if ($error)
            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";
        ?>
    });
</script>