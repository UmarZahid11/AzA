<div class="dashboard-content">
    <div class="float-right">
        <a href="javascript:;" id="revoke_token">Logout</a>
    </div>
    <!-- <i class="fa-regular fa-book"></i> -->
    <svg style="color: rgb(232, 151, 255); width:15px;" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <title>QuickBooks</title>
        <path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm.642 4.1335c.9554 0 1.7296.776 1.7296 1.7332v9.0667h1.6c1.614 0 2.9275-1.3156 2.9275-2.933 0-1.6173-1.3136-2.9333-2.9276-2.9333h-.6654V7.3334h.6654c2.5722 0 4.6577 2.0897 4.6577 4.667 0 2.5774-2.0855 4.6666-4.6577 4.6666H12.642zM7.9837 7.333h3.3291v12.533c-.9555 0-1.73-.7759-1.73-1.7332V9.0662H7.9837c-1.6146 0-2.9277 1.316-2.9277 2.9334 0 1.6175 1.3131 2.9333 2.9277 2.9333h.6654v1.7332h-.6654c-2.5725 0-4.6577-2.0892-4.6577-4.6665 0-2.5771 2.0852-4.6666 4.6577-4.6666Z" fill="#e897ff"></path>
    </svg>
    <h4><?= __('Quickbooks') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . QUICKBOOKS_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Plaid Tutorial</a>
    <hr />
    <div class="row mt-5">

        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Customer') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/customer') ?>"><?= __('View Customers') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/customer') ?>"><?= __('Create Customer') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Invoice') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/invoice') ?>"><?= __('View Invoices') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Account') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/account') ?>"><?= __('View Accounts') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/account') ?>"><?= __('Create Account') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Department') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/department') ?>"><?= __('View Departments') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/department') ?>"><?= __('Create Department') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Class') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/class') ?>"><?= __('View Classes') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/class') ?>"><?= __('Create Class') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Vendor') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/vendor') ?>"><?= __('View Vendors') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/vendor') ?>"><?= __('Create Vendor') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Cashflow') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-view/cashflow') ?>"><?= __('View Cashflow Report') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Item') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/item') ?>"><?= __('View Items') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/item') ?>"><?= __('Create Item') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Bill') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/bill') ?>"><?= __('View Bills') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/bill') ?>"><?= __('Create Bill') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Bill Payment') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/billpayment') ?>"><?= __('View Bill Payment') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/billpayment') ?>"><?= __('Create Bill Payment') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Employee') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/employee') ?>"><?= __('View Employees') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/employee') ?>"><?= __('Create Employee') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Time Activity') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-listing/timeactivity') ?>"><?= __('View Time Activities') ?></a></li>
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/home/quickbook-save/timeactivity') ?>"><?= __('Create Time Activity') ?></a></li>
            </ul>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {
        $('body').on('click', '#revoke_token', function() {
            var data = {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
            var url = base_url + 'quickbook/revokeToken'

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
                        swal({
                            title: "Success",
                            text: response.txt,
                            icon: "success",
                        }).then(() => {
                            location.href = base_url + 'dashboard'
                        })
                    } else {
                        swal('Error', response.txt, 'error')
                    }
    		    }
		    )
        })
    })
</script>