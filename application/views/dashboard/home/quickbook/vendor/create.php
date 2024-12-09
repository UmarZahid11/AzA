<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($vendor->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($vendor->Id) ? $vendor->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Suffix </label>
                <input type="text" name="Suffix" class="form-control" value="<?= isset($vendor->Suffix) ? $vendor->Suffix : '' ?>" maxlength="16" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,16}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'suffix') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Title </label>
                <input type="text" name="Title" class="form-control" value="<?= isset($vendor->Title) ? $vendor->Title : '' ?>" maxlength="16" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,16}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'title') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>DisplayName <span class="text-danger">*</span></label>
                <input type="text" name="DisplayName" class="form-control" value="<?= isset($vendor->DisplayName) ? $vendor->DisplayName : '' ?>" maxlength="500" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,500}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'display name') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="PrimaryEmailAddr[Address]" class="form-control" value="<?= isset($vendor->PrimaryEmailAddr) && $vendor->PrimaryEmailAddr ? $vendor->PrimaryEmailAddr->Address : '' ?>" maxlength="100" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'email') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>URL </label>
                <input type="url" name="WebAddr[URI]" class="form-control" value="<?= isset($vendor->WebAddr) && $vendor->WebAddr ? $vendor->WebAddr->URI : '' ?>" maxlength="1000" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'url') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Phone </label>
                <input type="text" name="PrimaryPhone[FreeFormNumber]" class="form-control" value="<?= isset($vendor->PrimaryPhone) && $vendor->PrimaryPhone ? $vendor->PrimaryPhone->FreeFormNumber : '' ?>" maxlength="30" pattern="[0-9]{8,30}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'phone number') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Mobile </label>
                <input type="text" name="Mobile[FreeFormNumber]" class="form-control" value="<?= isset($vendor->Mobile) && $vendor->Mobile ? $vendor->Mobile->FreeFormNumber : '' ?>" maxlength="30" pattern="[0-9]{8,30}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'mobile number') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Family Name </label>
                <input type="text" name="FamilyName" class="form-control" value="<?= isset($vendor->FamilyName) ? $vendor->FamilyName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'family name') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Tax Identifier </label>
                <input type="text" name="TaxIdentifier" class="form-control" value="<?= isset($vendor->TaxIdentifier) ? $vendor->TaxIdentifier : '' ?>" maxlength="20" pattern="([0-9,.-]+( [0-9,.-]+)*){3,20}"/>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'tax identifier name') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Acct Number </label>
                <input type="text" name="AcctNum" class="form-control" value="<?= isset($vendor->AcctNum) ? $vendor->AcctNum : '' ?>" maxlength="100" pattern="([0-9,.-]+( [0-9,.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'account number') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Company Name </label>
                <input type="text" name="CompanyName" class="form-control" value="<?= isset($vendor->CompanyName) ? $vendor->CompanyName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'company name') ?></small>
            </div>
        </div>

        <h4 class="mt-4">Bill Address</h4>
        <div class="col-md-12">
            <div class="form-group">
                <label>Line 1 </label>
                <input type="text" name="BillAddr[Line1]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->Line1 : '' ?>" maxlength="500" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line 1 address') ?></small>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Line 2 </label>
                <input type="text" name="BillAddr[Line2]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->Line2 : '' ?>" maxlength="500" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line 2 address') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>City </label>
                <input type="text" name="BillAddr[City]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->City : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'city') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Country </label>
                <input type="text" name="BillAddr[Country]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->Country : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'country') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Country SubDivision </label>
                <input type="text" name="BillAddr[CountrySubDivisionCode]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->CountrySubDivisionCode : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'country subdivison') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>PostalCode </label>
                <input type="text" name="BillAddr[PostalCode]" class="form-control" value="<?= isset($vendor->BillAddr) && $vendor->BillAddr ? $vendor->BillAddr->PostalCode : '' ?>" maxlength="30" pattern="([0-9]){3,30}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'postal code') ?></small>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>