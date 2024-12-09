<style>
    .add {
        margin: auto;
    }
    .cut {
        opacity: 1;
        padding: 0.25em 0.5em;
        position: absolute;
        top: auto;
        left: 341px;
        width: auto;
        height: auto;
    }
</style>

<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($billpayment->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($billpayment->Id) ? $billpayment->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Vendor <span class="text-danger">*</span></label>
                <select class="form-select" name="VendorRef[value]" required>
                    <?php if (isset($VendorRef)) : ?>
                        <option value="">Select Vendor</option>
                        <?php foreach ($VendorRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($billpayment->VendorRef) && $billpayment->VendorRef == $value->Id ? 'selected' : '' ?>><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'Vendor') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>TotalAmt <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" name="TotalAmt" value="<?= isset($billpayment->TotalAmt) && $billpayment->TotalAmt ? $billpayment->TotalAmt : 0 ?>" min="0" />
                <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'TotalAmt') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>PayType <span class="text-danger">*</span></label>
                <select class="form-select" name="PayType" required>
                    <option value="">Select PayType</option>
                    <option value="Check" <?= isset($billpayment->PayType) && $billpayment->PayType == 'Check' ? 'selected' : '' ?>>Check</option>
                    <option value="CreditCard" <?= isset($billpayment->PayType) && $billpayment->PayType == 'CreditCard' ? 'selected' : '' ?>>CreditCard</option>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'PayType') ?></small>
            </div>
        </div>

        <?php if (isset($CurrencyRef) && is_array($CurrencyRef) && count($CurrencyRef) > 0) : ?>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Currency <span class="text-danger">*</span></label>
                    <select class="form-select" name="CurrencyRef[value]" required>
                        <?php foreach ($CurrencyRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($billpayment->CurrencyRef) && $billpayment->CurrencyRef == $value->Id ? 'selected' : '' ?>><?= $value->Name ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Currency') ?></small>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-6">
            <div class="form-group">
                <label>CheckPayment <span class="text-danger">*</span></label>
                <select class="form-select" name="CheckPayment[BankAccountRef][value]" required>
                    <?php if (isset($AccountRef)) : ?>
                        <option value="">Select CheckPayment</option>
                        <?php foreach ($AccountRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($billpayment->CheckPayment->BankAccountRef) && $billpayment->CheckPayment->BankAccountRef ? 'selected' : '' ?>><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'CheckPayment') ?></small>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>CreditCardPayment <span class="text-danger">*</span></label>
                <select class="form-select" name="CreditCardPayment[CCAccountRef][value]" required>
                    <?php if (isset($AccountRef)) : ?>
                        <option value="">Select CreditCardPayment</option>
                        <?php foreach ($AccountRef as $key => $value) : ?>
                            <option value="<?= $value->Id ?>" <?= isset($billpayment->CreditCardPayment->CCAccountRef) && $billpayment->AccountRef == $value->Id ? 'selected' : '' ?>><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'CreditCardPayment') ?></small>
            </div>
        </div>

        <h4>Line</h4>
        <div class="line">
            <div class="row lineItems">
                <input type="hidden" name="Line[0][LinkedTxn][0][TxnType]" value="Bill" />
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Transaction Id <span class="text-danger">*</span></label>
                        <select class="form-select TxnId" name="Line[0][LinkedTxn][0][TxnId]" required data-key="0" >
                            <?php if (isset($bill)) : ?>
                                <option value="">Select Transaction Id</option>
                                <?php foreach ($bill as $key => $value) : ?>
                                    <option value="<?= $value->Id ?>" data-amount="<?= $value->Balance ?>"><?= $value->Id ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'bill transaction Id') ?></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Amount <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" readonly="" name="Line[0][Amount]" value="Bill" placeholder="Line Amount" />
                </div>
            </div>
        </div>
    </div>
    <a class="add">+</a>
    <hr />
    <button type="submit" class="btn btn-custom mt-2">Submit</button>
</form>

