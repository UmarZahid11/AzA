<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'. $entity .'/' . $employee->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity); ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />

    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $employee->Id ?></li>
                    <li><b>Title:</b> <?= $employee->Title  ?></li>
                    <li><b>Suffix:</b> <?= $employee->Suffix ?></li>
                    <li><b>Name:</b> <?= $employee->FullyQualifiedName ?? $employee->DisplayName ?></li>
                    <li><b>GivenName:</b> <?= $employee->GivenName; ?></li>
                    <li><b>MiddleName:</b> <?= $employee->MiddleName; ?></li>
                    <li><b>FamilyName:</b> <?= $employee->FamilyName; ?></li>
                    <li><b>EmployeeNumber:</b> <?= $employee->EmployeeNumber; ?></li>
                    <li><b>Email:</b> <?= isset($employee->PrimaryEmailAddr->Address) ? $employee->PrimaryEmailAddr->Address : __(NOT_AVAILABLE) ?></li>
                    <li><b>Phone:</b> <?= isset($employee->PrimaryPhone) && $employee->PrimaryPhone ? $employee->PrimaryPhone->FreeFormNumber : ''; ?></li>
                    <li><b>Mobile:</b> <?= isset($employee->Mobile) && $employee->Mobile ? $employee->Mobile->FreeFormNumber : ''; ?></li>
                    <li><b>Name to print on check:</b> <?= $employee->PrintOnCheckName ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Gender:</b> <?= $employee->Gender; ?></li>
                    <li><b>Organization:</b> <?= $employee->Organization ? 'Yes' : 'No'; ?></li>
                    <li><b>BillableTime:</b> <?= $employee->BillableTime ? 'Yes' : 'No'; ?></li>
                    <li><b>BillRate:</b> <?= $employee->BillRate ?? NOT_AVAILABLE; ?></li>
                    <li><b>Billing Address:</b> <?= isset($employee->PrimaryAddr->Line1) ? $employee->PrimaryAddr->Line1 . '<br/>' : '' ?>
                        <?= isset($employee->PrimaryAddr->Line2) ? $employee->PrimaryAddr->Line2 . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr->Line3) ? $employee->PrimaryAddr->Line3 . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr->Line4) ? $employee->PrimaryAddr->Line4 . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr->Line5) ? $employee->PrimaryAddr->Line5 . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr->City) ? $employee->PrimaryAddr->City . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr->PostalCode) ? $employee->PrimaryAddr->PostalCode . '<br/>' : ''  ?>
                        <?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr->Country ? $employee->PrimaryAddr->Country : ''  ?>
                        <?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr->CountrySubDivisionCode ? $employee->PrimaryAddr->CountrySubDivisionCode . '<br/>' : ''  ?>
                    </li>
                    <li><b>Create Time:</b> <?= $employee->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $employee->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>