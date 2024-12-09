<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($employee->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($employee->Id) ? $employee->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="Title" class="form-control" maxlength="16" value="<?= isset($employee->Title) ? $employee->Title : '' ?>" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'title') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>DisplayName <span class="text-danger">*</span></label>
                <input type="text" name="DisplayName" class="form-control" value="<?= isset($employee->DisplayName) ? $employee->DisplayName : '' ?>" maxlength="500" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,500}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'display name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>GivenName <span class="text-danger">*</span></label>
                <input type="text" name="GivenName" class="form-control" value="<?= isset($employee->GivenName) ? $employee->GivenName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'given name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>MiddleName </label>
                <input type="text" name="MiddleName" class="form-control" value="<?= isset($employee->MiddleName) ? $employee->MiddleName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'middle name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Suffix </label>
                <input type="text" name="Suffix" class="form-control" value="<?= isset($employee->Suffix) ? $employee->Suffix : '' ?>" maxlength="16" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,16}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'suffix') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>FamilyName </label>
                <input type="text" name="FamilyName" class="form-control" value="<?= isset($employee->FamilyName) ? $employee->FamilyName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'family name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>PostalCode </label>
                <input type="text" name="PrimaryAddr[PostalCode]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->PostalCode : '' ?>" maxlength="30" pattern="([0-9]){3,30}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'postal code') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>City </label>
                <input type="text" name="PrimaryAddr[City]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->City : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'city') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Country </label>
                <input type="text" name="PrimaryAddr[Country]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Country : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'country') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Country SubDivision </label>
                <input type="text" name="PrimaryAddr[CountrySubDivisionCode]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->CountrySubDivisionCode : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'country subdivison') ?></small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Line1 </label>
                <input type="text" name="PrimaryAddr[Line1]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Line1 : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line1') ?></small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Line2 </label>
                <input type="text" name="PrimaryAddr[Line2]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Line2 : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line2') ?></small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Line3 </label>
                <input type="text" name="PrimaryAddr[Line3]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Line3 : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line3') ?></small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Line4 </label>
                <input type="text" name="PrimaryAddr[Line4]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Line4 : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line4') ?></small>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Line5 </label>
                <input type="text" name="PrimaryAddr[Line5]" class="form-control" value="<?= isset($employee->PrimaryAddr) && $employee->PrimaryAddr ? $employee->PrimaryAddr->Line5 : '' ?>" maxlength="255" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,255}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'line5') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <input type="email" name="PrimaryEmailAddr[Address]" class="form-control" value="<?= isset($employee->PrimaryEmailAddr) && $employee->PrimaryEmailAddr ? $employee->PrimaryEmailAddr->Address : '' ?>" maxlength="100" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'email') ?></small>
            </div>
        </div>

        <?php if (!isset($employee->Id)) : ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>SSN <span class="text-danger">*</span></label>
                    <input type="text" name="SSN" class="form-control" value="<?= isset($employee->SSN) ? $employee->SSN : '' ?>" maxlength="100" pattern="([0-9,.-]+( [0-9,.-]+)*){3,100}" required />
                    <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'ssn') ?></small>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-6">
            <div class="form-group">
                <label>Phone </label>
                <input type="text" name="PrimaryPhone[FreeFormNumber]" class="form-control" value="<?= isset($employee->PrimaryPhone) && $employee->PrimaryPhone ? $employee->PrimaryPhone->FreeFormNumber : '' ?>" maxlength="20" pattern="[0-9]{8,20}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'phone number') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Mobile </label>
                <input type="text" name="Mobile[FreeFormNumber]" class="form-control" value="<?= isset($employee->Mobile) && $employee->Mobile ? $employee->Mobile->FreeFormNumber : '' ?>" maxlength="20" pattern="[0-9]{8,20}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'mobile number') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Family Name </label>
                <input type="text" name="FamilyName" class="form-control" value="<?= isset($employee->FamilyName) ? $employee->FamilyName : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'family name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Gender </label>
                <select class="form-select" name="Gender">
                    <option value="">Select Gender</option>
                    <option value="Male" <?= isset($employee->Gender) && $employee->Gender == 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= isset($employee->Gender) && $employee->Gender == 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'gender') ?></small>
            </div>
        </div>

        <!-- <div class="col-md-6">
            <div class="form-group">
                <label>HiredDate </label>
                <input type="date" name="HiredDate[date]" class="form-control" value="<?= isset($employee->HiredDate) ? $employee->HiredDate : '' ?>" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'HiredDate') ?></small>
            </div>
        </div> -->

        <div class="col-md-6">
            <div class="form-group">
                <label>BillableTime</label>
                <select class="form-select" name="BillableTime">
                    <option value="">Select if this entity is currently enabled for use by QuickBooks</option>
                    <option value="true" <?= isset($employee->BillableTime) && $employee->BillableTime == 'true' ? 'selected' : '' ?>>Yes</option>
                    <option value="false" <?= isset($employee->BillableTime) && $employee->BillableTime == '' ? 'selected' : '' ?>>No</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'BillableTime') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>BillRate </label>
                <input type="number" name="BillRate" class="form-control" value="<?= isset($employee->BillRate) ? $employee->BillRate : '' ?>" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'BillRate') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Organization</label>
                <select class="form-select" name="Organization">
                    <option value="">Employee represent an Organization</option>
                    <option value="true" <?= isset($employee->Organization) && $employee->Organization == 'true' ? 'selected' : '' ?> >Yes</option>
                    <option value="false" <?= isset($employee->Organization) && $employee->Organization == 'false' ? 'selected' : '' ?>>No</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Organization') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>EmployeeNumber </label>
                <input type="text" name="EmployeeNumber" class="form-control" value="<?= isset($employee->EmployeeNumber) ? $employee->EmployeeNumber : '' ?>" maxlength="100" pattern="([0-9,.-]+( [0-9,.-]+)*){3,100}" />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'EmployeeNumber') ?></small>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>