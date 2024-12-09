<style>
    /* */
    .contenteditable,
    .contenteditable:focus {
        width: 100% !important;
    }

    table.inventory td:nth-child(1) {
        width: 15%;
    }

    table.inventory td:nth-child(2) {
        width: 15%;
    }

    table.inventory td:nth-child(3) {
        width: 15%;
    }

    table.inventory td:nth-child(4) {
        width: 15%;
    }

    table.inventory td:nth-child(5) {
        width: 15%;
    }

    table.inventory td:nth-child(6) {
        width: 15%;
    }

    /* */

    th,
    td {
        border-width: 1px;
        padding: 0.5em;
        position: relative;
        text-align: left;
    }

    th,
    td {
        border-radius: 0.25em;
        border-style: solid;
    }

    th {
        background: #EEE;
        border-color: #BBB;
    }

    td {
        border-color: #DDD;
    }

    .contenteditable,
    .contenteditable:focus {
        border: none;
        background: none;
        outline: 0;
        width: -webkit-fill-available;
    }

    select.editable {
        background-color: #f5f6fa;
        border: none;
        outline: 0;
    }

    .inventory tr {
        display: block
    }

    td.break {
        float: left;
        line-height: 22px;
        width: 100%;
    }
</style>

<form id="quickbookSaveForm" method="POST" action="javascript:;" novalidate>
    <?php if (isset($bill->Id)) : ?>
        <input type="hidden" name="id" value="<?= isset($bill->Id) ? $bill->Id : 0 ?>" />
    <?php endif; ?>
    <input type="hidden" name="entity" value="<?= isset($entity) ? $entity : '' ?>" />

    <div class="col-md-6">
        <div class="form-group">
            <label>Vendor <span class="text-danger">*</span></label>
            <select class="form-select" name="VendorRef[value]" required>
                <?php if (isset($VendorRef)) : ?>
                    <option value="">Select Vendor</option>
                    <?php foreach ($VendorRef as $key => $value) : ?>
                        <option value="<?= $value->Id ?>" <?= isset($bill->VendorRef) && $bill->VendorRef == $value->Id ? 'selected' : '' ?>><?= $value->DisplayName ?? $value->FullyQualifiedName ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <small class="invalid-feedback"><?= sprintf(__('Select a valid %s.'), 'Vendor') ?></small>
        </div>
    </div>

    <?php if (isset($CurrencyRef) && is_array($CurrencyRef) && count($CurrencyRef) > 0) : ?>
        <div class="col-md-6">
            <div class="form-group">
                <label>Currency <span class="text-danger">*</span></label>
                <select class="form-select" name="CurrencyRef[value]" required>
                    <?php foreach ($CurrencyRef as $key => $value) : ?>
                        <option value="<?= $value->Id ?>" <?= isset($bill->CurrencyRef) && $bill->CurrencyRef == $value->Id ? 'selected' : '' ?>><?= $value->Name ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="invalid-feedback"><?= sprintf(__('%s is a required field.'), 'Currency') ?></small>
            </div>
        </div>
    <?php endif; ?>

    <label>Line</label>
    <table class="inventory">
        <tbody>
            <?php if (isset($bill->Line) && count($bill->Line) > 0) : ?>
                <?php foreach ($bill->Line as $key => $value) : ?>
                    <?php $detailType = $value->{'DetailType'}; ?>
                    <?php if (property_exists($value, $detailType)) : ?>

                        <input type="hidden" name="Line[<?=$key?>][Id]" value="<?= property_exists($value, 'Id') ? $value->Id : '' ?>" />
                        <tr>
                            <input type="hidden" class="form-control" name="Line[<?=$key?>][DetailType]" value="<?= isset($bill->DetailType) ? $bill->DetailType : 'AccountBasedExpenseLineDetail' ?>" />
                            <td><input type="number" class="form-control" step="0.01" min="1" name="Line[<?=$key?>][Amount]" value="<?= isset($value->Amount) ? $value->Amount : '' ?>" placeholder="Amount" required /></td>
                            <td>
                                <span>
                                    <?php if (isset($AccountRef)) : ?>
                                        <select class="form-control editable" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][AccountRef][value]" required>
                                            <option value="">Select Account</option>
                                            <?php foreach ($AccountRef as $keyValue => $valueAccount) : ?>
                                                <option value="<?= $valueAccount->Id ?>" <?= property_exists($value->{$detailType}, 'AccountRef') && $value->{$detailType}->AccountRef == $valueAccount->Id ? 'selected' : '' ?> ><?= $valueAccount->FullyQualifiedName ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (isset($CustomerRef) && count($CustomerRef) > 0) : ?>
                                    <select class="form-control editable" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][CustomerRef][value]">
                                        <option value="">Select Customer</option>
                                        <?php foreach ($CustomerRef as $keyCustomer => $valueCustomer) : ?>
                                            <option value="<?= $valueCustomer->Id ?>" <?= property_exists($value->{$detailType}, 'CustomerRef') && $value->{$detailType}->CustomerRef == $valueCustomer->Id ? 'selected' : '' ?> ><?= $valueCustomer->FullyQualifiedName ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span>
                                    <?php if (isset($ClassRef)) : ?>
                                        <select class="form-control editable" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][ClassRef][value]">
                                            <option value="">Select Class</option>
                                            <?php foreach ($ClassRef as $keyClass => $valueClass) : ?>
                                                <option value="<?= $valueClass->Id ?>" <?= property_exists($value->{$detailType}, 'ClassRef') && $value->{$detailType}->ClassRef == $valueClass->Id ? 'selected' : '' ?> ><?= $valueClass->Name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td>
                                <?php if (isset($taxCodeRef) && count($taxCodeRef) > 0) : ?>
                                    <select class="form-control editable" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][TaxCodeRef][value]">
                                        <option value="">Select Taxcode</option>
                                        <?php foreach ($taxCodeRef as $keyTax => $valueTax) : ?>
                                            <option value="<?= $valueTax->Id ?>" <?= property_exists($value->{$detailType}, 'TaxCodeRef') && $value->{$detailType}->TaxCodeRef == $valueTax->Id ? 'selected' : '' ?> ><?= $valueTax->Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </td>
                            <td>
                                <select class="form-control editable" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][BillableStatus]">
                                    <option value="">Select Billable Status</option>
                                    <option value="Billable">Billable</option>
                                    <option value="NotBillable">NotBillable</option>
                                    <option value="HasBeenBilled">HasBeenBilled</option>
                                </select>
                            </td>
                            <td colspan="1" class="break w-25"><span><input type="number" step="0.01" class="contenteditable form-control" value="<?= property_exists($value->{$detailType}, 'TaxAmount') ? $value->{$detailType}->TaxAmount : '' ?>" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][TaxAmount]" min="0" placeholder="Tax amount" /></span></td>
                            <td colspan="1" class="break w-25"><span><input type="number" step="0.01" class="contenteditable form-control" value="<?= property_exists($value->{$detailType}, 'TaxInclusiveAmt') ? $value->{$detailType}->TaxInclusiveAmt : '' ?>" name="Line[<?=$key?>][AccountBasedExpenseLineDetail][TaxInclusiveAmt]" min="0" placeholder="Tax inclusive amount" /></span></td>
                            <td colspan="4" class="break w-50">
                                <input type="text" name="Line[<?=$key?>][Description]" class="form-control contenteditable" value="<?= property_exists($value, 'Description') ? $value->Description : '' ?>" placeholder="Enter item description or details" />
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <input type="hidden" class="form-control" name="Line[0][DetailType]" value="<?= isset($bill->DetailType) ? $bill->DetailType : 'AccountBasedExpenseLineDetail' ?>" />
                    <td><input type="number" class="form-control" step="0.01" min="1" name="Line[0][Amount]" value="<?= isset($bill->TotalAmt) ? $bill->TotalAmt : '' ?>" placeholder="Amount" required /></td>
                    <td>
                        <span>
                            <?php if (isset($AccountRef)) : ?>
                                <select class="form-control editable" name="Line[0][AccountBasedExpenseLineDetail][AccountRef][value]" required>
                                    <option value="">Select Account</option>
                                    <?php foreach ($AccountRef as $key => $value) : ?>
                                        <option value="<?= $value->Id ?>" ><?= $value->FullyQualifiedName ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (isset($CustomerRef) && count($CustomerRef) > 0) : ?>
                            <select class="form-control editable" name="Line[0][AccountBasedExpenseLineDetail][CustomerRef][value]">
                                <option value="">Select Customer</option>
                                <?php foreach ($CustomerRef as $key => $value) : ?>
                                    <option value="<?= $value->Id ?>"><?= $value->FullyQualifiedName ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span>
                            <?php if (isset($ClassRef)) : ?>
                                <select class="form-control editable" name="Line[0][AccountBasedExpenseLineDetail][ClassRef][value]">
                                    <option value="">Select Class</option>
                                    <?php foreach ($ClassRef as $key => $value) : ?>
                                        <option value="<?= $value->Id ?>" ><?= $value->Name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </span>
                    </td>
                    <td>
                        <?php if (isset($taxCodeRef) && count($taxCodeRef) > 0) : ?>
                            <select class="form-control editable" name="Line[0][AccountBasedExpenseLineDetail][TaxCodeRef][value]">
                                <option value="">Select Taxcode</option>
                                <?php foreach ($taxCodeRef as $key => $value) : ?>
                                    <option value="<?= $value->Id ?>"><?= $value->Name ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </td>
                    <td>
                        <select class="form-control editable" name="Line[0][AccountBasedExpenseLineDetail][BillableStatus]">
                            <option value="">Select Billable Status</option>
                            <option value="Billable">Billable</option>
                            <option value="NotBillable">NotBillable</option>
                            <option value="HasBeenBilled">HasBeenBilled</option>
                        </select>
                    </td>
                    <td colspan="1" class="break w-25"><span><input type="number" step="0.01" class="contenteditable form-control" value="" name="Line[0][AccountBasedExpenseLineDetail][TaxAmount]" min="0" placeholder="Tax amount" /></span></td>
                    <td colspan="1" class="break w-25"><span><input type="number" step="0.01" class="contenteditable form-control" value="" name="Line[0][AccountBasedExpenseLineDetail][TaxInclusiveAmt]" min="0" placeholder="Tax inclusive amount" /></span></td>
                    <td colspan="4" class="break w-50">
                        <input type="text" name="Line[0][Description]" class="form-control contenteditable" placeholder="Enter item description or details" />
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <a class="add">+</a>

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
        var emptyColumn = document.createElement('tr');
        var count = $('.inventory tr').length;

        emptyColumn.innerHTML = '<input type="hidden" class="form-control" name="Line[' + count + '][DetailType]" value="<?= isset($bill->DetailType) ? $bill->DetailType : 'AccountBasedExpenseLineDetail' ?>" />' +
            '<td><a class="cut">-</a><input type="number" class="form-control" step="0.01" min="1" name="Line[' + count + '][Amount]" value="<?= isset($bill->Amount) ? $bill->Amount : '' ?>" placeholder="Amount" required /></td>' +
            '<td><span><?php if (isset($AccountRef)) : ?><select class="form-control editable" name="Line[' + count + '][AccountBasedExpenseLineDetail][AccountRef][value]" required><option value="">Select Account</option><?php foreach ($AccountRef as $key => $value) : ?><option value="<?= $value->Id ?>" ><?= $value->FullyQualifiedName ?></option><?php endforeach; ?></select><?php endif; ?></span></td>' +
            '<td><?php if (isset($CustomerRef) && count($CustomerRef) > 0) : ?><select class="form-control editable" name="Line[' + count + '][AccountBasedExpenseLineDetail][CustomerRef][value]"><option value="">Select Customer</option><?php foreach ($CustomerRef as $key => $value) : ?><option value="<?= $value->Id ?>"><?= addcslashes($value->FullyQualifiedName, "'\\"); ?></option><?php endforeach; ?></select><?php endif; ?></td>' +
            '<td><span><?php if (isset($ClassRef)) : ?><select class="form-control editable" name="Line[' + count + '][AccountBasedExpenseLineDetail][ClassRef][value]"><option value="">Select Class</option><?php foreach ($ClassRef as $key => $value) : ?><option value="<?= $value->Id ?>" ><?= $value->Name ?></option><?php endforeach; ?></select><?php endif; ?></span></td>' +
            '<td><?php if (isset($taxCodeRef) && count($taxCodeRef) > 0) : ?><select class="form-control editable" name="Line[' + count + '][AccountBasedExpenseLineDetail][TaxCodeRef][value]"><option value="">Select Taxcode</option><?php foreach ($taxCodeRef as $key => $value) : ?><option value="<?= $value->Id ?>"><?= $value->Name ?></option><?php endforeach; ?></select><?php endif; ?></td>' +
            '<td><select class="form-control editable" name="Line[' + count + '][AccountBasedExpenseLineDetail][BillableStatus]"><option value="">Select Billable Status</option><option value="Billable">Billable</option><option value="NotBillable">NotBillable</option><option value="HasBeenBilled">HasBeenBilled</option></select></td>' +
            '<td colspan="1" class="break w-25"><span><input type="number" class="contenteditable form-control" value="" name="Line[' + count + '][AccountBasedExpenseLineDetail][TaxAmount]" min="0" step="0.01" placeholder="Tax amount" /></span></td>' +
            '<td colspan="1" class="break w-25"><span><input type="number" class="contenteditable form-control" value="" name="Line[' + count + '][AccountBasedExpenseLineDetail][TaxInclusiveAmt]" min="0" step="0.01" placeholder="Tax inclusive amount" /></span></td>' +
            '<td colspan="4" class="break w-50"><input type="text" name="Line[' + count + '][Description]" class="form-control contenteditable" placeholder="Enter item description or details" /></td>';

        return emptyColumn;
    }

    function onContentLoad() {

        var
            input = document.querySelector('input');

        function onClick(e) {
            var element = e.target.querySelector('[contenteditable]'),
                row;

            element && e.target != document.documentElement && e.target != document.body && element.focus();

            if (e.target.matchesSelector('.add')) {
                document.querySelector('table.inventory tbody').appendChild(generateTableRow());
            } else if (e.target.className == 'cut') {
                row = e.target.ancestorQuerySelector('tr');

                row.parentNode.removeChild(row);
            }
        }

        if (window.addEventListener) {
            document.addEventListener('click', onClick);
        }
    }

    window.addEventListener && document.addEventListener('DOMContentLoaded', onContentLoad);
</script>