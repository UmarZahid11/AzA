<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($department->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($department->Id) ? $department->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" name="Name" class="form-control" value="<?= isset($department->Name) ? $department->Name : '' ?>" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" required />
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'department name') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Parent department <span class="text-danger">*</span></label>
                <select class="form-select" name="ParentRef[value]" required>
                    <?php if (isset($parentRef)) : ?>
                        <?php foreach ($parentRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($department->ParentRef) && $department->ParentRef == $value->Id ? 'selected' : '' ?>><?= $value->Name ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Parent department') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>is SubDepartment <span class="text-danger">*</span></label>
                <select class="form-select" name="SubDepartment" required>
                    <option value="">Is SubDepartment?</option>
                    <option value="true" <?= isset($department->SubDepartment) && $department->SubDepartment == 'true' ? 'selected' : '' ?>>Yes</option>
                    <option value="false" <?= isset($department->SubDepartment) && ($department->SubDepartment == 'false' || $department->SubDepartment == '') ? 'selected' : '' ?>>No</option>
                </select>
                <small class="invalid-feedback"><?= __('Select a valid option.') ?></small>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>

<script>
    $('document').ready(function() {
        if ($('select[name=SubDepartment]').val() == 'true') {
            $('select[name="ParentRef[value]"]').attr('disabled', false)
        } else {
            $('select[name="ParentRef[value]"]').attr('disabled', true)
        }
        $('body').on('change', 'select[name=SubDepartment]', function() {
            if ($(this).val() == 'true') {
                $('select[name="ParentRef[value]"]').attr('disabled', false)
            } else {
                $('select[name="ParentRef[value]"]').attr('disabled', true)
            }
        })

    })
</script>