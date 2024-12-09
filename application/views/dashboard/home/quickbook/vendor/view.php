<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'. $entity .'/' . $vendor->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity); ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $vendor->Id ?></li>
                    <li><b>Title:</b> <?= $vendor->Title  ?></li>
                    <li><b>Suffix:</b> <?= $vendor->Suffix ?></li>
                    <li><b>Name:</b> <?= $vendor->FullyQualifiedName ?? $vendor->DisplayName ?></li>
                    <li><b>Email:</b> <?= isset($vendor->PrimaryEmailAddr->Address) ? $vendor->PrimaryEmailAddr->Address : __(NOT_AVAILABLE) ?></li>
                    <li><b>Phone:</b> <?= isset($vendor->PrimaryPhone) && $vendor->PrimaryPhone ? $vendor->PrimaryPhone->FreeFormNumber : ''; ?></li>
                    <li><b>Mobile:</b> <?= isset($vendor->Mobile) && $vendor->Mobile ? $vendor->Mobile->FreeFormNumber : ''; ?></li>
                    <li><b>Website:</b> <?= isset($vendor->WebAddr) && $vendor->WebAddr ? $vendor->WebAddr->URI : ''; ?></li>
                    <li><b>Name to print on check:</b> <?= $vendor->PrintOnCheckName ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Billing Address:</b> <?= isset($vendor->BillAddr->Line1) ? $vendor->BillAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($vendor->BillAddr->Line2) ? $vendor->BillAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($vendor->BillAddr->City) ? $vendor->BillAddr->City . '<br/>' : ''  ?>
                        <?= isset($vendor->BillAddr->PostCode) ? $vendor->BillAddr->PostCode . '<br/>' : ''  ?>
                        <?= isset($vendor->BillAddr) && $vendor->BillAddr->Country ? $vendor->BillAddr->Country : ''  ?>
                        <?= isset($vendor->BillAddr) && $vendor->BillAddr->CountrySubDivisionCode ? $vendor->BillAddr->CountrySubDivisionCode . '<br/>' : ''  ?>
                    </li>
                    <li><b>Create Time:</b> <?= $vendor->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $vendor->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>