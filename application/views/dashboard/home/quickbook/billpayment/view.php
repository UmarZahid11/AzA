<?php
$this->dataService = $this->session->userdata['quickbook']['service_instance'];
$VendorRef = NULL;
if (property_exists($billpayment, 'VendorRef')) {
    $VendorRef = $this->dataService->FindbyId('vendor', $billpayment->VendorRef);
}
?>

<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/' . $entity . '/' . $billpayment->Id) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />

    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $billpayment->Id ?></li>
                    <li><b>Vendor:</b> <?= $VendorRef->FullyQualifiedName ?? $VendorRef->DisplayName ?></li>
                    <li><b>PayType:</b> <?= $billpayment->PayType ?></li>
                    <li><b>TotalAmt:</b> <?= $billpayment->CurrencyRef . ' ' . $billpayment->TotalAmt ?></li>
                    <li><b>TxnDate:</b> <?= $billpayment->TxnDate ?></li>
                    <?php if(property_exists($billpayment, 'CreditCardPayment') && $billpayment->CreditCardPayment): ?>
                        <?php if(property_exists($billpayment->CreditCardPayment, 'CCAccountRef')): ?>
                            <?php $CCAccountRef = $this->dataService->FindbyId('account', $billpayment->CreditCardPayment->CCAccountRef); ?>
                            <?php if($CCAccountRef && property_exists($CCAccountRef, 'Name')): ?>
                                <li><b>Account Name:</b> <?= $CCAccountRef->Name; ?></li>
                                <li><b>AccountType:</b> <?= $CCAccountRef->AccountType; ?></li>
                                <li><b>AccountSubType:</b> <?= $CCAccountRef->AccountSubType; ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if(property_exists($billpayment, 'CheckPayment') && $billpayment->CheckPayment): ?>
                        <?php if(property_exists($billpayment->CheckPayment, 'BankAccountRef')): ?>
                            <?php $BankAccountRef = $this->dataService->FindbyId('account', $billpayment->CheckPayment->BankAccountRef); ?>
                            <?php if($BankAccountRef && property_exists($BankAccountRef, 'Name')): ?>
                                <li><b>Account Name:</b> <?= $BankAccountRef->Name; ?></li>
                                <li><b>AccountType:</b> <?= $BankAccountRef->AccountType; ?></li>
                                <li><b>AccountSubType:</b> <?= $BankAccountRef->AccountSubType; ?></li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Create Time:</b> <?= $billpayment->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $billpayment->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
            <?php if (property_exists($billpayment, 'Line')) : ?>
                <?php if (is_array($billpayment->Line)) : ?>
                    <?php foreach ($billpayment->Line as $key => $value) : ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>