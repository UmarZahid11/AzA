<? global $config;

$dt_params['dt_headings'] = $dt_params['dt_headings'] ? $dt_params['dt_headings'] : $model_obj->pagination_params['fields'];

$model_heads = explode(",", $dt_params['dt_headings']);

echo create_modal_html("view_product", "Detail");

?>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Listing</small></h1>

</div>

<div class="row">

    <div class="col-md-12">

        <div class="portlet">

            <div class="portlet-title">

                <div class="Error">

                </div>

                <div class="actions">

                    <button class="btn btn-primary" id="run-cron-manually" title="Run milestone payment transfers manually, this will transfer all remaining escrow payments." data-toggle="tooltip" data-placement="left">Run cron-job manually</button>

                    <?php if (isset($dt_params['action']['hide_add_button']) && !$dt_params['action']['hide_add_button']) : ?>

                        <a href="<?= $config['base_url'] ?>admin/<?= $class_name ?>/add" class="btn default yellow-stripe">

                            <i class="fa fa-plus"></i>

                            <span class="hidden-480">
                                Add new <?= humanize($class_name) ?>
                            </span>

                        </a>

                    <?php endif; ?>

                </div>

            </div>

            <div class="portlet-body">

                <div class="table-container">

                    <div class="table-actions-wrapper">

                        <span>

                        </span>

                        <select class="table-group-action-input form-control input-inline input-small input-sm" data-update-uri="update_status" data-model="<?= $model_name ?>">

                            <option value="">Select...</option>

                            <option value="<?= STATUS_ACTIVE ?>">Activate Selected</option>

                            <option value="<?= STATUS_INACTIVE ?>">DeActivate Selected</option>

                            <?php if ($dt_params['action']['show_delete']) : ?>

                                <option value="<?= STATUS_DELETE ?>">Delete Selected</option>

                            <?php endif; ?>

                        </select>

                        <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>

                    </div>

                    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                        <thead>

                            <tr role="row" class="heading">

                                <th width="2%">

                                    <input type="checkbox" class="group-checkable">

                                </th>

                                <?php

                                $filters = "";

                                foreach ($model_heads as $field) {

                                    $field = trim($field);

                                    if (!isset($model_fields[$field]))
                                        continue;

                                    $field_attr = $model_fields[$field];

                                    $field_type = (isset($field_attr['type_filter_dt'])) ? $field_attr['type_filter_dt'] : $field_attr['type'];

                                    $dt_attributes = (isset($field_attr['dt_attributes'])) ? $field_attr['dt_attributes'] : '';

                                    // Setting up DT Attributes form fields info

                                    $col_width = (isset($dt_attributes['width'])) ? $dt_attributes['width'] : '';

                                ?>

                                    <th width="<?= $col_width ?>">

                                        <?= $model_fields[$field]['label']; ?>

                                    </th>

                                    <?php

                                    // If field is not  searchable , skip filter

                                    if (is_array($dt_params['searchable']) && in_array($field, $dt_params['searchable'])) {

                                        switch ($field_type) {

                                            case 'dropdown':

                                                $options = '<option value="">SELECT</option>';

                                                $list_data = $field_attr['list_data'];

                                                if (!array_filled($list_data)) {

                                                    $list_data_key = (isset($field_attr['list_data_key'])) ? $field_attr['list_data_key'] : $field;

                                                    $list_data = $this->_list_data[$list_data_key];
                                                }

                                                $options = generate_options_html($list_data);

                                                $filters .= '<td>' .

                                                    '<select class="form-control form-filter input-sm" name="filter[' . $field . ']">' .

                                                    $options .

                                                    '</select>' .

                                                    '</td>';

                                                break;

                                            case 'switch':

                                                $options = '<option value="">SELECT</option>';

                                                $list_data = (isset($field_attr['list_data'])) ? $field_attr['list_data'] : '';

                                                if (!array_filled($list_data)) {

                                                    $list_data = array(

                                                        STATUS_ACTIVE =>  "<span class=\"label label-primary\">Active</span>",

                                                        STATUS_INACTIVE => "<span class=\"label label-default\">Inactive</span>",

                                                    );
                                                }

                                                $options = generate_options_html($list_data);

                                                $filters .= '<td>' .

                                                    '<select class="form-control form-filter input-sm" name="filter[' . $field . ']">' .

                                                    $options .

                                                    '</select>' .

                                                    '</td>';

                                                break;



                                            case 'image':

                                            case 'hidden':

                                                $filters .= '<td></td>';

                                                break;



                                            default:

                                                $filters .= '<td>' .

                                                    '<input type="text" class="form-control form-filter input-sm" name="filter[' . $field . ']">' .

                                                    '</td>';

                                                break;
                                        } // End Filter switch

                                    } // ENd If Filter setable

                                    else

                                        $filters .= '<td></td>';
                                }

                                if (!$dt_params['action']['hide']) {

                                    ?>

                                    <th width="10%">

                                        Actions

                                    </th>

                                <? } ?>

                            </tr>

                            <tr role="row" class="filter">

                                <td>

                                </td>

                                <?= $filters ?>

                                <td>

                                    <div class="margin-bottom-5">

                                        <button class="btn btn-sm yellow filter-submit margin-bottom" title="Apply Filter"><i class="fa fa-search"></i></button>

                                        <button class="btn btn-sm red filter-cancel" title="Reset Filter"><i class="fa fa-eraser"></i> </button>

                                        <button class="btn btn-sm blue refresh-datatable" title="Refresh table data"><i class="fa fa-refresh"></i> </button>

                                    </div>

                                </td>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    $(document).ready(function() {

        Metronic.init(); // init metronic core components

        QuickSidebar.init(); // init quick sidebar

        Demo.init(); // init demo features

        TableAjax.init(); // DataTable API

        UIAlertDialogApi.init(); // UI Alert API

        $('body').on('click', '#run-cron-manually', function() {
            var runCronBtn = '#run-cron-manually'
            var data = {
                'force_manual' : true,
                '_token': $('meta[name=csrf-token]').attr("content")
            }
            var url = '<?= $config['base_url'] . 'job_milestone/milestone_payment_transfer' ?>'

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
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $(runCronBtn).attr('disabled', true)
                        $(runCronBtn).html('Running ...')
                    },
                    complete: function() {
                        $(runCronBtn).attr('disabled', false)
                        $(runCronBtn).html('Run cron-job manually')
                    }
                })
			}).then(
			    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.message)
                        if(response.refresh) {
                            $('#datatable_ajax').dataTable().api().ajax.reload()
                            $('[data-toggle="tooltip"]').tooltip()
                        }
                    } else {
                        $('.Error').html('')
                        $('.Error').append(
                            '<label class="label label-danger">' + response.message + '</label>' +
                            '<p>' + response.reason + '</p>'
                        )
                        AdminToastr.error(response.message)
                    }
			    }
            )
        })

        $('body').on('click', '.refresh-datatable', function(){
			$('#preloader').show()
			$('#datatable_ajax').dataTable().api().ajax.reload()
            $('[data-toggle="tooltip"]').tooltip()
			setTimeout(function(){
				$('#preloader').hide()
			}, 500)
		})

    });
</script>