<script>
    /* Prototyping
/* ========================================================================== */

    (function(window, ElementPrototype, ArrayPrototype, polyfill) {
        function NodeList() {
            [polyfill]
        }
        NodeList.prototype.length = ArrayPrototype.length;

        ElementPrototype.matchesSelector = ElementPrototype.matchesSelector ||
            ElementPrototype.mozMatchesSelector ||
            ElementPrototype.msMatchesSelector ||
            ElementPrototype.oMatchesSelector ||
            ElementPrototype.webkitMatchesSelector ||
            function matchesSelector(selector) {
                return ArrayPrototype.indexOf.call(this.parentNode.querySelectorAll(selector), this) > -1;
            };

        ElementPrototype.ancestorQuerySelectorAll = ElementPrototype.ancestorQuerySelectorAll ||
            ElementPrototype.mozAncestorQuerySelectorAll ||
            ElementPrototype.msAncestorQuerySelectorAll ||
            ElementPrototype.oAncestorQuerySelectorAll ||
            ElementPrototype.webkitAncestorQuerySelectorAll ||
            function ancestorQuerySelectorAll(selector) {
                for (var cite = this, newNodeList = new NodeList; cite = cite.parentElement;) {
                    if (cite.matchesSelector(selector)) ArrayPrototype.push.call(newNodeList, cite);
                }

                return newNodeList;
            };

        ElementPrototype.ancestorQuerySelector = ElementPrototype.ancestorQuerySelector ||
            ElementPrototype.mozAncestorQuerySelector ||
            ElementPrototype.msAncestorQuerySelector ||
            ElementPrototype.oAncestorQuerySelector ||
            ElementPrototype.webkitAncestorQuerySelector ||
            function ancestorQuerySelector(selector) {
                return this.ancestorQuerySelectorAll(selector)[0] || null;
            };
    })(this, Element.prototype, Array.prototype);

    /* Helper Functions
    /* ========================================================================== */

    function generateTableRow() {
        var emptyColumn = document.createElement('div');
        emptyColumn.classList.add('row')
        emptyColumn.classList.add('lineItems')
        var count = $('.lineItems').length;
        console.log(count)

        emptyColumn.innerHTML = '<input type="hidden" name="Line[' + count + '][LinkedTxn][0][TxnType]" value="Bill" />'+
            '<div class="col-md-6"><a class="cut">-</a><div class="form-group"><label>Transaction Id <span class="text-danger">*</span></label><select class="form-select TxnId" name="Line[' + count + '][LinkedTxn][0][TxnId]" required data-key="' + count + '" ><?php if (isset($bill)) : ?><option value="">Select Transaction Id</option><?php foreach ($bill as $key => $value) : ?><option value="<?= $value->Id ?>" data-amount="<?= $value->Balance ?>"><?= $value->Id ?></option><?php endforeach; ?><?php endif; ?></select><small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'Bill') ?></small></div></div>' +
            '<div class="col-md-6"><label>Amount <span class="text-danger">*</span></label><input class="form-control" type="number" readonly="" name="Line[' + count + '][Amount]" value="Bill" placeholder="Line Amount" /></div></div>';

        return emptyColumn;
    }

    function onContentLoad() {

        var input = document.querySelector('input')

        function onClick(e) {
            var element = e.target.querySelector('[contenteditable]'),
                row;

            element && e.target != document.documentElement && e.target != document.body && element.focus();

            if (e.target.matchesSelector('.add')) {
                document.querySelector('div.line').appendChild(generateTableRow());
            } else if (e.target.className == 'cut') {
                row = e.target.ancestorQuerySelector('div.lineItems');

                row.parentNode.removeChild(row);
            }
        }

        if (window.addEventListener) {
            document.addEventListener('click', onClick);
        }
    }

    window.addEventListener && document.addEventListener('DOMContentLoaded', onContentLoad);
</script>

<script>
    $('body').on('change', '.TxnId', function(){
        var key = ($(this).data('key'))
        $('input[name="Line[' + key + '][Amount]"]').val($(this).find(':selected').data('amount'))
    })

    switch($('select[name=PayType]').val()) {
        case 'Check':
            $('select[name="CheckPayment[BankAccountRef][value]"]').attr('disabled', false)
            $('select[name="CreditCardPayment[CCAccountRef][value]"]').attr('disabled', true)
            break;
        case 'CreditCard':
            $('select[name="CheckPayment[BankAccountRef][value]"]').attr('disabled', true)
            $('select[name="CreditCardPayment[CCAccountRef][value]"]').attr('disabled', false)
            break;
    }

    $('body').on('change', 'select[name=PayType]', function(){
        switch($(this).val()) {
            case 'Check':
                $('select[name="CheckPayment[BankAccountRef][value]"]').attr('disabled', false)
                $('select[name="CreditCardPayment[CCAccountRef][value]"]').attr('disabled', true)
                break;
            case 'CreditCard':
                $('select[name="CheckPayment[BankAccountRef][value]"]').attr('disabled', true)
                $('select[name="CreditCardPayment[CCAccountRef][value]"]').attr('disabled', false)
                break;
        }
    })

</script>