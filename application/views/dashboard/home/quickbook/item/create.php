<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>

    <?php if (isset($item->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($item->Id) ? $item->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="Name" class="form-control" value="<?= isset($item->Name) ? $item->Name : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'item name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Type <span class="text-danger">*</span></label>
                <select class="form-select" name="Type" required>
                    <option value="">Select Type</option>
                    <option value="Inventory" <?= isset($item->Type) && $item->Type == 'Inventory' ? 'selected' : '' ?>>Inventory</option>
                    <option value="Service" <?= isset($item->Type) && $item->Type == 'Service' ? 'selected' : '' ?>>Service</option>
                    <option value="NonInventory" <?= isset($item->Type) && $item->Type == 'NonInventory' ? 'selected' : '' ?>>NonInventory</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'item type') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>QtyOnHand <span class="text-danger">*</span></label>
                <input type="text" name="QtyOnHand" class="form-control" value="<?= isset($item->QtyOnHand) ? $item->QtyOnHand : '' ?>" min="0" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'QtyOnHand') ?></small>
            </div>
        </div>

        <!-- <div class="col-md-6">
            <div class="form-group">
                <label>UnitPrice <span class="text-danger">*</span></label>
                <input type="number" step="0.1" name="UnitPrice" class="form-control" value="<?= isset($item->UnitPrice) ? $item->UnitPrice : '' ?>" min="0" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'UnitPrice') ?></small>
            </div>
        </div> -->

        <div class="col-md-6">
            <div class="form-group">
                <label>Income Account <span class="text-danger">*</span></label>
                <select class="form-select" name="IncomeAccountRef[value]" required>
                    <?php if (isset($IncomeAccountRef) && $IncomeAccountRef) : ?>
                        <option value="">Select Income Account</option>
                        <?php foreach ($IncomeAccountRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($item->IncomeAccountRef) && $item->IncomeAccountRef == $value->Id ? 'selected' : '' ?> ><?= $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Income Account') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Asset Account <span class="text-danger">*</span></label>
                <select class="form-select" name="AssetAccountRef[value]" required>
                    <?php if (isset($AssetAccountRef) && $AssetAccountRef) : ?>
                        <option value="">Select Asset Account</option>
                        <?php foreach ($AssetAccountRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($item->ExpenseAccountRef) && $item->ExpenseAccountRef == $value->Id ? 'selected' : '' ?> ><?= $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Asset Account') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Expense Account <span class="text-danger">*</span></label>
                <select class="form-select" name="ExpenseAccountRef[value]" required>
                    <?php if (isset($ExpenseAccountRef) && $ExpenseAccountRef) : ?>
                        <option value="">Select Expense Account</option>
                        <?php foreach ($ExpenseAccountRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($item->ExpenseAccountRef) && $item->ExpenseAccountRef == $value->Id ? 'selected' : '' ?> ><?= $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Expense Account') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>InvStartDate <span class="text-danger">*</span></label>
                <input type="date" name="InvStartDate" class="form-control" value="<?= isset($item->InvStartDate) ? $item->InvStartDate : '' ?>" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'InvStartDate') ?></small>
            </div>
        </div>

    </div>
    <button type="submit" class="btn btn-custom mt-2"><?= __('Submit') ?></button>
</form>

<script>
    $('document').ready(function() {

        switch ($('select[name=Type]').val()) {
            case 'Inventory':
                if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') != undefined) {
                    $('select[name="IncomeAccountRef[value]"]').attr('disabled', false)
                }
                if ($('select[name="AssetAccountRef[value]"]').attr('disabled') != undefined) {
                    $('select[name="AssetAccountRef[value]"]').attr('disabled', false)
                }
                if ($('input[name=InvStartDate]').attr('disabled') != undefined) {
                    $('input[name=InvStartDate]').attr('disabled', false)
                }
                if ($('input[name=QtyOnHand]').attr('disabled') != undefined) {
                    $('input[name=QtyOnHand]').attr('disabled', false)
                }
                break;
            case 'Service':
                if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') != undefined) {
                    $('select[name="IncomeAccountRef[value]"]').attr('disabled', false)
                }
                if ($('select[name="AssetAccountRef[value]"]').attr('disabled') == undefined) {
                    $('select[name="AssetAccountRef[value]"]').attr('disabled', true)
                }
                if ($('input[name=InvStartDate]').attr('disabled') == undefined) {
                    $('input[name=InvStartDate]').attr('disabled', true)
                }
                if ($('input[name=QtyOnHand]').attr('disabled') == undefined) {
                    $('input[name=QtyOnHand]').attr('disabled', true)
                }
                break;
            case 'NonInventory':
                if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') == undefined) {
                    $('select[name="IncomeAccountRef[value]"]').attr('disabled', true)
                }
                if ($('select[name="AssetAccountRef[value]"]').attr('disabled') == undefined) {
                    $('select[name="AssetAccountRef[value]"]').attr('disabled', true)
                }
                if ($('input[name=InvStartDate]').attr('disabled') == undefined) {
                    $('input[name=InvStartDate]').attr('disabled', true)
                }
                if ($('input[name=QtyOnHand]').attr('disabled') == undefined) {
                    $('input[name=QtyOnHand]').attr('disabled', true)
                }
                break;
        }

        $('select[name=Type]').on('change', function() {
            switch ($(this).val()) {
                case 'Inventory':
                    if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') != undefined) {
                        $('select[name="IncomeAccountRef[value]"]').attr('disabled', false)
                    }
                    if ($('select[name="AssetAccountRef[value]"]').attr('disabled') != undefined) {
                        $('select[name="AssetAccountRef[value]"]').attr('disabled', false)
                    }
                    if ($('input[name=InvStartDate]').attr('disabled') != undefined) {
                        $('input[name=InvStartDate]').attr('disabled', false)
                    }
                    if ($('input[name=QtyOnHand]').attr('disabled') != undefined) {
                        $('input[name=QtyOnHand]').attr('disabled', false)
                    }
                    break;
                case 'Service':
                    if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') != undefined) {
                        $('select[name="IncomeAccountRef[value]"]').attr('disabled', false)
                    }
                    if ($('select[name="AssetAccountRef[value]"]').attr('disabled') == undefined) {
                        $('select[name="AssetAccountRef[value]"]').attr('disabled', true)
                    }
                    if ($('input[name=InvStartDate]').attr('disabled') == undefined) {
                        $('input[name=InvStartDate]').attr('disabled', true)
                    }
                    if ($('input[name=QtyOnHand]').attr('disabled') == undefined) {
                        $('input[name=QtyOnHand]').attr('disabled', true)
                    }
                    break;
                case 'NonInventory':
                    if ($('select[name="IncomeAccountRef[value]"]').attr('disabled') == undefined) {
                        $('select[name="IncomeAccountRef[value]"]').attr('disabled', true)
                    }
                    if ($('select[name="AssetAccountRef[value]"]').attr('disabled') == undefined) {
                        $('select[name="AssetAccountRef[value]"]').attr('disabled', true)
                    }
                    if ($('input[name=InvStartDate]').attr('disabled') == undefined) {
                        $('input[name=InvStartDate]').attr('disabled', true)
                    }
                    if ($('input[name=QtyOnHand]').attr('disabled') == undefined) {
                        $('input[name=QtyOnHand]').attr('disabled', true)
                    }
                    break;
            }
        })
    })
</script>