<div class="dashboard-content">
    <i class="fa-light fa-shopping-cart"></i>
    <h4><?= __('Checkout') ?> </h4>
    <hr />
    <p class="text-danger">* Indicates required field</p>

    <div class="create-profile-form">
        <form class="checkoutForm" id="checkoutForm" method="POST" novalidate>
            <input type="hidden" name="_token" />
            <input type="hidden" name="order[order_user_id]" value="<?= ($this->userid) ?>" />
            <?php if ($type == INSERT) : ?>
                <input type="hidden" name="order[order_amount]" value="<?= $this->cart->total() ?>" />
                <input type="hidden" name="order[order_fee]" value="<?= (($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)) ?>" />
                <input type="hidden" name="order[order_tax]" value="<?= $this->cart->total() * (g('db.admin.tax') / 100) ?>" />
                <input type="hidden" name="order[order_total]" value="<?= ($this->cart->total() + (($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0))) ?>" />

                <input type="hidden" name="order[order_reference_type]" value="<?= $reference_type ?>" />

                <?php
                    $cart_reference_type = cartReferenceType($this->cart->contents());

                    $shippingCost = 0;
                    if($cart_reference_type == PRODUCT_REFERENCE_PRODUCT) {
                        $shippingCost = ($this->cart->total()) * (g('db.admin.shipping') > 0 ? (g('db.admin.shipping') / 100) : 0);
                    }
                ?>

                <?php if($shippingCost): ?>
                    <input type="hidden" name="order[order_shipping]" value="<?= (($this->cart->total()) * (g('db.admin.shipping') > 0 ? (g('db.admin.shipping') / 100) : 0)) ?>" />
                <?php else: ?>
                    <input type="hidden" name="order[order_shipping]" value="0" />
                <?php endif; ?>

            <?php elseif ($type == UPDATE) : ?>
                <input type="hidden" name="order[order_amount]" value="<?= $order['order_amount'] ?>" />
                <input type="hidden" name="order[order_fee]" value="<?= $order['order_fee'] ?>" />
                <input type="hidden" name="order[order_shipping]" value="<?= $order['order_shipping'] ?>" />
                <input type="hidden" name="order[order_tax]" value="<?= $order['order_tax'] ?>" />
                <input type="hidden" name="order[order_total]" value="<?= $order['order_total'] ?>" />

                <?php $shippingCost = $order['order_shipping']; ?>
            <?php endif; ?>


            <?php if (isset($order_id) && isset($order) && $order['order_id'] == $order_id) : ?>
                <input type="hidden" name="order_id" value="<?= JWT::encode($order_id) ?>" />
            <?php endif; ?>

            <?php if (isset($reference_type) && $reference_type) : ?>
                <input type="hidden" name="order[order_reference_type]" value="<?= ($reference_type) ?>" />
            <?php endif; ?>

            <div class="row">
                <div class="col-md-7">
                    <p>Billing details</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_firstname">First name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="order[order_firstname]" id="order_firstname" required value="<?= isset($order) && $order['order_firstname'] ? $order['order_firstname'] : $this->user_data['signup_firstname'] ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_lastname">Last name <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="order[order_lastname]" id="order_lastname" required value="<?= isset($order) && $order['order_lastname'] ? $order['order_lastname'] : $this->user_data['signup_lastname'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order_email">Email address <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="order[order_email]" id="order_email" required value="<?= isset($order) && $order['order_email'] ? $order['order_email'] : $this->user_data['signup_email'] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="order_phone">Phone <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="order[order_phone]" id="order_phone" required value="<?= isset($order) && $order['order_phone'] ? $order['order_phone'] : $this->user_data['signup_phone'] ?>" />
                    </div>

                    <div class="form-group">
                        <label for="order_address1">Address <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" name="order[order_address1]" id="order_address1" required value="<?= isset($order) && $order['order_address1'] ? $order['order_address1'] : $this->user_data['signup_address'] ?>" />
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_country">Country <span class="text-danger">*</span></label>
                                <select class="form-select" name="order[order_country]" id="order_country" required>
                                    <option value="">Please select a country</option>
                                    <?php if (isset($countries) && is_array($countries)) : ?>
                                        <?php foreach ($countries as $key => $value) : ?>
                                            <option value="<?= $value['name'] ?>" <?= isset($order) && $order['order_country'] == $value['name'] ? 'selected' : ($value['name'] == $this->user_data['signup_country'] ? 'selected' : ($value['name'] == 'United States' ? 'selected' : '')) ?>><?= $value['name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_state">State / Province <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="order[order_state]" id="order_state" placeholder="State / Province" required value="<?= isset($order) && $order['order_state'] ? $order['order_state'] : $this->user_data['signup_state'] ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_city">Town / City <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="order[order_city]" id="order_city" placeholder="Town / City" required value="<?= isset($order) && $order['order_city'] ? $order['order_city'] : $this->user_data['signup_city'] ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order_zip">Postcode <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="order[order_zip]" id="order_zip" placeholder="Postcode / Zip" required value="<?= isset($order) && $order['order_zip'] ? $order['order_zip'] : $this->user_data['signup_zip'] ?>" pattern="\d{5,5}(-\d{4,4})?" />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="notes">Order notes</label>
                        <textarea class="form-control" name="order[order_note]" id="notes" placeholder="Notes about your order, e.g. special notes for delivery."><?= isset($order) && $order['order_note'] ? $order['order_note'] : ''; ?></textarea>
                    </div>

                    <?php if($shippingCost): ?>
                        <hr />

                        <p>Shipping details</h3>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="form-check" name="shipping_check" value="1" <?= isset($order) && $order['order_is_shipment_address'] ? 'checked' : ''; ?> />&nbsp;<small>Ship to a different address?</small>
                            </label>
                        </div>

                    <?php endif; ?>

                    <div id="shipping_detail" class="d-none">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_firstname">First Name <span class="text-danger">*</span></label>
                                    <input class="form-control shipping_input" type="text" name="order[order_shipping_firstname]" id="order_shipping_firstname" required value="<?= isset($order) && $order['order_shipping_firstname'] ? $order['order_shipping_firstname'] : $this->user_data['signup_firstname'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_lastname">Last Name <span class="text-danger">*</span></label>
                                    <input class="form-control shipping_input" type="text" name="order[order_shipping_lastname]" id="order_shipping_lastname" required value="<?= isset($order) && $order['order_shipping_lastname'] ? $order['order_shipping_lastname'] : $this->user_data['signup_lastname'] ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="order_shipping_email">Email Address <span class="text-danger">*</span></label>
                            <input class="form-control shipping_input" type="text" name="order[order_shipping_email]" id="order_shipping_email" required value="<?= isset($order) && $order['order_shipping_email'] ? $order['order_shipping_email'] : $this->user_data['signup_email'] ?>" />
                        </div>
                        <div class="form-group">
                            <label for="order_shipping_phone">Phone <span class="text-danger">*</span></label>
                            <input class="form-control shipping_input" type="text" name="order[order_shipping_phone]" id="order_shipping_phone" required value="<?= isset($order) && $order['order_shipping_phone'] ? $order['order_shipping_phone'] : $this->user_data['signup_phone'] ?>" />
                        </div>

                        <div class="form-group">
                            <label for="order_shipping_address1">Address <span class="text-danger">*</span></label>
                            <input class="form-control shipping_input" type="text" name="order[order_shipping_address1]" id="order_shipping_address1" required value="<?= isset($order) && $order['order_shipping_address1'] ? $order['order_shipping_address1'] : $this->user_data['signup_address'] ?>" />
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_country">Country <span class="text-danger">*</span></label>
                                    <select class="form-select shipping_input" name="order[order_shipping_country]" id="order_shipping_country" required>
                                        <option value="">Please select a country</option>
                                        <?php if (isset($countries) && is_array($countries)) : ?>
                                            <?php foreach ($countries as $key => $value) : ?>
                                                <option value="<?= $value['name'] ?>" <?= isset($order) && $order['order_shipping_country'] == $value['name'] ? 'selected' : ($value['name'] == $this->user_data['signup_country'] ? 'selected' : ($value['name'] == 'United States' ? 'selected' : '')) ?>><?= $value['name'] ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_state">State / Province <span class="text-danger">*</span></label>
                                    <input class="form-control shipping_input" type="text" name="order[order_shipping_state]" id="order_shipping_state" placeholder="State / Province" required value="<?= isset($order) && $order['order_shipping_state'] ? $order['order_shipping_state'] : $this->user_data['signup_state'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_city">Town / City <span class="text-danger">*</span></label>
                                    <input class="form-control shipping_input" type="text" name="order[order_shipping_city]" id="order_shipping_city" placeholder="Town / City" required value="<?= isset($order) && $order['order_shipping_city'] ? $order['order_shipping_city'] : $this->user_data['signup_city'] ?>" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="order_shipping_zip">Postcode <span class="text-danger">*</span></label>
                                    <input class="form-control shipping_input" type="text" name="order[order_shipping_zip]" id="order_shipping_zip" placeholder="Postcode / Zip" required value="<?= isset($order) && $order['order_shipping_zip'] ? $order['order_shipping_zip'] : $this->user_data['signup_zip'] ?>" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 order">
                    <!-- <p>Your Order</p> -->
                    <div>
                        <h4 class="d-inline">Product<span class="float-right">Total</span></h4>
                    </div>
                    <hr />
                    <div>
                        <?php if ($type == INSERT) : ?>
                            <?php foreach ($this->cart->contents() as $key => $value) : ?>
                                <b class="d-inline">
                                    <a href="<?= $value['options']['url'] ?>" target="_blank"><?= $value['name'] ?></a>
                                    <small>x</small> <?= $value['qty'] ?><span class="float-right"><?= price($value['qty'] * $value['price']) ?></span><br />
                                    <span class="font-12"><?= $value['qty'] . ' x ' . price($value['price']) ?></span>
                                </b><br />
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php if ($type == UPDATE && isset($order_item) && is_array($order_item)) : ?>
                                <?php foreach ($order_item as $key => $value) : ?>
                                    <?php $product = $this->model_product->find_by_pk($value['order_item_product_id']); ?>
                                    <b class="d-inline">
                                        <a href="<?= l('dashboard/product/detail/' . $product['product_slug']) ?>" target="_blank">
                                            <?= $product['product_name'] ?>
                                        </a>
                                        <small>x</small> <?= $value['order_item_qty'] ?><span class="float-right"><?= price($value['order_item_qty'] * $value['order_item_price']) ?></span><br />
                                        <span class="font-12"><?= $value['order_item_qty'] . ' x ' . price($value['order_item_price']) ?></span>
                                    </b><br />
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <hr />
                    <div>
                        <?php if ($type == INSERT) : ?>
                            <h6 class="d-inline">Subtotal<span class="float-right"><?php echo price($this->cart->total()); ?></span></h6>
                        <?php elseif ($type == UPDATE) : ?>
                            <h6 class="d-inline">Subtotal<span class="float-right"><?php echo price($order['order_amount']); ?></span></h6>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?php if ($type == INSERT) : ?>
                            <b class="d-inline">Platform fee (<?= g('db.admin.service_fee') ?>%)<span class="float-right"><?= price(($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)) ?></span></b>
                        <?php elseif ($type == UPDATE) : ?>
                            <b class="d-inline">Platform fee (<?= $order['order_fee'] > 0 ? number_format(($order['order_fee'] / $order['order_amount']) * 100, 2) : 0 ?>%)<span class="float-right"><?= price($order['order_fee']) ?></span></b>
                        <?php endif; ?>
                    </div>

                    <?php if($shippingCost): ?>
                        <div>
                            <?php if ($type == INSERT) : ?>
                                <b class="d-inline">Estimated shipping
                                    <span id="shipping-percentage" data-value="<?= g('db.admin.shipping') ?>">
                                        (<?= g('db.admin.shipping') ?>%)
                                    </span>
                                    <span class="float-right" id="shipping-total" data-value="<?= ($this->cart->total() * (g('db.admin.shipping') / 100)) ?>">
                                        <?= price($this->cart->total() * (g('db.admin.shipping') / 100)) ?>
                                    </span>
                                </b>
                            <?php elseif ($type == UPDATE) : ?>
                                <b class="d-inline">Estimated shipping
                                    <span id="shipping-percentage" data-value="<?= g('db.admin.shipping') ?>">
                                        <?= '(' . g('db.admin.shipping') . '%)' ?>
                                    </span>
                                    <span class="float-right" id="shipping-total" data-value="<?= ($order['order_amount'] * (g('db.admin.shipping') / 100)) ?>">
                                        <?= price($order['order_amount'] * (g('db.admin.shipping') / 100)) ?>
                                    </span>
                                </b>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <?php if ($type == INSERT) : ?>
                            <b class="d-inline">Tax
                                <span id="tax-percentage" data-value="<?= g('db.admin.tax') ?>">
                                    (<?= g('db.admin.tax') ?>%)
                                </span>
                                <span class="float-right" id="tax-total" data-value="<?= ($this->cart->total() * (g('db.admin.tax') / 100)) ?>">
                                    <?= price($this->cart->total() * (g('db.admin.tax') / 100)) ?>
                                </span>
                            </b>
                        <?php elseif ($type == UPDATE) : ?>
                            <b class="d-inline">Tax
                                <span id="tax-percentage" data-value="<?= g('db.admin.tax') ?>">
                                    <?= '(' . g('db.admin.tax') . '%)' ?>
                                </span>
                                <span class="float-right" id="tax-total" data-value="<?= ($order['order_amount'] * (g('db.admin.tax') / 100)) ?>">
                                    <?= price($order['order_amount'] * (g('db.admin.tax') / 100)) ?>
                                </span>
                            </b>
                        <?php endif; ?>
                    </div>

                    <hr />

                    <div>
                        <?php if ($type == INSERT) : ?>
                            <b class="d-inline">Order total
                                <span class="float-right" id="order-total">
                                    <?= price(
                                        $this->cart->total() +
                                        (($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)) +
                                        (($this->cart->total()) * (g('db.admin.shipping') > 0 ? (g('db.admin.shipping') / 100) : 0))
                                    ) ?>
                                </span>
                            </b>
                        <?php elseif ($type == UPDATE) : ?>
                            <b class="d-inline">Order total<span class="float-right"><?= price($order['order_total']) ?></span></b>
                        <?php endif; ?>
                    </div>

                    <hr />

                    <?php if (in_array($type, [INSERT, UPDATE])) : ?>
                        <button type="submit" id="checkout-submit" class="btn btn-custom w-100" data-text="<?= $type == INSERT ? 'Place order' : 'Update order'; ?>"><?= $type == INSERT ? 'Place order' : 'Update order'; ?></button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Create our number formatter.
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });

    function calculateTotal() {
        var total = parseFloat($('input[name="order[order_amount]"]').val()) + parseFloat($('input[name="order[order_fee]"]').val()) + parseFloat($('input[name="order[order_shipping]"]').val()) + parseFloat($('input[name="order[order_tax]"]').val())
        $('input[name="order[order_total]"]').val(total)
        $('#order-total').html(formatter.format(total))
    }

    /**
     * Method showTaxLoad
     *
     * @return void
     */
    function showTaxLoad(set_value = false, response = '') {
        if (!set_value) {
            $('#tax-total').html('<span class="loading-ellipsis"></span>')
            $('#tax-percentage').html('<span class="loading-ellipsis"></span>')
            $('#checkout-submit').attr('disabled', true)
        } else if (set_value) {
            //
            if (response.total_rate) {
                var rate = parseFloat(response.total_rate);
                var tax_total = $('input[name="order[order_amount]"]').val() * rate
                $('#tax-total').html(formatter.format(tax_total))
                $('#tax-percentage').html('(' + parseFloat(rate * 100).toFixed(1) + '%)')
                $('input[name="order[order_tax]"]').val(tax_total)
            } else {
                var rate = $('#tax-percentage').data('value');
                var tax_total = $('input[name="order[order_amount]"]').val() * (rate / 100)
                $('#tax-total').html(formatter.format(tax_total))
                $('#tax-percentage').html('(' + rate + '%)')
                $('input[name="order[order_tax]"]').val(tax_total)
            }
            $('#checkout-submit').attr('disabled', false)
            calculateTotal()
        }
    }

    /**
     * Method calculateTax
     *
     * @return void
     */
    async function calculateTax(zip) {
        await showTaxLoad();
        var url = base_url + 'dashboard/order/taxCalculator'
        var data = {
            '_token': $("meta[name=csrf-token]").attr('content'),
            'zip_code': zip
        }

        return new Promise((resolve, reject) => {
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
            })
		})
    }

    $(document).ready(function() {

        // PHONE MASK //
        // intlTelInput
        $(function($) {
            var iti = $('#order_phone').intlTelInput({
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js',
                initialCountry: 'us',
                separateDialCode: false,
                nationalMode: false,
                autoHideDialCode: true,
                // onlyCountries: [ 'cn', 'us', 'ca', 'gr', 'es', 'pt', 'hu', 'fk' ],
            });
            var iti = $('#order_shipping_phone').intlTelInput({
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js',
                initialCountry: 'us',
                separateDialCode: false,
                nationalMode: false,
                autoHideDialCode: true,
            });
        })

        /**
         * Method dynamicMask
         *
         * @param {string} placeholder
         *
         * @return void
         */
        function dynamicMask(placeholder, selector) {
            if (placeholder != "" && placeholder != undefined) {
                var dynamoMask = placeholder.replace(/[0-9]/g, 0);
                $(selector).mask(dynamoMask)
            } else {
                // call after 0.1 s
                setTimeout(function() {
                    var placeholder = $(selector).attr('placeholder')
                    dynamicMask(placeholder, selector)
                }, 100)
            }
        }

        // dyanmic mask on load
        dynamicMask($("#order_phone").attr('placeholder'), '#order_phone');
        dynamicMask($("#order_shipping_phone").attr('placeholder'), '#order_shipping_phone');

        // dyanmic mask on change
        $('#order_phone').on("countrychange", function(event) {
            dynamicMask($("#order_phone").attr('placeholder'), '#order_phone');
        })
        $('#order_shipping_phone').on("countrychange", function(event) {
            dynamicMask($("#order_shipping_phone").attr('placeholder'), '#order_shipping_phone');
        })
        // PHONE MASK //

        //
        $('#order_phone').on('keyup keydown change focus', function() {
            if ($('.order_phone').val() == "" || !($.trim($('#order_phone').val())) || !$('#order_phone').intlTelInput("isValidNumber")) {
                error = true;
                $('#order_phone').addClass('force-invalid');
            } else {
                $('#order_phone').removeClass('force-invalid');
            }
        })
        $('#order_shipping_phone').on('keyup keydown change focus', function() {
            if ($('#order_shipping_phone').val() == "" || !($.trim($('#order_shipping_phone').val())) || !$('#order_shipping_phone').intlTelInput("isValidNumber")) {
                error = true;
                $('#order_shipping_phone').addClass('force-invalid');
            } else {
                $('#order_shipping_phone').removeClass('force-invalid');
            }
        })
        //

        //
        $('.shipping_input').attr('disabled', true)

        //
        if ($('input[name="shipping_check"]').length && $('input[name="shipping_check"]')[0].checked) {
            if ($('#shipping_detail').hasClass('d-none')) {
                $('#shipping_detail').removeClass('d-none')
            }
            $('.shipping_input').attr('disabled', false)
        } else {
            if (!$('#shipping_detail').hasClass('d-none')) {
                $('#shipping_detail').addClass('d-none')
            }
            $('.shipping_input').attr('disabled', true)
        }
        //
        $('input[name="shipping_check"]').on('change', function() {
            if ($('input[name="shipping_check"]')[0].checked) {
                if ($('#shipping_detail').hasClass('d-none')) {
                    $('#shipping_detail').removeClass('d-none')
                }
                $('.shipping_input').attr('disabled', false)
            } else {
                if (!$('#shipping_detail').hasClass('d-none')) {
                    $('#shipping_detail').addClass('d-none')
                }
                $('.shipping_input').attr('disabled', true)
            }
        })

        //
        $("#checkoutForm").submit(function(event) {
            event.preventDefault();

            var error = false;
            if ($('#order_phone').val() == "" || !($.trim($('#order_phone').val())) || !$('#order_phone').intlTelInput("isValidNumber")) {
                error = true;
                $('.order_phone').addClass('force-invalid');
                $('.order_phone').focus()
            } else {
                $('.order_phone').removeClass('force-invalid');
            }
            if ($('#order_shipping_phone').val() == "" || !($.trim($('#order_shipping_phone').val())) || !$('#order_shipping_phone').intlTelInput("isValidNumber")) {
                error = true;
                $('.order_shipping_phone').addClass('force-invalid');
                $('.order_shipping_phone').focus()
            } else {
                $('.order_shipping_phone').removeClass('force-invalid');
            }
    
            if (!$('#checkoutForm')[0].checkValidity() || error) {
                event.preventDefault()
                event.stopPropagation()
                $('#checkoutForm').addClass('was-validated');
                $('#checkoutForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#checkoutForm').removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $("#checkoutForm").serialize();
            var url = "<?php echo l('dashboard/order/checkoutAction'); ?>";

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
                        $('#checkout-submit').attr('disabled', true)
                        $('#checkout-submit').html('<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
                        // <div class="spinner-border spinner-border-sm"></div>
                    },
                    complete: function() {
                        $('#checkout-submit').attr('disabled', false)
                        $('#checkout-submit').html($('#checkout-submit').data('text'))
                    }
                })
    		}).then(
    		    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        if (response.redirect_url) {
                            location.href = response.redirect_url;
                        }
                    } else {
                        AdminToastr.error(response.txt ?? '<?= ERROR_MESSAGE ?>');
                    }
                }
            );
        })

        //
        if ($('#order_zip').val() !== "" && $('#order_zip').val().length == 5) {
            calculateTax($('#order_zip').val()).then(
                function(response) {
                    showTaxLoad(true, response.response[0])
                    if (response.status) {} else {
                        AdminToastr.error(response.txt)
                    }
                }
            );
        }

        $('body').on('input', '#order_zip', function() {
            if ($(this).val() !== "" && $(this).val().length == 5) {
                calculateTax($(this).val()).then(
                    function(response) {
                        showTaxLoad(true, response.response[0])
                        if (response.status) {} else {
                            AdminToastr.error(response.txt)
                        }
                    }
                );
            }
        })
    })
</script>