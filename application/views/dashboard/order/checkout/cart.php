<div class="dashboard-content">
    <i class="fa-light fa-shopping-cart"></i>
    <h4><?= __('Shopping Cart') ?> </h4>
    <hr />

    <div class="shopping-cart">

        <?php if ($this->cart->contents()) : ?>

            <?php
                $cart_reference_type = cartReferenceType($this->cart->contents());

                $shippingCost = 0;
                if($cart_reference_type == PRODUCT_REFERENCE_PRODUCT) {
                    $shippingCost = ($this->cart->total()) * (g('db.admin.shipping') > 0 ? (g('db.admin.shipping') / 100) : 0);
                }
            ?>

            <div class="column-labels">
                <label class="product-details">Item</label>
                <label class="product-price">Price</label>
                <label class="product-quantity">Quantity</label>
                <label class="product-line-price">Total</label>
                <label class="product-removal">Action</label>
            </div>

            <form class="update_cart_form" method="POST">
                <input type="hidden" name="_token" value="" />
                <?php foreach ($this->cart->contents() as $key => $value) : ?>
                    <input type="hidden" name="id[]" value="<?= $value['id'] ?>" />
                    <input type="hidden" name="rowid[]" value="<?= $value['rowid'] ?>" />
                    <div class="product">
                        <div class="product-details">
                            <div class="product-title">
                                <a href="<?= $value['options']['url'] ?>" target="_blank">
                                    <?= $value['name'] ?>
                                </a>
                            </div>
                            <p class="product-description">
                                <?= strip_string($value['options']['description']) ?>
                            </p>
                        </div>
                        <div class="product-price"><?= price($value['price']) ?></div>
                        <div class="product-quantity">
                            <?php if(isset($value['options']['type']) && (in_array($value['options']['type'], [PRODUCT_REFERENCE_SERVICE, PRODUCT_REFERENCE_TECHNOLOGY]))): ?>
                                <?= $value['qty'] ?>
                                <input type="hidden" class="form-control" name="qty[]" value="<?= $value['qty'] ?>" min="1" />
                            <?php else: ?>
                                <input type="number" class="form-control" name="qty[]" value="<?= $value['qty'] ?>" min="1" />
                            <?php endif; ?>
                        </div>
                        <div class="product-line-price"><?= price($value['price'] * $value['qty']) ?></div>
                        <div class="product-removal">
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Remove this product from shopping cart.") ?>" href="javascript:;" class="delete_cart_item" data-id="<?= $this->model_product->getProductRow($value['id']) ?>">
                                <i class="fa fa-trash"></i>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-custom" id="updateCartBtn">Update Cart</button>

            </form>

            <div class="totals">
                <div class="totals-item">
                    <label>Subtotal</label>
                    <div class="totals-value" id="cart-subtotal"><?= price($this->cart->total()) ?></div>
                </div>
                <div class="totals-item">
                    <?php if($shippingCost): ?>
                        <?php if((int) g('db.admin.shipping') == 0): ?>
                            <label>Free shipping (<?= g('db.admin.shipping') ?>%)</label>
                        <?php else: ?>
                            <label>Estimated Shipping Fee (<?= g('db.admin.shipping') ?>%)</label>
                        <?php endif; ?>
                        <div class="totals-value" id="cart-tax"><?= price($shippingCost) ?></div>
                    <?php endif; ?>
                </div>
                <div class="totals-item">
                    <label>Service Fee (<?= g('db.admin.service_fee') ?>%)</label>
                    <div class="totals-value" id="cart-tax"><?= price(($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)) ?></div>
                </div>
                <div class="totals-item">
                    <label>Tax <a href="javascript:;" data-toggle="tooltip" title="Tax will be calculated in the next step."><i class="fa fa-question-circle"></i></a></label>
                    <div class="totals-value float-right" id="cart-tax"><?php echo price(0) ?></div>
                </div>
                <div class="totals-item totals-item-total">
                    <label>Grand Total</label>
                    <div class="totals-value" id="cart-total">
                        <?= price(
                            $this->cart->total() +
                            (($this->cart->total()) * (g('db.admin.service_fee') > 0 ? (g('db.admin.service_fee') / 100) : 0)) +
                            ($shippingCost)
                        ) ?>
                    </div>
                </div>
            </div>

            <a href="<?= l('dashboard/order/checkout/' . JWT::encode(0) . '/' . constant('ORDER_REFERENCE_' . strtoupper($cart_reference_type))) ?>" class="btn btn-custom float-right" id="checkoutBtn">
                Checkout
            </a>

        <?php else : ?>
            <?= ERROR_MESSAGE_CART_EMPTY ?>
        <?php endif; ?>

    </div>

</div>

<script>
    $(document).ready(function() {
        $('body').on('click', '#checkoutBtn', function() {
            $('#checkoutBtn').addClass('disabled')
            $('#checkoutBtn').html('Processing ...')

            setTimeout(function(){
                $('#checkoutBtn').removeClass('disabled')
                $('#checkoutBtn').html('Checkout')
            }, 10000)
        })
    })
</script>