<?php
    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
?>
<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'.$entity.'/' . $timeactivity->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= 'Edit' . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <ul>
                <li><b>Id:</b> <?= $timeactivity->Id ?></li>
                <li><b>NameOf:</b> <?= $timeactivity->NameOf ?></li>
                <li><b>TxnDate:</b> <?= $timeactivity->TxnDate ?></li>
                <?php if(property_exists($timeactivity, 'EmployeeRef') && $timeactivity->EmployeeRef): ?>
                    <?php $employee = $this->dataService->FindbyId('employee', $timeactivity->EmployeeRef); ?>
                    <li><b>Employee:</b> <?= $employee->DisplayName ?></li>
                <?php endif; ?>
                <?php if(property_exists($timeactivity, 'VendorRef') && $timeactivity->VendorRef): ?>
                    <?php $vendor = $this->dataService->FindbyId('vendor', $timeactivity->VendorRef); ?>
                    <li><b>Vendor:</b> <?= $vendor->DisplayName ?></li>
                <?php endif; ?>
                <li><b>StartTime:</b> <?= $timeactivity->StartTime ?></li>
                <li><b>EndTime:</b> <?= $timeactivity->EndTime ?></li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul>
                <li><b>Create Time:</b> <?= $timeactivity->MetaData->CreateTime ?></li>
                <li><b>Last Updated Time:</b> <?= $timeactivity->MetaData->LastUpdatedTime ?></li>
            </ul>
        </div>
    </div>
</div>