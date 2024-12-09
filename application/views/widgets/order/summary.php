<div class="row">
    <div class="col-md-12 col-xs-12 invoice-block text-right">
        <ul class="list-unstyled amounts">
            <li><strong style="color:#333">Subtotal</strong>
                : <?= $order['order_currency'] . ' ' . price_without_sign($order['order_amount']) ?> </li>

            <li><strong style="color:#333">Service Fee</strong>
                : <?= $order['order_currency'] . ' ' . price_without_sign($order['order_fee']) ?> </li>

            <?php if ($order['order_shipping']) : ?>
                <li><strong style="color:#333">Shipping Fee</strong>
                    : <?= $order['order_currency'] . ' ' . price_without_sign($order['order_shipping']) ?> </li>
            <?php endif; ?>

            <?php if ($order['order_tax']) : ?>
                <li><strong style="color:#333">Tax</strong>
                    : <?= $order['order_currency'] . ' ' . price_without_sign($order['order_tax']) ?> </li>
            <?php endif; ?>

            <li><strong style="color:#333">Total</strong>
                : <?= $order['order_currency'] . ' ' . price_without_sign($order['order_total']) ?> </li>
        </ul>
        <br />
    </div>
</div>