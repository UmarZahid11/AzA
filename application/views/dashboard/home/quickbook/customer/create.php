<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($customer->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($customer->Id) ? $customer->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="Title" class="form-control" maxlength="16" value="<?= isset($customer->Title) ? $customer->Title : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>First name</label>
                <input type="text" name="GivenName" class="form-control" maxlength="100" value="<?= isset($customer->GivenName) ? $customer->GivenName : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Middle name</label>
                <input type="text" name="MiddleName" class="form-control" maxlength="100" value="<?= isset($customer->MiddleName) ? $customer->MiddleName : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Last name</label>
                <input type="text" name="FamilyName" class="form-control" maxlength="100" value="<?= isset($customer->FamilyName) ? $customer->FamilyName : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Suffix</label>
                <input type="text" name="Suffix" class="form-control" maxlength="16" value="<?= isset($customer->Suffix) ? $customer->Suffix : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Company name</label>
                <input type="text" name="CompanyName" class="form-control" maxlength="100" value="<?= isset($customer->CompanyName) ? $customer->CompanyName : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Customer display name <span class="text-danger">*</span></label>
                <input type="text" name="DisplayName" class="form-control" maxlength="100" required value="<?= isset($customer->DisplayName) ? $customer->DisplayName : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="PrimaryEmailAddr[Address]" class="form-control" maxlength="100" value="<?= isset($customer->PrimaryEmailAddr->Address) ? $customer->PrimaryEmailAddr->Address : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Phone number</label>
                <input type="tel" name="PrimaryPhone[FreeFormNumber]" class="form-control" maxlength="30" value="<?= isset($customer->PrimaryPhone->FreeFormNumber) ? $customer->PrimaryPhone->FreeFormNumber : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Mobile number</label>
                <input type="tel" name="Mobile[FreeFormNumber]" class="form-control" maxlength="30" value="<?= isset($customer->Mobile->FreeFormNumber) ? $customer->Mobile->FreeFormNumber : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Fax</label>
                <input type="text" name="Fax[FreeFormNumber]" class="form-control" maxlength="30" value="<?= isset($customer->Fax->FreeFormNumber) ? $customer->Fax->FreeFormNumber : '' ?>" />
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="form-group">
                <label>Other</label>
                <input type="text" name="OtherAddr" class="form-control" maxlength="30" />
            </div>
        </div> -->
        <div class="col-md-6">
            <div class="form-group">
                <label>Website</label>
                <input type="url" name="WebAddr[URI]" class="form-control" maxlength="1000" value="<?= isset($customer->WebAddr->URI) ? $customer->WebAddr->URI : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Name to print on checks</label>
                <input type="text" name="PrintOnCheckName" class="form-control" maxlength="110" value="<?= isset($customer->PrintOnCheckName) ? $customer->PrintOnCheckName : '' ?>" />
            </div>
        </div>
        <hr class="mt-3" />
        <h5>Addresses</h5>
        <p>Billing address</p>
        <div class="col-md-6">
            <div class="form-group">
                <label>Street address 1</label>
                <input type="text" name="BillAddr[Line1]" class="form-control" maxlength="255" value="<?= isset($customer->BillAddr->Line1) ? $customer->BillAddr->Line1 : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Street address 2</label>
                <input type="text" name="BillAddr[Line2]" class="form-control" maxlength="255" value="<?= isset($customer->BillAddr->Line2) ? $customer->BillAddr->Line2 : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="BillAddr[City]" class="form-control" maxlength="255" value="<?= isset($customer->BillAddr->City) ? $customer->BillAddr->City : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>State</label>
                <input type="text" name="BillAddr[CountrySubDivisionCode]" class="form-control" maxlength="255" value="<?= isset($customer->BillAddr->CountrySubDivisionCode) ? $customer->BillAddr->CountrySubDivisionCode : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Zip code</label>
                <input type="text" name="BillAddr[PostalCode]" class="form-control" maxlength="30" value="<?= isset($customer->BillAddr->PostalCode) ? $customer->BillAddr->PostalCode : '' ?>" />
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="BillAddr[Country]" class="form-control" maxlength="255" value="<?= isset($customer->BillAddr->Country) ? $customer->BillAddr->Country : '' ?>" />
            </div>
        </div>
        <p class="mt-2">Shipping address</p>
        <label><input type="checkbox" id="same_shippingAddress" checked /> <?= __('Same as billing address') ?></label>
        <div class="row d-none" id="shippingAddress">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Street address 1</label>
                    <input type="text" name="ShipAddr[Line1]" class="form-control shippingInput" maxlength="255" value="<?= isset($customer->ShipAddr->Line1) ? $customer->ShipAddr->Line1 : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Street address 2</label>
                    <input type="text" name="ShipAddr[Line2]" class="form-control shippingInput" maxlength="255" value="<?= isset($customer->ShipAddr->Line2) ? $customer->ShipAddr->Line2 : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="ShipAddr[City]" class="form-control shippingInput" maxlength="255" value="<?= isset($customer->ShipAddr->City) ? $customer->ShipAddr->City : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="ShipAddr[CountrySubDivisionCode]" class="form-control shippingInput" maxlength="255" value="<?= isset($customer->ShipAddr->CountrySubDivisionCode) ? $customer->ShipAddr->CountrySubDivisionCode : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zip code</label>
                    <input type="text" name="ShipAddr[PostalCode]" class="form-control shippingInput" maxlength="30" value="<?= isset($customer->ShipAddr->PostalCode) ? $customer->ShipAddr->PostalCode : '' ?>" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="ShipAddr[Country]" class="form-control shippingInput" maxlength="255" value="<?= isset($customer->ShipAddr->Country) ? $customer->ShipAddr->Country : '' ?>" />
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>Notes</label>
                <textarea name="Notes" class="form-control" maxlength="1000"><?= isset($customer->Notes) ? $customer->Notes : '' ?></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>

<script>
    $(document).ready(function() {
        if ($('input[name=id]').length != '') {
            $('#same_shippingAddress').prop("checked", false);
        }

        if ($('#same_shippingAddress').is(':checked')) {
            if (!$('#shippingAddress').hasClass('d-none')) {
                $('#shippingAddress').addClass('d-none')
            }
            $('.shippingInput').attr('disabled', true)
        } else {
            $('#shippingAddress').removeClass('d-none')
            $('.shippingInput').attr('disabled', false)
        }
        $('#same_shippingAddress').on('change', function() {
            if ($(this).is(':checked')) {
                if (!$('#shippingAddress').hasClass('d-none')) {
                    $('#shippingAddress').addClass('d-none')
                }
                $('.shippingInput').attr('disabled', true)
            } else {
                $('#shippingAddress').removeClass('d-none')
                $('.shippingInput').attr('disabled', false)
            }
        })
    })
</script>