var TableAjax = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

    var handleRecords = function () {

        var update_url = $js_config.base_url + "admin/"+ $js_config.paginate.class + "/" + $js_config.paginate.update_status_uri;

        var grid = new Datatable();

        var instantiateDelete = function (grid) {
            gridTable = grid.gettableContainer();
            gridTable.on("click",".btn_delete_product", function() {
                var delete_obj = ($(this));
                var onAgree = function () {
                    params = {} ;
                    params.idList = Array();
                    params.idList[0] = delete_obj.attr("data-pk");
                    params.updateVal = 2;
                    params.model = delete_obj.attr("data-model");
                    AjaxRequest.fire(update_url , params);
                    grid.getDataTable().ajax.reload();
                    if( AjaxRequest.response.affected > 0 )
                    {
                        var message = AjaxRequest.response.affected + " Record(s) Deleted" ;
                        AdminAlert.success(message, grid.getTableWrapper());
                    }
                };
                val = UIAlertDialogApi.promptDelete(onAgree);
            });
        };

        // Instantiate VIEW buttons
        var instantiateView = function (grid) {
            gridTable = grid.gettableContainer();
            gridTable.on("click",".btn_view_product", function() {
                var view_obj = ($(this));
                var modal_obj = $(".view_product_modal");
                var url = view_obj.attr("data-href");
                var response = AjaxRequest.fire(url,{});
                var modal_body = modal_obj.find(".modal-body");
                var div = $("<div>").addClass("table-scrollable");
                var table = $("<table>").addClass("table table-hover");
                modal_obj.modal();
                modal_body.html(div);
                div.append(table);

                $.each(response.record,function (head,value) {
                    var row = $("<tr>").
                                append( $("<th>").html(head) ).
                                append($("<td>").html(value));
                    table.append(row);
                });
                if(response.prepend)
                    modal_body.prepend(response.prepend);
                if(response.append)
                    modal_body.append(response.append);
            });

            $('.view_product_form').on("click","[data-dismiss='modal']", function() {
                grid.getDataTable().ajax.reload();
            });
        };

        // Executes everytime Grid is loaded
        var onDraw = function(grid) {
            instantiateDelete(grid);
            instantiateView(grid);
            $('[data-toggle="tooltip"]').tooltip();
        };

        var onSuccess = function(grid) {
        };

        grid.init({
            src: $("#datatable_ajax"),
            onSuccess: onSuccess,
            onDraw: onDraw,
            onError: function (grid) {
                // execute some code on network or other general error
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                // So when dropdowns used the scrollable div should be removed.
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.
                "lengthMenu": [
                    [10, 20, 50, 100, 150, -1],
                    [10, 20, 50, 100, 150, "All"] // change per page values here
                ],
                "pageLength": 20, // default record count per page
                "ajax": {
                    "url": $js_config.base_url+"admin/"+$js_config.paginate.class+"/"+$js_config.paginate.uri, // ajax source
                },
                "order": [
                    [1, "desc"]
                ] // set first column as a default sort by asc
            }
        });

        // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());

            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {

                var ajaxParams = {} ;
                ajaxParams.model =  action.attr("data-model") ;
                ajaxParams.updateVal =  action.val() ;
                ajaxParams.idList =  grid.getSelectedRows() ;
                AjaxRequest.fire(update_url, ajaxParams);
                grid.getDataTable().ajax.reload();
                if( AjaxRequest.response.affected > 0 )
                {
                    var message = "Total " + AjaxRequest.response.affected + " Records Updated" ;
                    AdminToastr.success(message, "Successful!");
                }

            } else if (action.val() == "") {
                AdminAlert.failure('Please select an action', grid.getTableWrapper());
            } else if (grid.getSelectedRowsCount() === 0) {
                AdminAlert.failure('No record selected', grid.getTableWrapper());
            }
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            initPickers();
            handleRecords();
        }

    };

}();