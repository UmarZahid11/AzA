<? global $config;

$model_heads = explode(",", $dt_params['dt_headings']);

?>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Record</small></h1>

</div>

<div class="row">

    <div class="col-md-12">

        <!-- BEGIN VALIDATION STATES-->

        <div class="tabbable tabbable-custom boxless tabbable-reversed">

            <ul class="nav nav-tabs">

                <li class="active">

                    <a href="#tab_0" data-toggle="tab">

                        CMS Page </a>

                </li>

                <?

                if (isset($form_data['cms_page']['cms_page_is_image'])) { ?>

                    <li>

                        <a href="#tab_1" data-toggle="tab">

                            Image </a>

                    </li>

                    <?

                    /*

              ?>

              <li>

                <a href="#tab_2" data-toggle="tab">

                Bottle Size </a>

              </li>

              <li>

                <a href="#tab_3" data-toggle="tab">

                Product Weight </a>

              </li>

              <?

              */

                    ?>

                <? } ?>

            </ul>

            <div class="tab-content">

                <div class="tab-pane active" id="tab_0">

                    <div class="portlet box green">

                        <div class="portlet-title">

                            <div class="caption">

                                <i class="fa fa-desktop"></i><?= humanize($class_name) ?>

                                <small>Add Details to <?= humanize($class_name) ?></small>

                            </div>

                            <!--<div class="tools">

                    <a href="javascript:;" class="collapse">

                    </a>

                    <a href="javascript:;" class="reload">

                    </a>

                  </div>-->

                        </div>

                        <div class="portlet-body form">

                            <!-- BEGIN FORM-->

                            <? $this->load->view("admin/widget/form_generator"); ?>

                            <!-- END FORM-->

                        </div>

                        <!-- END VALIDATION STATES-->

                    </div>

                </div>

                <?

                // Images only in edit mode.

                if (isset($form_data['cms_page']['cms_page_is_image'])) { ?>

                    <div class="tab-pane" id="tab_1">

                        <div class="portlet box green">

                            <div class="portlet-title">

                                <div class="caption">

                                    <i class="fa fa-desktop"></i><?= humanize($class_name) ?>

                                </div>

                                <div class="tools">

                                    <a href="javascript:;" class="collapse">

                                    </a>

                                    <a href="javascript:;" class="reload">

                                    </a>

                                </div>

                            </div>

                            <div class="portlet-body form">

                                <!-- BEGIN FORM-->

                                <? $this->load->view("admin/cms_page/image"); ?>

                                <!-- END FORM-->

                            </div>

                            <!-- END VALIDATION STATES-->

                        </div>

                    </div>

                <? } ?>

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

        FormFileUpload.init();
    })

</script>