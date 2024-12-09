<?php
$this->dataService = $this->session->userdata['quickbook']['service_instance'];
$VendorRef = NULL;
if (property_exists($bill, 'VendorRef')) {
    $VendorRef = $this->dataService->FindbyId('vendor', $bill->VendorRef);
}
?>

<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/' . $entity . '/' . $bill->Id) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />

    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $bill->Id ?></li>
                    <li><b>Balance:</b> <?= $bill->CurrencyRef . ' ' . $bill->Balance ?></li>
                    <li><b>TxnDate:</b> <?= $bill->TxnDate ?></li>
                    <li><b>TotalAmt:</b> <?= $bill->TotalAmt ?></li>
                    <li><b>Vendor:</b> <?= $VendorRef->FullyQualifiedName ?? $VendorRef->DisplayName ?></li>
                    <li><b>DueDate:</b> <?= $bill->DueDate ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Create Time:</b> <?= $bill->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $bill->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>

            <?php if (property_exists($bill, 'Line')) : ?>
                <div class="col-md-12">
                    <h5><?= __('Account & customer') ?></h5>
                    <?php
                    $class = NULL;
                    $taxCode = NULL;
                    $customer = NULL;
                    ?>
                    <?php if (is_array($bill->Line)) : ?>
                        <?php foreach ($bill->Line as $key => $value) : ?>

                            <?php if (property_exists($value, 'DetailType')) : ?>

                                <?php $detailType = $value->{'DetailType'}; ?>
                                <div class="card text-left" style="border:0; border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                    <div class="card-body">

                                        <?php if (property_exists($value->{$detailType}, 'AccountRef')) : ?>
                                            <?php $account = $this->dataService->FindbyId('account', $value->{$detailType}->AccountRef); ?>
                                            <h6 class="card-title"><?= $account->Name ?> <span class="float-right"><?= $bill->CurrencyRef . ' ' . $value->Amount ?>&nbsp;<span>(<?= $value->{$detailType}->BillableStatus ?>)</span></span></h6>
                                            <?php if (property_exists($value->{$detailType}, 'CustomerRef')) :
                                                $customer = $this->dataService->FindbyId('account', $value->{$detailType}->CustomerRef);
                                            ?>
                                                <?php if ($customer) : ?>
                                                    <p><?= (property_exists($customer, 'FullyQualifiedName') && $customer->FullyQualifiedName) ? $customer->FullyQualifiedName : (property_exists($customer, 'DisplayName') ? $customer->DisplayName : ''); ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php $class = NULL; ?>
                                            <?php if (property_exists($value->{$detailType}, 'ClassRef')) :
                                                $class = $this->dataService->FindbyId('class', $value->{$detailType}->ClassRef);
                                            ?>
                                                <?php if ($class) : ?>
                                                    <p><?= 'Class: ' . (property_exists($class, 'FullyQualifiedName') ? ($class->FullyQualifiedName) : '') ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <p><?= 'TaxCode: ' . $value->{$detailType}->TaxCodeRef ?></p>
                                        <?php endif; ?>

                                        <?php if (property_exists($value->{$detailType}, 'ItemRef')) : ?>
                                            <?php $item = $this->dataService->FindbyId('item', $value->{$detailType}->ItemRef); ?>
                                            <h6 class="card-title"><?= $item->Name ?> <span class="float-right"><?= $bill->CurrencyRef . ' ' . $value->Amount ?></span></h6>
                                            <small class="card-text"><?= $bill->CurrencyRef . ' ' . $value->{$detailType}->UnitPrice . ' x ' . $value->{$detailType}->Qty ?></small>
                                        <?php endif; ?>

                                    </div>
                                </div>

                            <?php endif; ?>

                        <?php endforeach; ?>
                    <?php else : ?>
                        <?php $value = $bill->Line; ?>

                        <?php if (property_exists($value, 'DetailType')) : ?>

                            <?php $detailType = $value->{'DetailType'}; ?>
                            <div class="card text-left" style="border:0; border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-body">

                                    <?php if (property_exists($value->{$detailType}, 'AccountRef')) : ?>
                                        <?php $account = $this->dataService->FindbyId('account', $value->{$detailType}->AccountRef); ?>
                                        <h6 class="card-title"><?= $account->Name ?> <span class="float-right"><?= $bill->CurrencyRef . ' ' . $value->Amount ?>&nbsp;<span>(<?= $value->{$detailType}->BillableStatus ?>)</span></span></h6>
                                        <?php if (property_exists($value->{$detailType}, 'CustomerRef')) :
                                            $customer = $this->dataService->FindbyId('account', $value->{$detailType}->CustomerRef);
                                        ?>
                                            <?php if ($customer) : ?>
                                                <p><?= (property_exists($customer, 'FullyQualifiedName') && $customer->FullyQualifiedName) ? $customer->FullyQualifiedName : (property_exists($customer, 'DisplayName') ? $customer->DisplayName : ''); ?></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php $class = NULL; ?>
                                        <?php if (property_exists($value->{$detailType}, 'ClassRef')) :
                                            $class = $this->dataService->FindbyId('class', $value->{$detailType}->ClassRef);
                                        ?>
                                            <?php if ($class) : ?>
                                                <p><?= 'Class: ' . (property_exists($class, 'FullyQualifiedName') ? ($class->FullyQualifiedName) : '') ?></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <p><?= 'TaxCode: ' . $value->{$detailType}->TaxCodeRef ?></p>
                                    <?php endif; ?>

                                    <?php if (property_exists($value->{$detailType}, 'ItemRef')) : ?>
                                        <?php $item = $this->dataService->FindbyId('item', $value->{$detailType}->ItemRef); ?>
                                        <h6 class="card-title"><?= $item->Name ?> <span class="float-right"><?= $bill->CurrencyRef . ' ' . $value->Amount ?></span></h6>
                                        <small class="card-text"><?= $bill->CurrencyRef . ' ' . $value->{$detailType}->UnitPrice . ' x ' . $value->{$detailType}->Qty ?></small>
                                    <?php endif; ?>

                                </div>
                            </div>

                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>