<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'. $entity .'/' . $customer->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity); ?></a>
    <a href="<?= l('dashboard/home/quickbook-listing/invoice/0/' . $customer->Id) ?>" class="btn btn-custom float-right" target="_blank"><i class="fa fa-paper-plane text-white"></i>&nbsp;<?= __('View saved invoices') ?></a>
    <a href="<?= l('dashboard/home/quickbook-save/invoice/0/' . $customer->Id) ?>" class="btn btn-custom float-right" target="_blank"><i class="fa fa-address-card text-white"></i>&nbsp;<?= __('Send invoice') ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Customer:</b> <?= $customer->FullyQualifiedName ?></li>
                    <li><b>Email:</b> <?= isset($customer->PrimaryEmailAddr->Address) ? $customer->PrimaryEmailAddr->Address : __(NOT_AVAILABLE) ?></li>
                    <li><b>Phone:</b> <?= isset($customer->PrimaryPhone) && $customer->PrimaryPhone ? $customer->PrimaryPhone->FreeFormNumber : ''; ?></li>
                    <li><b>Mobile:</b> <?= isset($customer->Mobile) && $customer->Mobile ? $customer->Mobile->FreeFormNumber : ''; ?></li>
                    <li><b>Fax:</b> <?= isset($customer->Fax) && $customer->Fax ? $customer->Fax->FreeFormNumber : ''; ?></li>
                    <li><b>Website:</b> <?= isset($customer->WebAddr) && $customer->WebAddr ? $customer->WebAddr->URI : ''; ?></li>
                    <li><b>Notes:</b> <?= $customer->Notes ?></li>
                    <li><b>Name to print on check:</b> <?= $customer->PrintOnCheckName ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Billing Address:</b> <?= isset($customer->BillAddr->Line1) ? $customer->BillAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($customer->BillAddr->Line2) ? $customer->BillAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($customer->BillAddr->City) ? $customer->BillAddr->City . '<br/>' : ''  ?>
                        <?= isset($customer->BillAddr->PostCode) ? $customer->BillAddr->PostCode . '<br/>' : ''  ?>
                        <?= isset($customer->BillAddr) && $customer->BillAddr->Country ? $customer->BillAddr->Country : ''  ?>
                        <?= isset($customer->BillAddr) && $customer->BillAddr->CountrySubDivisionCode ? $customer->BillAddr->CountrySubDivisionCode . '<br/>' : ''  ?></li>
                    <li><b>Shipping Address:</b> <?= isset($customer->ShipAddr->Line1) ? $customer->ShipAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($customer->ShipAddr->Line2) ? $customer->ShipAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($customer->ShipAddr->City) ? $customer->ShipAddr->City . '<br/>' : ''  ?>
                        <?= isset($customer->ShipAddr->PostCode) ? $customer->ShipAddr->PostCode . '<br/>' : ''  ?>
                        <?= isset($customer->ShipAddr->Country) ? $customer->ShipAddr->Country : ''  ?><?= isset($customer->ShipAddr->CountrySubDivisionCode) ? $customer->ShipAddr->CountrySubDivisionCode . '<br/>' : ''  ?></li>
                    <li><b>Term:</b><?= isset($customer->SalesTermRef) && $customer->SalesTermRef ? $customer->SalesTermRef : ''; ?></li>
                    <li><b>Payment method:</b><?= isset($customer->PaymentMethodRef) && $customer->PaymentMethodRef ? $customer->PaymentMethodRef : ''; ?></li>
                    <li><b>Customer type:</b><?= isset($customer->CustomerTypeRef) && $customer->CustomerTypeRef ? $customer->CustomerTypeRef : '' ?></li>
                    <li><b>Tax reg. no.:</b><?= isset($customer->TaxRegime) && $customer->TaxRegime ? $customer->TaxRegime : '' ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>