<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'.$entity.'/' . $account->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $account->Id ?></li>
                    <li><b>Account:</b> <?= $account->FullyQualifiedName ?></li>
                    <li><b>Sub-Account:</b> <?= $account->SubAccount ? 'Yes' : 'No' ?></li>
                    <li><b>Account Number:</b> <?= $account->AcctNum ?></li>
                    <li><b>Classification:</b> <?= $account->Classification ?></li>
                    <li><b>Account Type:</b> <?= $account->AccountType ?></li>
                    <li><b>Account Sub Type:</b> <?= $account->AccountSubType ?></li>
                    <li><b>Current Balance:</b> <?= $account->CurrencyRef . ' ' . $account->CurrentBalance ?></li>
                    <li><b>Current Balance With Sub-Accounts:</b> <?= $account->CurrencyRef . ' ' . $account->CurrentBalanceWithSubAccounts ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Tax Account:</b> <?= $account->TaxAccount ?></li>
                    <li><b>Tax CodeRef:</b> <?= $account->TaxCodeRef ?></li>
                    <li><b>Create Time:</b> <?= $account->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $account->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>