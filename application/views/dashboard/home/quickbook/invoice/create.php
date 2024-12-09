<style>
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

<div class="dashboard-content">
    <i class="fa-regular fa-book"></i>
    <h4><?= (isset($title) ? ucfirst($title) : 'Create') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <div>
        <form id="quickbookSaveInvoiceForm" method="POST" action="javascript:;" novalidate>
            <?php if (isset($invoice->Id)) : ?>
                <input type="hidden" name="id" value="<?= isset($invoice->Id) ? $invoice->Id : 0 ?>" />
            <?php endif; ?>

            <div class="row">
                <div class="col-md-3 headerInvoice">
                    <h4><?= __('Company') ?></h4>
                    <address>
                        <p><?= isset($companyInfo->LegalName) ? $companyInfo->LegalName : (isset($companyInfo->CompanyName) ? $companyInfo->CompanyName : '') ?></p>
                        <p><?= isset($companyInfo->CompanyAddr) ? $companyInfo->CompanyAddr->Line1 . ($companyInfo->CompanyAddr->Line2 ? ', ' . $companyInfo->CompanyAddr->Line2 : '') . ($companyInfo->CompanyAddr->Line3 ?  ', ' . $companyInfo->CompanyAddr->Line3 : '') . ($companyInfo->CompanyAddr->Line4 ?  ', ' . $companyInfo->CompanyAddr->Line4 : '') : '' ?></p>
                        <p><?= isset($companyInfo->CompanyAddr) ? ($companyInfo->CompanyAddr->City ? $companyInfo->CompanyAddr->City : '') . ($companyInfo->CompanyAddr->CountrySubDivisionCode ? ', ' . $companyInfo->CompanyAddr->CountrySubDivisionCode : '') . ($companyInfo->CompanyAddr->PostalCode ?  ', ' . $companyInfo->CompanyAddr->PostalCode : '') : '' ?>.</p>
                    </address>
                </div>

            </div>
            <div class="articleInvoice">
                <h4><?= __('Recipient') ?></h4>
                <input type="hidden" name="CustomerRef[value]" value="<?= isset($customerInfo->Id) && $customerInfo->Id ? $customerInfo->Id : 0 ?>" />
                <input type="hidden" name="EmailStatus" value="NeedToSend" />
                <div class="form-group has-search input-group w-25 mb-2">
                    <span class="fa fa-edit form-control-feedback"></span>
                    <input type="email" class="form-control contenteditable" name="BillEmail[Address]" value="<?= isset($customerInfo->PrimaryEmailAddr->Address) && $customerInfo->PrimaryEmailAddr->Address ? $customerInfo->PrimaryEmailAddr->Address : '' ?>" placeholder="Billing Email" required />
                </div>

                <address>
                    <p><?= isset($customerInfo->FullyQualifiedName) ? $customerInfo->FullyQualifiedName : (isset($customerInfo->CompanyName) ? $customerInfo->CompanyName : '') ?></p>
                    <p><?= isset($customerInfo->BillAddr) ? $customerInfo->BillAddr->Line1 . ($customerInfo->BillAddr->Line2 ? ', ' . $customerInfo->BillAddr->Line2 : '') . ($customerInfo->BillAddr->Line3 ?  ', ' . $cocustomerInfompanyInfo->BillAddr->Line3 : '') . ($customerInfo->BillAddr->Line4 ?  ', ' . $customerInfo->BillAddr->Line4 : '') : '' ?></p>
                    <p><?= isset($customerInfo->BillAddr) ? ($customerInfo->BillAddr->City ? $customerInfo->BillAddr->City : '') . ($customerInfo->BillAddr->CountrySubDivisionCode ? ', ' . $customerInfo->BillAddr->CountrySubDivisionCode : '') . ($customerInfo->BillAddr->PostalCode ?  ', ' . $customerInfo->BillAddr->PostalCode : '') : '' ?>.</p>
                </address>
                <table class="meta">
                    <tr>
                        <th><span><?= __('Invoice no') ?> #</span></th>
                        <td><input type="number" class="contenteditable form-control" min="1" name="DocNumber" value="<?= isset($invoice->DocNumber) ? $invoice->DocNumber : ''; ?>" placeholder="1001" /></td>
                    </tr>
                    <tr></tr>
                    <?php if (isset($term) && count($term) > 0) : ?>
                        <tr>
                            <th><span><?= __('Terms') ?></span></th>
                            <td class="p-0">
                                <span>
                                    <select class="form-select editable" name="SalesTermRef[value]">
                                        <?php foreach ($term as $key => $value) : ?>
                                            <option value="<?= $value->Id ?>" <?= (isset($invoice->SalesTermRef) && $invoice->SalesTermRef == $value->Id) ? 'selected' : ''; ?>><?= $value->Name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </span>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <th><span><?= __('Invoice Date') ?></span></th>
                        <td><span><input type="date" class="contenteditable form-control" id="TxnDate" name="TxnDate" value="<?= isset($invoice->TxnDate) ? $invoice->TxnDate : '' ?>" /></span></td>
                    </tr>
                    <tr>
                        <th><span><?= __('Due Date') ?></span></th>
                        <td><span><input type="date" class="contenteditable form-control" id="DueDate" name="DueDate" value="<?= isset($invoice->DueDate) ? $invoice->DueDate : '' ?>" /></span></td>
                    </tr>
                    <tr class="d-none">
                        <th><span><?= __('Amount Due') ?></span></th>
                        <td><span id="prefix" contenteditable>$</span><span>5.00</span></td>
                    </tr>
                </table>
                <table class="inventory">
                    <tbody>
                        <?php if (isset($invoice->Line) && count($invoice->Line) > 0) : ?>
                            <?php foreach ($invoice->Line as $key => $value) : ?>
                                <?php $detailType = $value->{'DetailType'}; ?>
                                <?php if (property_exists($value, $detailType)) : ?>

                                    <input type="hidden" name="Line[Id][]" value="<?= property_exists($value, 'Id') ? $value->Id : '' ?>" />
                                    <tr>
                                        <td>
                                            <?= property_exists($value, 'Id') && $value->Id ? '' : '<a class="cut">-</a>'; ?>

                                            <span>
                                                <?php if (isset($items)) : ?>
                                                    <select class="form-control editable" name="Line[SalesItemLineDetail][ItemRef][value][]">
                                                        <?php foreach ($items as $keyValue => $itemValue) : ?>
                                                            <option value="<?= $itemValue->Id ?>" data-name="<?= $itemValue->Name ?>" <?= property_exists($value->{$detailType}, 'ItemRef') && $value->{$detailType}->ItemRef == $itemValue->Id ? 'selected' : ''; ?>><?= $itemValue->Name ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span>
                                                <select class="form-control editable">
                                                    <option value="Unit"><?= __('Unit') ?></option>
                                                    <option value="Hour"><?= __('Hour') ?></option>
                                                    <!-- <option value="Flat rate"><?//= __('Flat rate') ?></option> -->
                                                </select>
                                            </span>
                                        </td>
                                        <td><span><input type="number" class="contenteditable form-control" value="<?= isset($value->{$detailType}->UnitPrice) ? $value->{$detailType}->UnitPrice : 0 ?>" name="Line[SalesItemLineDetail][UnitPrice][]" min="1" placeholder="Rate" required /></span></td>
                                        <td><span><input type="number" class="contenteditable form-control" value="<?= isset($value->{$detailType}->Qty) ? $value->{$detailType}->Qty : 0 ?>" name="Line[SalesItemLineDetail][Qty][]" min="1" placeholder="Qty" required /></span></td>
                                        <td>
                                            <?php if (isset($taxCode) && count($taxCode) > 0) : ?>
                                                <select class="form-control editable" name="Line[SalesItemLineDetail][TaxCodeRef][value][]">
                                                    <?php foreach ($taxCode as $keyTax => $valueTax) : ?>
                                                        <option value="<?= $valueTax->Id ?>" <?= (isset($value->{$detailType}->TaxCodeRef) && $value->{$detailType}->TaxCodeRef == $valueTax->Id ? 'selected' : '') ?> ><?= $valueTax->Name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                        </td>
                                        <td><span data-prefix>$</span><span>5</span></td>
                                        <td colspan="5" class="break">
                                            <input type="text" name="Line[Description][]" class="form-control contenteditable" value="<?= isset($value->Description) ? $value->Description : ''; ?>" placeholder="Enter item description or details" required />
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td>
                                    <!-- <a class="cut">-</a> -->
                                    <span>
                                        <?php if (isset($items)) : ?>
                                            <select class="form-control editable" name="Line[SalesItemLineDetail][ItemRef][value][]">
                                                <?php foreach ($items as $key => $value) : ?>
                                                    <option value="<?= $value->Id ?>" data-name="<?= $value->Name ?>"><?= $value->Name ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td>
                                    <span>
                                        <select class="form-control editable">
                                            <option value="Unit"><?= __('Unit') ?></option>
                                            <option value="Hour"><?= __('Hour') ?></option>
                                            <!-- <option value="Flat rate"><?//= __('Flat rate') ?></option> -->
                                        </select>
                                    </span>
                                </td>
                                <td><span><input type="number" class="contenteditable form-control" value="5" name="Line[SalesItemLineDetail][UnitPrice][]" min="1" placeholder="Rate" required /></span></td>
                                <td><span><input type="number" class="contenteditable form-control" value="1" name="Line[SalesItemLineDetail][Qty][]" min="1" placeholder="Qty" required /></span></td>
                                <td>
                                    <?php if (isset($taxCode) && count($taxCode) > 0) : ?>
                                        <select class="form-control editable" name="Line[SalesItemLineDetail][TaxCodeRef][value][]">
                                            <?php foreach ($taxCode as $keyTax => $valueTax) : ?>
                                                <option value="<?= $valueTax->Id ?>"><?= $valueTax->Name ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </td>
                                <td><span data-prefix>$</span><span>5</span></td>
                                <td colspan="5" class="break">
                                    <input type="text" name="Line[Description][]" class="form-control contenteditable" placeholder="Enter item description or details" required />
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <a class="add">+</a>
                <table class="balance">
                    <tr>
                        <th><span><?= __('Subtotal') ?></span></th>
                        <td><span data-prefix>$</span><span>5</span></td>
                    </tr>
                    <tr class="d-none">
                        <th><span><?= __('Amount Paid') ?></span></th>
                        <td><span data-prefix>$</span><span contenteditable>0.00</span></td>
                    </tr>
                    <tr>
                        <th><span><?= __('Invoice Total') ?></span></th>
                        <td><span data-prefix>$</span><span>5</span></td>
                    </tr>
                </table>
            </div>
            <div class="form-group w-50">
                <label><?= __('Note to customer') ?></label>
                <textarea name="CustomerMemo[value]" class="form-control" maxlength="1000" placeholder="<?= __('Thank you for your business') ?>"><?= isset($invoice->CustomerMemo) ? $invoice->CustomerMemo : '' ?></textarea>
            </div>
            <div class="form-group mt-5">
                <input type="submit" class="btn btn-custom" value="Submit" />
            </div>
        </form>
    </div>
