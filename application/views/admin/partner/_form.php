<?global $config;
$model_heads = explode("," , $dt_params['dt_headings'] );
?>

<style>
    /*table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 80%;
        margin: 0 auto;
    }

    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 15px;
        width: 50%;
    }
    .addons a {
    text-shadow: none;
    color: #ffffff;
}

    td:first-child{
        font-weight: bold;
    }

    tr:nth-child(odd) {
        background-color: #dddddd;
    }
    .image-box .fa-eye{font-size: 18px;}
    .big-image{width: 100%;}*/


</style>

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="tabbable tabbable-custom boxless tabbable-reversed">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_0" data-toggle="tab">
                        Partner Details </a>
                </li>
                <?if($form_data){?>
                    <li class="">
                        <a href="#tab_1" data-toggle="tab">
                            Partner Images </a>
                    </li>

                    <!-- <li class="">
                        <a href="#tab_2" data-toggle="tab">Videos
                            </a>
                    </li>
 -->


                <?}?>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_0">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-image"></i>
                                <small>Add Details to <?=humanize($class_name)?></small>
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
                            <?$this->load->view("admin/widget/form_generator");?>
                            <!-- END FORM-->
                        </div>
                        <!-- END VALIDATION STATES-->
                    </div>
                </div>
                <?
                // Images only in edit mode.
                if($form_data){?>
                    <div class="tab-pane" id="tab_1">
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-image"></i><?=humanize($class_name)?>
                                    <small><?= __('Logo') ?></small>

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
                                <?$this->load->view("admin/widget/upload_partner_images");?>
                                <!-- END FORM-->
                            </div>
                            <!-- END VALIDATION STATES-->
                        </div>
                    </div>

                    <div class="tab-pane" id="tab_2">
                        <div class="portlet box green">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-shopping-cart"></i>
                                    <small><?//humanize($class_name)?> Add-ons</small>
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>
                                    <a href="javascript:;" class="reload">
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body form">
                                <? //$this->load->view("admin/widget/uploadvideos");?>
                            </div>
                        </div>
                    </div>



                <?}?>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        // $('#product-product_old_price').parent().parent().hide('slow');
        //$('.fancybox').fancybox();
        Metronic.init(); // init metronic core components
        QuickSidebar.init(); // init quick sidebar
        Demo.init(); // init demo features
        UIAlertDialogApi.init(); //UI Alert API
        FormFileUpload.init();
        if(<?=$id?>) {
            $('.tabbable li a[href=\#tab_1]').click();
        }

        if(!<?=$id?>) // when add product detail, disabled images and item set tab
        {
            $('.tabbable li a[href=\#tab_1]').css({"background-color": "#CFD1CF",
                "color": "#fff"
            });
            $('.tabbable li a[href=\#tab_2]').css({"background-color": "#CFD1CF",
                "color": "#fff"
            });
            $('.tabbable li a[href=\#tab_1]').click(false);
            $('.tabbable li a[href=\#tab_2]').click(false);
        }

    });
</script>
