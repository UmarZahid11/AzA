<div class="dashboard-content">

    <div class="float-right">
        <a class="plaid_relogin" href="javascript:;" data-toggle="tooltip" title="<?= __('Re-login to plaid') ?>."><?= __('Re-login') ?></a> |

        <?php if (!$this->user_data['signup_is_employment_verified']) : ?>
            <?php echo 'Verify income through '; ?>
            <a href="<?= l('plaid/link/' . PLAID_TYPE_INCOME . '/' . PLAID_BANK_INCOME) ?>" data-toggle="tooltip" title="<?= __('Confirm your employment by connecting with your bank (applicable for USA and Canada only).') ?>.">
                <?= __('Bank') ?>
            </a>
            OR
            <a href="<?= l('plaid/link/' . PLAID_TYPE_INCOME . '/' . PLAID_PAYROLL_INCOME) ?>" data-toggle="tooltip" title="<?= __('Confirm your employment by verifying your payroll (applicable for USA and Canada only).') ?>.">
                <?= __('Payroll') ?>
            </a>
        <?php else : ?>
            <a ><i class="fa fa-check-circle"></i>&nbsp; Employment verified</a>
        <?php endif; ?>
    </div>

    <i class="fa-regular fa-bank"></i>
    <h4><?= __('Plaid') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . PLAID_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Plaid Tutorial</a>
    <hr />
    <div class="row mt-5">

        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Accounts') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/accounts') ?>" target="_blank"><?= __('View Accounts') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Categories') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/categories') ?>" target="_blank"><?= __('View Categories') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Institutions') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/institutions') ?>" target="_blank"><?= __('View Institutions') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Items') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/items') ?>" target="_blank"><?= __('View Items') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Liabilities') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/liabilities') ?>" target="_blank"><?= __('View Liabilities') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Transactions') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/transactions') ?>" target="_blank"><?= __('View Transactions') ?></a></li>
            </ul>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <h4><?= __('Identity') ?></h4>
            <ul class="px-5">
                <li>&centerdot;&nbsp;<a href="<?= l('dashboard/plaid/listing/identity') ?>" target="_blank"><?= __('View Identities of account') ?></a></li>
            </ul>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('click', '.plaid_relogin', function() {
            var data = {
                "_token": "<?= $this->csrf_token; ?>"
            }
            var url = base_url + 'dashboard/plaid/relogin'

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
                        swal({
                            title: "Success",
                            text: response.txt,
                            icon: "success",
                        }).then(() => {
                            location.reload()
                        })
                    } else {
                        swal("<?= __('Error') ?>", response.txt, "error");
                    }
    		    }
		    )
        })
    })
</script>