</div>

<script>
    // not used
    $("#logoUpload").change(function() {
        var file_obj = $(this);
        // size
        readURL(this, $('#imagePreview'));
    })

    TxnDate.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];
    if (TxnDate.value == "") {
        TxnDate.value = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0]
    }

    DueDate.min = new Date(Date.now() + (3600 * 1000 * 24 * 31)).toISOString().split("T")[0]
    if (DueDate.value == "") {
        DueDate.value = new Date(Date.now() + (3600 * 1000 * 24 * 31)).toISOString().split("T")[0]
    }

    $('#quickbookSaveInvoiceForm').submit(function(e) {
        e.preventDefault();
        if (!$('#quickbookSaveInvoiceForm')[0].checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
            $('#quickbookSaveInvoiceForm').addClass('was-validated');
            $('#quickbookSaveInvoiceForm').find(":invalid").first().focus();
            hideLoader()
            return false;
        } else {
            $('#quickbookSaveInvoiceForm').removeClass('was-validated');
        }

        var data = ($(this).serialize())
        var url = base_url + 'quickbook/saveInvoice'

        new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function() {
                    showLoader()
                },
                complete: function() {
                    hideLoader()
                }
            })
		}).then(
		    function(response) {
		        if (response.status) {
                    swal({
                        title: "Success",
                        text: response.txt,
                        icon: "success",
                    }).then(() => {
                        window.open('<?= l('dashboard/home/quickbook-view/'.$entity.'/') ?>' + response.result.Id, '_blank');
                    });
                    if ($('input[name=id]').length == '') {
                        $('#quickbookSaveInvoiceForm').each(function() {
                            this.reset();
                        });
                    }
                } else {
                    swal({
                        title: "Error",
                        text: response.txt[0] == undefined ? response.txt : response.txt[0],
                        icon: "warning"
                    }).then((isConfirm) => {
                        if (response.refresh) {
                            location.reload();
                        }
                    });
                }
		    }
	    )
    })
