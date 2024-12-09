<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($account->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($account->Id) ? $account->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="Name" class="form-control" value="<?= isset($account->Name) ? $account->Name : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'account name') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Account Number <span class="text-danger">*</span></label>
                <input type="text" name="AcctNum" class="form-control" value="<?= isset($account->AcctNum) ? $account->AcctNum : '' ?>" minlength="6" maxlength="20" pattern="([0-9]){6,20}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required with minimum length of 6 and a maximum length of 20.'), 'account number') ?></small>
            </div>
        </div>
        <?php if (isset($taxCodes)) : ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Tax Code</label>
                    <select class="form-select" name="TaxCodeRef[value]">
                        <option value="">Select Tax Code</option>
                        <?php foreach ($taxCodes as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?php echo ((isset($account->TaxCodeRef->value)) && $account->TaxCodeRef->value == $value->Name) ? 'selected' : '' ?>><?= $value->Name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-6">
            <div class="form-group">
                <label>Account Type <span class="text-danger">*</span></label>
                <select class="form-select" name="AccountType" required>
                    <?php if (isset($AccountType)) : ?>
                        <?php $current_group = ''; ?>
                        <?php foreach ($AccountType as $key => $value) : ?>
                            <?php if ($current_group != $value['quickbook_account_repo_parent']) : ?>
                                <?php if ($current_group != '') : ?>
                                    </optgroup>
                                <?php endif; ?>
                                <optgroup label="<?= $value['quickbook_account_repo_parent'] ?>">
                                <?php endif; ?>
                                <option value="<?= $value['quickbook_account_repo_type'] ?>" <?= isset($account->AccountType) && $account->AccountType == $value['quickbook_account_repo_type'] ? 'selected' : '' ?>><?= $value['quickbook_account_repo_type'] ?></option>
                                <?php if ($current_group != $value['quickbook_account_repo_parent']) : ?>
                                    <?php $current_group = $value['quickbook_account_repo_parent'] ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'account type') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Account SubType <span class="text-danger">*</span></label>
                <select class="form-select" name="AccountSubType" required data-selected="<?= isset($account->AccountSubType) ? $account->AccountSubType : '' ?>">
                </select>
                <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'account subtype') ?></small>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>

<script>
    $('document').ready(function() {
        
        var data = {
            'AccountType': $('select[name=AccountType]').val(),
            'selected': $('select[name=AccountSubType]').data('selected')
        };
        var url = base_url + 'dashboard/custom/fetchSubAccounts';

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
                    $('select[name=AccountSubType]').html(response.result)
                } else {
                    AdminToastr.error(response.txt)
                }
		    }
	    )
        
        $('select[name=AccountType]').on('change', function() {
            var data = {
                'AccountType': $(this).val(),
                'selected': $('select[name=AccountSubType]').data('selected')
            };
            var url = base_url + 'dashboard/custom/fetchSubAccounts';

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
                        $('select[name=AccountSubType]').html(response.result)
                    } else {
                        AdminToastr.error(response.txt)
                    }
    		    }
    	    )
        })
    })
</script>