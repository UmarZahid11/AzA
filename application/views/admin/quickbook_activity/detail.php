<style>
    .main-content .container {
        border-left: none;
    }
</style>

<?php
global $config;
$model_heads = explode(",", $dt_params['dt_headings']);
?>

<div class="inner-page-header">

    <h1><?= humanize($class_name) ?> <small>Details</small></h1>

</div>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-book"></i>
            <strong>Quickbook Entity: <?= strtoupper(QUICKBOOK_ENTITY_TYPE_LIST[$entity_detail['quickbook_activity_entity']]) ?> </strong>
            <small> / <?= date("Y-m-d", strtotime($entity_detail['quickbook_activity_createdon'])) ?></small>
        </div>
        <div class="tools">
            <a onclick="print_div();" class="label label-white"><i class="fa fa-print"></i>
            </a>
        </div>
    </div>

    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        <div class="invoice container" id="invoice" style="padding: 20px;">
            <div class="row invoice-logo">
                <div class="col-xs-6 invoice-logo-space">
                    <img style="height: 100px;" src="<?= get_image($this->layout_data['logo'][0]['logo_image_path'], $this->layout_data['logo'][0]['logo_image']) ?>" alt="logo" class="main-tem-logo" />
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                    <strong>Created by:</strong> <?= $this->model_signup->profileName($this->model_signup->find_by_pk($entity_detail['quickbook_activity_userid']), FALSE); ?><br />
                    <strong>Created on:</strong> <?= date('d M, Y h:i a', strtotime($entity_detail['quickbook_activity_createdon'])); ?><br />
                    <?php if ($entity_detail['quickbook_activity_updatedon']) : ?>
                        <strong>Last updated:</strong> <?= $entity_detail['quickbook_activity_updatedon']; ?>
                    <?php endif; ?>
                </div>

            </div>
            <hr />
            <div class="container">
                <div class="row">
                    <h4><?= QUICKBOOK_ENTITY_TYPE_LIST[$entity_detail['quickbook_activity_entity']] ?></h4>
                    <div class="col-xs-6">
                        <?php $entityArray = (unserialize($entity_detail['quickbook_activity_entity_data'])); ?>
                        <? //debug($entityArray);
                        ?>
                        <?php echo '<ul class="list-unstyled">'; ?>

                        <?php switch ($entity_detail['quickbook_activity_entity']) {
                            case 'account':
                                echo '<li><b>Name:</b> ' . $entityArray->Name ?? $entityArray->FullyQualifiedName . '</li>';
                                break;
                            case 'bill':
                                if ($entityArray->VendorRef) {
                                    $vendorRef = $entity_detail['quickbook_activity_vendor_ref'];
                                    if ($vendorRef) {
                                        $vendorRef = unserialize($vendorRef);
                                        echo '<li><b>Vendor:</b> ' . ($vendorRef->FullyQualifiedName ?? $vendorRef->DisplayName) . '</li>';
                                    }
                                }
                                echo '<li><b>DueDate:</b> ' . $entityArray->DueDate . '</li>' .
                                    '<li><b>TotalAmt:</b> ' . $entityArray->CurrencyRef . ' ' . $entityArray->TotalAmt . '</li>' .
                                    '<li><b>Balance:</b> ' . $entityArray->CurrencyRef . ' ' . $entityArray->Balance . '</li>';
                                break;
                            case 'billpayment':
                                if ($entityArray->VendorRef) {
                                    $vendorRef = $entity_detail['quickbook_activity_vendor_ref'];
                                    if ($vendorRef) {
                                        $vendorRef = unserialize($vendorRef);
                                        echo '<li><b>Vendor:</b> ' . ($vendorRef->FullyQualifiedName ?? $vendorRef->DisplayName) . '</li>';
                                    }
                                }
                                echo '<li><b>PayType:</b> ' . $entityArray->PayType . '</li>' .
                                    '<li><b>TotalAmt:</b> ' . $entityArray->CurrencyRef . ' ' . $entityArray->TotalAmt . '</li>' .
                                    '<li><b>TxnDate:</b> ' . $entityArray->TxnDate . '</li>';
                                break;
                            case 'class':
                                echo '<li><b>Name:</b> ' . $entityArray->Name ?? $entityArray->FullyQualifiedName . '</li>';
                                break;
                            case 'customer':
                                echo '<li><b>Name:</b> ' . ($entityArray->FullyQualifiedName ?? $entityArray->DisplayName) . '</li>' .
                                    '<li><b>Email:</b> ' . (isset($entityArray->PrimaryEmailAddr->Address) ? $entityArray->PrimaryEmailAddr->Address : __(NOT_AVAILABLE)) . '</li>' .
                                    '<li><b>Phone:</b> ' . ((isset($entityArray->PrimaryPhone) && $entityArray->PrimaryPhone) ? $entityArray->PrimaryPhone->FreeFormNumber : '') . '</li>' .
                                    '<li><b>Mobile:</b> ' . (isset($entityArray->Mobile) && $entityArray->Mobile ? $entityArray->Mobile->FreeFormNumber : '') . '</li>' .
                                    '<li><b>Fax:</b> ' . (isset($entityArray->Fax) && $entityArray->Fax ? $entityArray->Fax->FreeFormNumber : '') . '</li>' .
                                    '<li><b>Website:</b> ' . (isset($entityArray->WebAddr) && $entityArray->WebAddr ? $entityArray->WebAddr->URI : '') . '</li>' .
                                    '<li><b>Notes:</b> ' . $entityArray->Notes . '</li>' .
                                    '<li><b>Name to print on check:</b> ' . $entityArray->PrintOnCheckName . '</li>';
                                break;
                            case 'department':
                                echo '<li><b>Name:</b> ' . $entityArray->Name . '</li>' .
                                    '<li><b>FullyQualifiedName:</b> ' . ($entityArray->FullyQualifiedName) . '</li>';
                                break;
                            case 'employee':
                                echo '<li><b>Title:</b> ' . $entityArray->Title  . '</li>' .
                                    '<li><b>Suffix:</b> ' . $entityArray->Suffix . '</li>' .
                                    '<li><b>Name:</b> ' . ($entityArray->FullyQualifiedName ?? $entityArray->DisplayName) . '</li>' .
                                    '<li><b>GivenName:</b> ' . $entityArray->GivenName . '</li>' .
                                    '<li><b>MiddleName:</b> ' . $entityArray->MiddleName . '</li>' .
                                    '<li><b>FamilyName:</b> ' . $entityArray->FamilyName . '</li>' .
                                    '<li><b>EmployeeNumber:</b> ' . $entityArray->EmployeeNumber . '</li>' .
                                    '<li><b>Email:</b> ' . (isset($entityArray->PrimaryEmailAddr->Address) ? $entityArray->PrimaryEmailAddr->Address : __(NOT_AVAILABLE)) . '</li>' .
                                    '<li><b>Phone:</b> ' . (isset($entityArray->PrimaryPhone) && $entityArray->PrimaryPhone ? $entityArray->PrimaryPhone->FreeFormNumber : '') . '</li>' .
                                    '<li><b>Mobile:</b> ' . (isset($entityArray->Mobile) && $entityArray->Mobile ? $entityArray->Mobile->FreeFormNumber : '') . '</li>' .
                                    '<li><b>Name to print on check:</b> ' . $entityArray->PrintOnCheckName . '</li>';
                                break;
                            case 'invoice':
                                if ($entityArray->CustomerRef) {
                                    $customerRef = $entity_detail['quickbook_activity_customer_ref'];
                                    if ($customerRef) {
                                        $customerRef = unserialize($customerRef);
                                        echo '<li><b>Customer:</b> ' . ($customerRef->FullyQualifiedName ?? $customerRef->DisplayName) . '</li>';
                                    }
                                }
                                echo '<li><b>Billing Email:</b> ' . (isset($entityArray->BillEmail->Address) ? $entityArray->BillEmail->Address : __(NOT_AVAILABLE)) . '</li>' .
                                    '<li><b>No:</b> ' . (isset($entityArray->DocNumber) ? $entityArray->DocNumber : '') . '</li>' .
                                    '<li><b>Invoice Date:</b> ' . (isset($entityArray->TxnDate) ? date('d/m/Y', strtotime($entityArray->TxnDate)) : '') . '</li>' .
                                    '<li><b>Due Date:</b> ' . (isset($entityArray->DueDate) ? date('d/m/Y', strtotime($entityArray->DueDate)) : '') . '</li>' .
                                    '<li><b>Status:</b> ' . (isset($entityArray->Balance) ? ($entityArray->Balance == 0 ? 'Paid' : 'Overdue on ' . $entityArray->DueDate) : '') . '</li>' .
                                    '<li><b>Net Amount:</b> ' . ($entityArray->CurrencyRef) . ' ' . (isset($entityArray->TxnTaxDetail) && isset($entityArray->TxnTaxDetail->TaxLine->TaxLineDetail->NetAmountTaxable) ? $entityArray->TxnTaxDetail->TaxLine->TaxLineDetail->NetAmountTaxable : '') . '</li>' .
                                    '<li><b>Total Tax:</b> ' . ($entityArray->CurrencyRef) . ' ' . (isset($entityArray->TxnTaxDetail->TotalTax) ? $entityArray->TxnTaxDetail->TotalTax : '') . '</li>' .
                                    '<li><b>Amount due:</b> ' . ($entityArray->CurrencyRef) . ' ' . (isset($entityArray->Balance) ? $entityArray->Balance : '') . '</li>';
                                break;
                            case 'item':
                                echo '<li><b>Name:</b> ' . $entityArray->Name ?? $entityArray->FullyQualifiedName . '</li>';
                                break;
                            case 'timeactivity':
                                if ($entityArray->VendorRef) {
                                    $vendorRef = $entity_detail['quickbook_activity_vendor_ref'];
                                    if ($vendorRef) {
                                        $vendorRef = unserialize($vendorRef);
                                        echo '<li><b>Vendor:</b> ' . ($vendorRef->FullyQualifiedName ?? $vendorRef->DisplayName) . '</li>';
                                    }
                                }
                                if ($entityArray->EmployeeRef) {
                                    $employeeRef = $entity_detail['quickbook_activity_employee_ref'];
                                    if ($employeeRef) {
                                        $employeeRef = unserialize($employeeRef);
                                        echo '<li><b>Employee:</b> ' . ($employeeRef->FullyQualifiedName ?? $employeeRef->DisplayName) . '</li>';
                                    }
                                }
                                echo '<li><b>TxnDate:</b> ' . $entityArray->TxnDate . '</li>' .
                                    '<li><b>Start Time:</b> ' . $entityArray->StartTime . '</li>' .
                                    '<li><b>End Time:</b> ' . $entityArray->EndTime . '</li>';
                                break;
                        } ?>
                        <?php echo '</ul>'; ?>
                    </div>
                    <div class="col-md-6">
                        <?php echo '<ul class="list-unstyled">'; ?>
                        <?php echo '<li><b>Create Time:</b> ' . $entityArray->MetaData->CreateTime . '</li>' .
                            '<li><b>Last Updated Time:</b> ' . $entityArray->MetaData->LastUpdatedTime . '</li>';
                        ?>
                        <?php echo '</ul>'; ?>
                    </div>

                </div>
            </div>
            <hr />
            <small>Questions? Email: <a href="mailto:<?= g('db.admin.email') ?>"><?= g('db.admin.email') ?></a></small>

        </div>
    </div>

</div>

<script>
    function print_div() {
        var printContents = document.getElementById('invoice').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents
    }
</script>