</script>

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

        emptyColumn.innerHTML = '<td><a class="cut">-</a>' +
            '<span><?php if (isset($items)) : ?><select class="form-control editable" name="Line[SalesItemLineDetail][ItemRef][value][]"><?php foreach ($items as $key => $value) : ?><option value="<?= $value->Id ?>"><?= $value->Name ?></option><?php endforeach; ?></select><?php endif; ?></span></td>' +
            '<td><span><select class="form-control editable"><option value="Unit">Unit</option><option value="Hour">Hour</option></select></span></td>' +
            '<td><span><input type="number" class="contenteditable form-control" value="5" name="Line[SalesItemLineDetail][UnitPrice][]" min="1" placeholder="Rate" required /></span></td>' +
            '<td><span><input type="number" class="contenteditable form-control" value="1" name="Line[SalesItemLineDetail][Qty][]" min="1" placeholder="Qty" required /></span></td>' +
            '<td><?php if (isset($taxCode) && count($taxCode) > 0) : ?><select class="form-control editable" name="Line[SalesItemLineDetail][TaxCodeRef][value][]"><?php foreach ($taxCode as $keyTax => $valueTax) : ?><option value="<?= $valueTax->Id ?>"><?= $valueTax->Name ?></option><?php endforeach; ?></select><?php endif; ?></td>' +
            '<td><span data-prefix>$</span><span>5</span></td>' +
            '<td colspan="5" class="break"><input type="text" class="form-control contenteditable" placeholder="Enter item description or details" name="Line[Description][]" required /></td>';

        return emptyColumn;
    }

    function parseFloatHTML(element) {
        return parseFloat(element.innerHTML.replace(/[^\d\.\-]+/g, '')) || 0;
    }

    function parsePrice(number) {
        return number.toFixed(2).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1,');
    }

    /* Update Number
    /* ========================================================================== */

    function updateNumber(e) {
        var
            activeElement = document.activeElement,
            value = parseFloat(activeElement.innerHTML),
            wasPrice = activeElement.innerHTML == parsePrice(parseFloatHTML(activeElement));

        if (!isNaN(value) && (e.keyCode == 38 || e.keyCode == 40 || e.wheelDeltaY)) {
            e.preventDefault();

            value += e.keyCode == 38 ? 1 : e.keyCode == 40 ? -1 : Math.round(e.wheelDelta * 0.025);
            value = Math.max(value, 0);

            activeElement.innerHTML = wasPrice ? parsePrice(value) : value;
        }

        updateInvoice();
    }

    /* Update Invoice
    /* ========================================================================== */

    function updateInvoice() {
        var total = 0;
        var cells, price, total, a, i;

        // update inventory cells
        // ======================

        for (var a = document.querySelectorAll('table.inventory tbody tr'), i = 0; a[i]; ++i) {
            // get inventory row cells
            cells = a[i].querySelectorAll('span:last-child');
            if (cells.length) {
                // set price as cell[2] * cell[3]
                price = cells[2].firstChild.value * cells[3].firstChild.value;

                // add price to total
                total += price;

                // set row total
                cells[4].innerHTML = price;
            }
        }

        // update balance cells
        // ====================

        // get balance cells
        cells = document.querySelectorAll('table.balance td:last-child span:last-child');

        // set total
        cells[0].innerHTML = total;

        // set balance and meta balance - uncomment when 'payment due' is visible
        cells[2].innerHTML = document.querySelector('table.meta tr:last-child td:last-child span:last-child').innerHTML = parsePrice(total - parseFloatHTML(cells[1]));

        // update prefix formatting
        // ========================

        var prefix = document.querySelector('#prefix').innerHTML;
        for (a = document.querySelectorAll('[data-prefix]'), i = 0; a[i]; ++i) a[i].innerHTML = prefix;

        // update price formatting
        // =======================

        for (a = document.querySelectorAll('span[data-prefix] + span'), i = 0; a[i]; ++i)
            if (document.activeElement != a[i]) a[i].innerHTML = parsePrice(parseFloatHTML(a[i]));
    }

    /* On Content Load
    /* ========================================================================== */

    function onContentLoad() {
        updateInvoice();

        var
            input = document.querySelector('input'),
            image = document.querySelector('img');

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

            updateInvoice();
        }

        function onEnterCancel(e) {
            e.preventDefault();

            image.classList.add('hover');
        }

        function onLeaveCancel(e) {
            e.preventDefault();

            image.classList.remove('hover');
        }

        function onFileInput(e) {
            image.classList.remove('hover');

            var
                reader = new FileReader(),
                files = e.dataTransfer ? e.dataTransfer.files : e.target.files,
                i = 0;

            reader.onload = onFileLoad;

            while (files[i]) reader.readAsDataURL(files[i++]);
        }

        function onFileLoad(e) {
            var data = e.target.result;

            image.src = data;
        }

        if (window.addEventListener) {
            document.addEventListener('click', onClick);

            document.addEventListener('mousewheel', updateNumber);
            document.addEventListener('keydown', updateNumber);

            document.addEventListener('keydown', updateInvoice);
            document.addEventListener('keyup', updateInvoice);

            input.addEventListener('focus', onEnterCancel);
            input.addEventListener('mouseover', onEnterCancel);
            input.addEventListener('dragover', onEnterCancel);
            input.addEventListener('dragenter', onEnterCancel);

            input.addEventListener('blur', onLeaveCancel);
            input.addEventListener('dragleave', onLeaveCancel);
            input.addEventListener('mouseout', onLeaveCancel);

            input.addEventListener('drop', onFileInput);
            input.addEventListener('change', onFileInput);
        }
    }

    window.addEventListener && document.addEventListener('DOMContentLoaded', onContentLoad);
</script>