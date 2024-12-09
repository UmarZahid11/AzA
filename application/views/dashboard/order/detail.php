<div class="dashboard-content">
    <span class="float-right">
        <strong><small class="font-12">Order #<?php echo $order['order_id']; ?> </small></strong><br />
        <small class="font-12"> <?php echo date("M d, Y h:i a", strtotime($order['order_createdon'])) ?></small>
    </span>
    <i class="fa fa-shopping-cart"></i>
    <h4><?= __('Order'); ?> <span class="font-13">Details</span></h4>
    <hr />

    <?php global $config; ?>

    <?php
    switch ($order['order_reference_type']) {
        case ORDER_REFERENCE_MEMBERSHIP:
            $column = '3';
            break;
        case ORDER_REFERENCE_TECHNOLOGY:
        case ORDER_REFERENCE_PRODUCT:
                $column = '4';
            break;
        default:
            $column = '4';
    }
    ?>

    <div class="portlet box green">

        <div class="portlet-body">

            <div class="row">

                <div class="col-md-<?= $column ?>">
                    <span><strong>Billing Information:</strong></span>
                    <ul class="list-unstyled">
                        <li><strong>First name: </strong><?= ucfirst($order['order_firstname']) ?? NA; ?> </li>
                        <li><strong>Last name: </strong><?= ucfirst($order['order_lastname']) ?? NA; ?></li>
                        <li><strong>Email: </strong><a href="mailto:<?= $order['order_email']; ?>"><?= $order['order_email']; ?></a> </li>
                        <li><strong>Phone: </strong><a href="<?= $order['order_phone'] ? 'tel:' . $order['order_phone'] : 'javascript:;'; ?>"><?= $order['order_phone'] ?? NA; ?> </a></li>
                    </ul>
                </div>

                <div class="col-md-<?= $column ?>">
                    <span><strong>Address Information:</strong></span>
                    <ul class="list-unstyled">
                        <li><strong>Address: </strong><?= $order['order_address1'] ?? (isset($order['signup_address']) && $order['signup_address'] ? $order['signup_address'] : NA) ?? NA; ?> </li>
                        <li><strong>City: </strong><?= $order['order_city'] ?? (isset($order['signup_city']) && $order['signup_city'] ? $order['signup_city'] : NA) ?? NA; ?> </li>
                        <li><strong>Zip: </strong><?= $order['order_zip'] ?? (isset($order['signup_zip']) && $order['signup_zip'] ? $order['signup_zip'] : NA) ?? NA; ?> </li>
                        <li><strong>State: </strong><?= $order['order_state'] ? $order['order_state'] : (isset($order['signup_state']) && $order['signup_state'] ? $order['signup_state'] : NA); ?> </li>
                        <li><strong>Country: </strong><?= $order['order_country'] ?? NA; ?> </li>
                    </ul>
                </div>

                <div class="col-md-<?= $column ?>">
                    <span><strong>Payment Information:</strong></span>
                    <ul class="list-unstyled">
                        <li><strong>Status: </strong> <?= $this->model_order->get_payment_status($order['order_payment_status']); ?></li>
                        <li><strong>Currency: </strong> <?= $order['order_currency']; ?></li>
                        <li><strong>Subtotal: </strong> <?= price($order['order_amount']); ?></li>
                        <li><strong>Service fee (<?= $order['order_fee'] > 0 ? number_format(($order['order_fee'] / $order['order_amount']) * 100, 0) : 0 ?>%): </strong> <?= price($order['order_fee']); ?></li>
                        <li><strong>Total: </strong><?= $order['order_currency'] . ' ' . price_without_sign($order['order_total']) ?></li>
                    </ul>
                </div>
                <?php if ($order['order_reference_type'] == ORDER_REFERENCE_MEMBERSHIP) : ?>
                    <div class="col-md-<?= $column ?>">
                        <span><strong>Payment Method:</strong></span>
                        <ul class="list-unstyled">
                            <?php if (isset($payment_method) && $payment_method && $payment_method->id) : ?>
                                <li><strong>Type: </strong><?php echo ucfirst($payment_method->type); ?></li>
                                <li><strong>Card: </strong><?php echo ucfirst($payment_method->card->brand); ?></li>
                                <li><strong>Number: </strong><?php echo '**** **** ****' . ' ' . $payment_method->card->last4; ?></li>
                                <li><strong>Expiry: </strong><?php echo $payment_method->card->exp_month . ' / ' . $payment_method->card->exp_year; ?></li>
                            <?php else : ?>
                                <?php echo NA; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>

            <hr />

            <div class="row">
                <div class="col-md-12">
                    <p>Order type:
                        <?php
                        switch ($order['order_reference_type']) {
                            case ORDER_REFERENCE_MEMBERSHIP:
                                echo 'Subscription';
                                break;
                            case ORDER_REFERENCE_PRODUCT:
                                echo 'Product';
                                break;
                            case ORDER_REFERENCE_TECHNOLOGY:
                                echo 'Technology';
                                break;
                            case ORDER_REFERENCE_JOB:
                                echo 'Job';
                                break;
                        }
                        ?>
                    </p>
                    <p><strong>Item details:</strong></p>
                </div>
                <div class="col-md-12 col-md-12">
                    <?php $this->load->view('widgets/order/detail.php'); ?>
                </div>
            </div>

            <hr />

            <?php $this->load->view('widgets/order/summary.php'); ?>

            <?php $this->load->view('widgets/order/question.php'); ?>

        </div>
    </div>

</div>