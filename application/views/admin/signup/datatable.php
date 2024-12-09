<style>
    .rotate {
        transform:rotate(360deg)
    }
    .fa {
        transition: transform .75s;
    }
</style>

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

        <!-- Begin: life time stats -->

        <div class="portlet">

            <div class="portlet-title">

                <!--<div class="caption">

					<i class="fa fa-list"></i><?/*=humanize($class_name)*/ ?> Listing

				</div>-->

                <div class="actions">


                    <? if (isset($dt_params['action']['hide_add_button']) && !$dt_params['action']['hide_add_button']) { ?>

                        <a href="<?= $config['base_url'] ?>admin/<?= $class_name ?>/add" class="btn default yellow-stripe">

                            <i class="fa fa-plus"></i>

                            <span class="hidden-480">

                                Add new <?= humanize($class_name) ?> </span>

                        </a>

                    <? } ?>

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

                            <? if ($dt_params['action']['show_delete']) { ?>

                                <option value="<?= STATUS_DELETE ?>">Delete Selected</option>

                            <? } ?>

                        </select>

                        <button class="btn btn-sm yellow table-group-action-submit"><i class="fa fa-check"></i> Submit</button>

                    </div>

                    <table class="table table-striped table-bordered table-hover" id="datatable_ajax">

                        <thead>

                            <tr role="row" class="heading">

                                <th width="2%">

                                    <input type="checkbox" class="group-checkable">

                                </th>

                                <?

                                $filters = "";

                                foreach ($model_heads as $field) {

                                    $field = trim($field);

                                    //if( !$model_fields[$field] )

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

                                    <?



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

                                                //$value = (isset($value))?$field_attr['list_data'][$value]:'' ;

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

                                                //$value = (isset($value))?$field_attr['list_data'][$value]:'' ;

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

                                    <th width="20%">

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

        <!-- End: life time stats -->

    </div>

</div>

<div class="modal fade extendTrialModal" id="extendTrialModal" tabindex="-1" role="dialog" aria-labelledby="extendTrialModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center">
                    <i class="fa fa-exclamation-circle fa-2x"></i>
                    <p>Are you sure?</p>
                    <form class="userExpiryForm" action="javascript:;" method="POST" novalidate>
                        <input type="hidden" name="_token" />
                        <input type="hidden" name="signup_id" />
                        <input type="hidden" name="type" />
                        <div class="form-group">
                            <label>Extent Free Trial for: "<span id="userPlaceholder">User</span>"</label>
                            <input type="date" class="form-control" name="signup[signup_trial_expiry]" id="signup_trial_expiry" value="" required  />
                        </div>
                        <button type="submit" class="btn btn-primary">Yes</button>
                        <button type="button" class="btn btn-danger cancelBtn">No</button>
                    </form>
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

        TableAjax.init(); //DataTable API

        UIAlertDialogApi.init(); //UI Alert API

        // $('#datatable_ajax').dataTable().api().ajax.reload()

        //
        signup_trial_expiry.min = new Date(Date.now() + (3600 * 1000)).toISOString().split("T")[0];

        //
        $('body').on('click', '.extendTrialBtn', function() {

            var type = $(this).data('type')
            var data = { 'id' : $(this).data('id') }
            var url  = base_url + 'signup/getSignupById'

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
                        $('#preloader').show()
                    },
                    complete: function() {
                        $('#preloader').hide()
                    }
                })
            }).then(
                function(response) {
                    if(response.status) {
                        $('input[name=signup_id]').val(response.data.signup_id)
                        $('input[name=type]').val(type)
                        $('#signup_trial_expiry').val(new Date(new Date(response.data.signup_trial_expiry).getTime() + (3600 * 1000 * 24)).toISOString().split("T")[0])
                        $('#userPlaceholder').html(response.data.signup_email)
                        //
                        $('#extendTrialModal').modal('show')
                        $('.extendTrialModal').show()
                        //
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        })

        //
        $('body').on('submit', '.userExpiryForm', function(event) {
            event.preventDefault()
            if (!$(this)[0].checkValidity()) {
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'signup/saveData'

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
                })
			}).then(
			    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                        $('.modal').hide()
                        $('#datatable_ajax').dataTable().api().ajax.reload()
                    } else {
                        AdminToastr.error(response.txt)
                    }
			    }
		    )
        })

        //
        $('body').on('click', '.approveSignuptoggle', function() {
            var data = {id: $(this).data('id')}
            var url  = base_url + 'signup/approve_disapprove_signup';

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
                        $('#preloader').show()
                    },
                    complete: function() {
                        $('#preloader').hide()
                    }
                })
            }).then(
                function(response) {
                    if(response.status) {
                        $('#datatable_ajax').dataTable().api().ajax.reload();
                        setTimeout(function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 1000);
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        });

        //
        $('body').on('click', '.lifetimeSubscriptiontoggle', function() {
            var data = {id: $(this).data('id')}
            var url  = base_url + 'signup/lifetimeAccess';

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
                        $('#preloader').show()
                    },
                    complete: function() {
                        $('#preloader').hide()
                    }
                })
            }).then(
                function(response) {
                    if(response.status) {
                        $('#datatable_ajax').dataTable().api().ajax.reload();
                        setTimeout(function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        }, 1000);
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        });

        //
        $('body').on('click', '.cancelBtn', function(event) {
            $('.modal').hide()
        })
    });
</script>