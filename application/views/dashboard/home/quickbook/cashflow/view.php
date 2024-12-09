<div class="dashboard-content">
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Report') ?></h4>
    <hr />
    <form id="cashflowReportForm" action="javascript:;">
        <div class="row">
            <h4>Add Filter</h4>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Start date</label>
                    <input type="date" id="start_date" name="start_date" class="form-control" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" id="end_date" name="end_date" class="form-control" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Customer</label>
                    <select class="form-select" name="customer[Id]">
                        <option value="">Select customer</option>
                        <?php if (isset($customer) && is_array($customer) && count($customer) > 0) : ?>
                            <?php foreach ($customer as $key => $value) : ?>
                                <option value="<?= $value->Id ?>"><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Vendor</label>
                    <select class="form-select" name="vendor[Id]">
                        <option value="">Select vendor</option>
                        <?php if (isset($vendor) && is_array($vendor) && count($vendor) > 0) : ?>
                            <?php foreach ($vendor as $key => $value) : ?>
                                <option value="<?= $value->Id ?>"><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Class</label>
                    <select class="form-select" name="class[Id]">
                        <option value="">Select class</option>
                        <?php if (isset($class) && is_array($class) && count($class) > 0) : ?>
                            <?php foreach ($class as $key => $value) : ?>
                                <option value="<?= $value->Id ?>"><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Item</label>
                    <select class="form-select" name="item[Id]">
                        <option value="">Select item</option>
                        <?php if (isset($item) && is_array($item) && count($item) > 0) : ?>
                            <?php foreach ($item as $key => $value) : ?>
                                <option value="<?= $value->Id ?>"><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Department</label>
                    <select class="form-select" name="department[Id]">
                        <option value="">Select department</option>
                        <?php if (isset($department) && is_array($department) && count($department) > 0) : ?>
                            <?php foreach ($department as $key => $value) : ?>
                                <option value="<?= $value->Id ?>"><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Sort order</label>
                    <select class="form-select" name="sort_order">
                        <option value="">Select sort_order</option>
                        <option value="ascend">ascend</option>
                        <option value="descend">descend</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Summarize column by</label>
                    <select class="form-select" name="summarize_column_by">
                        <option value="">Select summarize_column_by</option>
                        <option value="Total">Total</option>
                        <option value="Month">Month</option>
                        <option value="Week">Week</option>
                        <option value="Days">Days</option>
                        <option value="Quarter">Quarter</option>
                        <option value="Year">Year</option>
                        <option value="Customers">Customers</option>
                        <option value="Vendors">Vendors</option>
                        <option value="Classes">Classes</option>
                        <option value="Departments">Departments</option>
                        <option value="Employees">Employees</option>
                        <option value="ProductsAndServices">ProductsAndServices</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <button type="submit" class="btn btn-custom">Generate Report</button>
            </div>
        </div>
    </form>
    <hr />
    <h4>Report</h4>
    <div id="reportSection">
        <table id="cashFlowTable" class="style-1">

        </table>

    </div>
</div>

<script>
    $('body').on('change', '#start_date', function() {
        end_date.min = $(this).val()
    })

    $('body').on('submit', '#cashflowReportForm', function() {

        var data = $(this).serialize();
        var url = base_url + 'quickbook/generateCashFlow'

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
                    showLoader()
                },
                complete: function() {
                    hideLoader()
                }
            })
		}).then(
		    function(response) {
                if (response.status) {
                    if(response.result != undefined && response.result) {
                        console.log(response.result.Rows.Row)
                        var obj = response.result.Rows.Row
        
                        $('#cashFlowTable').html("")
                        var appendingHTML = '';
                        appendingHTML += '<tbody>';
        
                        if(obj.length > 0) {
                            for(i = 0; i < obj.length; i++ ) {
        
                                //
                                if(obj[i].Header != undefined) {
                                    for(j = 0; j < obj[i].Header.ColData.length; j++) {
                                        if(obj[i].Header.ColData[j].value) {
                                            appendingHTML += '<tr>'
                                            appendingHTML += '<th>' + obj[i].Header.ColData[j].value + '</th><th></th><th></th>';
                                            appendingHTML += '</tr>'
                                        }
                                    }
                                }
        
                                //
                                if(obj[i].Rows != undefined) {
        
                                    if(obj[i].Rows.Row != undefined) {
                                        var subobj = obj[i].Rows.Row;
                                        for(j = 0; j < subobj.length; j++) {
        
                                            if(subobj[j].Header != undefined) {
                                                for(k = 0; k < subobj[j].Header.ColData.length; k++) {
                                                    if(subobj[j].Header != undefined) {
                                                        if(subobj[j].Header.ColData[k].value) {
                                                            appendingHTML += '<tr>'
                                                            appendingHTML += '<td>' + subobj[j].Header.ColData[k].value + '</td><td></td><td></td>';
                                                            appendingHTML += '</tr>'
                                                        }
                                                    }
                                                }
                                            }
        
                                            if(subobj[j].Rows != undefined) {
                                                for(k = 0; k < subobj[j].Rows.Row.length; k++) {
                                                    appendingHTML += '<tr>'
                                                    if(subobj[j].Rows.Row[k] != undefined) {
                                                        appendingHTML += '<td>' + subobj[j].Rows.Row[k].ColData[0].value + '</td>';
                                                        appendingHTML += '<td>' + subobj[j].Rows.Row[k].ColData[1].value + '</td><td></td>';
                                                    }
                                                    appendingHTML += '</tr>'
                                                }
                                            }
                                        }
                                    }
                                }
        
                                //
                                if(obj[i].Summary != undefined) {
                                    appendingHTML += '<tr class="bg-custom"><td>' + obj[i].Summary.ColData[0].value +'</td>' +
                                    '<td></td><td>' + obj[i].Summary.ColData[1].value +'</td></tr>'
                                }
        
                                //
                                if(obj[i].ColData != undefined) {
                                    appendingHTML += '<tr class="bg-custom"><td>' + obj[i].ColData[0].value +'</td>' +
                                    '<td></td><td>' + obj[i].ColData[1].value +'</td></tr>'
                                }
                            }
                            appendingHTML += '</tbody>';
                            $('#cashFlowTable').append(appendingHTML)
        
                        } else {
                            $('#cashFlowTable').append('<tr><td>No result found.</td></tr>')
                        }
                    }
                } else {
                    swal({
                        title: "Error",
                        text: response.txt[0] == undefined ? response.txt : response.txt[0],
                        icon: "warning"
                    }).then((isConfirm) => {
                        if (response.refresh) {
                            location.reload();
                        }
                    });
                }
		    }
	    )
    })
</script>