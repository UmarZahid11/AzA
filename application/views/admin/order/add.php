<? global $config;
$model_heads = explode(",", $dt_params['dt_headings']);
?>
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="tabbable tabbable-custom boxless tabbable-reversed">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_0" data-toggle="tab">
                        Details </a>
                </li>
            </ul>
            <div class="tab-content">

                <div class="tab-pane active" id="tab_0">
                    <?= $this->load->view("admin/order/_form"); ?>
                    <!-- END FORM-->
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        <? if ($error)
            echo "AdminToastr.error('" . str_replace("\n", "", validation_errors('<div>', '</div></br>')) . "');";
        ?>
    });
</script>