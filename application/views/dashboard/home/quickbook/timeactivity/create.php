<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($timeactivity->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($timeactivity->Id) ? $timeactivity->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Name Of <span class="text-danger">*</span></label>
                <select class="form-select" name="NameOf" required>
                    <option value="">Select NameOf</option>
                    <option value="Vendor" <?= isset($timeactivity) && property_exists($timeactivity, 'NameOf') && $timeactivity->NameOf == 'Vendor' ? 'selected' : ''; ?> >Vendor</option>
                    <option value="Employee" <?= isset($timeactivity) && property_exists($timeactivity, 'NameOf') && $timeactivity->NameOf == 'Employee' ? 'selected' : ''; ?> >Employee</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'NameOf') ?></small>
            </div>
        </div>
        <div class="col-md-6">
            <label>TxnDate <span class="text-danger">*</span></label>
            <input type="date" class="form-control" name="TxnDate" value="<?= isset($timeactivity) && property_exists($timeactivity, 'TxnDate') ? $timeactivity->TxnDate : ''; ?>" required />
            <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'TxnDate') ?></small>
        </div>
        <div class="col-md-6">
            <label>StartTime <span class="text-danger">*</span></label>
            <?php $date = new DateTime('00:00:00'); ?>
            <select class="form-select" name="StartTime" required>
                <option value=""></option>
                <?php for($i = 0; $i < 96; $i++): ?>
                    <option value="<?= $date->format('h:i:s'); ?>" <?= isset($timeactivity) && property_exists($timeactivity, 'StartTime') && $date->format('h:i:s') == date('h:i:s', strtotime($timeactivity->StartTime)) ? 'selected' : '' ?> ><?= $date->format('h:i a'); ?></option>
                    <?php $date->add(new DateInterval('PT15M')); ?>
                <?php endfor; ?>
            </select>
            <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'StartTime') ?></small>
        </div>
        <div class="col-md-6">
            <label>EndTime <span class="text-danger">*</span></label>
            <?php $date = new DateTime('00:00:00'); ?>
            <select class="form-select" name="EndTime" required>
                <option value=""></option>
                <?php for($i = 0; $i < 96; $i++): ?>
                    <option value="<?= $date->format('h:i:s'); ?>" <?= isset($timeactivity) && property_exists($timeactivity, 'EndTime') && $date->format('h:i:s') == date('h:i:s', strtotime($timeactivity->EndTime)) ? 'selected' : '' ?> ><?= $date->format('h:i a'); ?></option>
                    <?php $date->add(new DateInterval('PT15M')); ?>
                <?php endfor; ?>
            </select>
            <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'EndTime') ?></small>
        </div>
        <?php if (isset($EmployeeRef) && is_array($EmployeeRef) && count($EmployeeRef) > 0) : ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Employee <span class="text-danger">*</span></label>
                    <select class="form-select" name="EmployeeRef[value]" required>
                        <option value="">Select Employee</option>
                        <?php foreach ($EmployeeRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($timeactivity) && property_exists($timeactivity, 'EmployeeRef') && $timeactivity->EmployeeRef == $value->Id ? 'selected' : ''; ?> ><?= $value->DisplayName ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Employee') ?></small>
                </div>
            </div>
        <?php endif; ?>
        <?php if (isset($VendorRef) && is_array($VendorRef) && count($VendorRef) > 0) : ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Vendor <span class="text-danger">*</span></label>
                    <select class="form-select" name="VendorRef[value]" required>
                        <option value="">Select Vendor</option>
                        <?php foreach ($VendorRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($timeactivity) && property_exists($timeactivity, 'VendorRef') && $timeactivity->VendorRef == $value->Id ? 'selected' : ''; ?> ><?= $value->DisplayName ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="invalid-feedback"><?= sprintf(__('A valid %s is required.'), 'Vendor') ?></small>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>

<script>
    $(document).ready(function() {
        switch ($('select[name=NameOf]').val()) {
            case 'Vendor':
                $('select[name="EmployeeRef[value]"]').attr('disabled', true)
                $('select[name="VendorRef[value]"]').attr('disabled', false)
                break;
            case 'Employee':
                $('select[name="EmployeeRef[value]"]').attr('disabled', false)
                $('select[name="VendorRef[value]"]').attr('disabled', true)
                break;
        }
        $('select[name=NameOf]').on('change', function() {
            switch ($(this).val()) {
                case 'Vendor':
                    $('select[name="EmployeeRef[value]"]').attr('disabled', true)
                    $('select[name="VendorRef[value]"]').attr('disabled', false)
                    break;
                case 'Employee':
                    $('select[name="EmployeeRef[value]"]').attr('disabled', false)
                    $('select[name="VendorRef[value]"]').attr('disabled', true)
                    break;
            }
        })
    })
</script>