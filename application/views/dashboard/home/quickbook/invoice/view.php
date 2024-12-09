<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-view/invoice/' . $invoice->Id . '/1') ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-download text-white"></i>&nbsp;<?= __('Download PDF') ?></a>
    <a href="<?= l('dashboard/home/quickbook-send-email/invoice/' . $invoice->Id) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-paper-plane text-white"></i>&nbsp;<?= __('Send Email') ?></a>
    <a href="<?= l('dashboard/home/quickbook-save/invoice/' . $invoice->Id . '/' . $invoice->CustomerRef) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-pencil text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <?php
                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                    $customer = $this->dataService->FindById('customer', $invoice->CustomerRef);
                    $error = $this->dataService->getLastError();
                    ?>
                    <?php if (!$error) : ?>
                        <li><b><?= __('Customer') ?>:</b> <?= $customer->FullyQualifiedName ?></li>
                    <?php endif; ?>
                    <li><b><?= __('Billing Email') ?>:</b> <?= isset($invoice->BillEmail->Address) ? $invoice->BillEmail->Address : __(NOT_AVAILABLE) ?></li>
                    <li><b><?= __('No') ?>:</b> <?= isset($invoice->DocNumber) ? $invoice->DocNumber : '' ?></li>
                    <li><b><?= __('Invoice Date') ?>:</b> <?= isset($invoice->TxnDate) ? date('d/m/Y', strtotime($invoice->TxnDate)) : '' ?></li>
                    <li><b><?= __('Due Date') ?>:</b> <?= isset($invoice->DueDate) ? date('d/m/Y', strtotime($invoice->DueDate)) : '' ?></li>
                    <li><b><?= __('Status') ?>:</b> <?= isset($invoice->Balance) ? ($invoice->Balance == 0 ? 'Paid' : 'Overdue on ' . $invoice->DueDate) : '' ?></li>
                    <li><b><?= __('Net Amount') ?>:</b> <?= $invoice->CurrencyRef . ' ' . isset($invoice->TxnTaxDetail) && isset($invoice->TxnTaxDetail->TaxLine->TaxLineDetail->NetAmountTaxable) ? $invoice->TxnTaxDetail->TaxLine->TaxLineDetail->NetAmountTaxable : '' ?></li>
                    <li><b><?= __('Total Tax') ?>:</b> <?= $invoice->CurrencyRef . ' ' . isset($invoice->TxnTaxDetail->TotalTax) ? $invoice->TxnTaxDetail->TotalTax : '' ?></li>
                    <li><b><?= __('Amount due') ?>:</b> <?= $invoice->CurrencyRef . ' ' . isset($invoice->Balance) ? $invoice->Balance : '' ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b><?= __('Billing Address') ?>:</b> <?= isset($invoice->BillAddr->Line1) ? $invoice->BillAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($invoice->BillAddr->Line2) ? $invoice->BillAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($invoice->BillAddr->Line3) ? $invoice->BillAddr->Line3 . '<br/>' : ''  ?>
                        <?= isset($invoice->BillAddr->Line4) ? $invoice->BillAddr->Line4 . '<br/>' : ''  ?>
                        <?= isset($invoice->BillAddr->City) ? $invoice->BillAddr->City . '<br/>' : ''  ?>
                        <?= isset($invoice->BillAddr->PostCode) ? $invoice->BillAddr->PostCode . '<br/>' : ''  ?>
                        <?= isset($invoice->BillAddr->Country) ? $invoice->BillAddr->Country : ''  ?>
                        <?= isset($invoice->BillAddr->CountrySubDivisionCode) ? $invoice->BillAddr->CountrySubDivisionCode . '<br/>' : ''  ?></li>
                    <li><b><?= __('Shipping Address') ?>:</b>
                        <?= isset($invoice->ShipAddr->Line1) ? $invoice->ShipAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($invoice->ShipAddr->Line2) ? $invoice->ShipAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($invoice->ShipAddr->Line3) ? $invoice->ShipAddr->Line3 . '<br/>' : ''  ?>
                        <?= isset($invoice->ShipAddr->Line4) ? $invoice->ShipAddr->Line4 . '<br/>' : ''  ?>
                        <?= isset($invoice->ShipAddr->City) ? $invoice->ShipAddr->City . '<br/>' : ''  ?>
                        <?= isset($invoice->ShipAddr->PostCode) ? $invoice->ShipAddr->PostCode . '<br/>' : ''  ?>
                        <?= isset($invoice->ShipAddr->Country) ? $invoice->ShipAddr->Country : ''  ?>
                        <?= isset($invoice->ShipAddr->CountrySubDivisionCode) ? $invoice->ShipAddr->CountrySubDivisionCode . '<br/>' : ''  ?></li>
                </ul>
                <ul><li><b><?= __('Note to customer') ?>:</b></li><?= isset($invoice->CustomerMemo) ? $invoice->CustomerMemo : '' ?></ul>
            </div>

            <?php if(isset($invoice->Line)): ?>
                <div class="col-md-12">
                    <h5><?= __('Products & services') ?></h5>
                    <?php foreach ($invoice->Line as $key => $value) : ?>
                        <?php $detailType = $value->{'DetailType'}; ?>
                        <?php if (property_exists($value, $detailType)) : ?>
                            <div class="card text-left" style="border:0; border-bottom: 1px solid rgba(0, 0, 0, 0.125);">
                                <div class="card-body">
                                    <?php if ($value->Description) : ?>
                                        <?php if($value->SalesItemLineDetail->ItemRef):
                                            $this->dataService->updateOAuth2Token($this->session->userdata['quickbook']['object']);
                                            $item = $this->dataService->FindbyId('item', $value->{$detailType}->ItemRef);
                                        ?>
                                            <h6 class="card-title"><?= $item->Name ?> <span class="float-right"><?= $invoice->CurrencyRef . ' ' . $value->Amount ?></span></h6>
                                        <?php else: ?>
                                            <h6 class="card-title"><?= $value->Description ?> <span class="float-right"><?= $invoice->CurrencyRef . ' ' . $value->Amount ?></span></h6>
                                        <?php endif; ?>

                                        <small class="card-text"><?= $invoice->CurrencyRef . ' ' . $value->SalesItemLineDetail->UnitPrice . ' x ' . $value->SalesItemLineDetail->Qty ?></small>
                                    <?php else: ?>
                                        <h6 class="card-title"><?= __('Total') ?>:<span class="float-right"><?= $invoice->CurrencyRef . ' ' . $value->Amount ?></span></h6>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>