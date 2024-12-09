<?php
    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
    $incomeAccount = NULL;
    if($item->IncomeAccountRef) {
        $incomeAccount = $this->dataService->FindbyId('account', $item->IncomeAccountRef);
    }
    $expenseAccount = NULL;
    if($item->ExpenseAccountRef) {
        $expenseAccount = $this->dataService->FindbyId('account', $item->ExpenseAccountRef);
    }
    $assetAccount = NULL;
    if($item->AssetAccountRef) {
        $assetAccount = $this->dataService->FindbyId('account', $item->AssetAccountRef);
    }
    $depositToAccount = NULL;
    if($item->DepositToAccountRef) {
        $depositToAccount = $this->dataService->FindbyId('account', $item->DepositToAccountRef);
    }
?>
<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'.$entity.'/' . $item->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= 'Edit' . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>

        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $item->Id ?></li>
                    <li><b>Account:</b> <?= $item->FullyQualifiedName ?></li>
                    <li><b>Type:</b> <?= $item->Type ?></li>
                    <?php if($incomeAccount): ?>
                        <li><b>Income Account:</b> <?= $incomeAccount->FullyQualifiedName ?></li>
                    <?php endif; ?>
                    <?php if($expenseAccount): ?>
                        <li><b>Expense Account:</b> <?= $expenseAccount->FullyQualifiedName ?></li>
                    <?php endif; ?>
                    <?php if($assetAccount): ?>
                        <li><b>Asset Account:</b> <?= $assetAccount->FullyQualifiedName ?></li>
                    <?php endif; ?>
                    <?php if($depositToAccount): ?>
                        <li><b>Deposit Account:</b> <?= $depositToAccount->FullyQualifiedName ?></li>
                    <?php endif; ?>
                    <li><b>Track Qty On Hand:</b> <?= $item->TrackQtyOnHand ?></li>
                    <li><b>Qty On Hand:</b> <?= $item->QtyOnHand ?? __(NOT_AVAILABLE) ?></li>
                    <li><b>Inv Start Date:</b> <?= $item->InvStartDate ?? __(NOT_AVAILABLE) ?></li>
                    <li><b>Inv Start Date:</b> <?= $item->InvStartDate ?? __(NOT_AVAILABLE) ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Create Time:</b> <?= $item->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $item->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>