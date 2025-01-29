<?php global $config; ?>

<div class="inner-page-header">
    <h1><?= humanize($class_name) ?> <small>Details</small></h1>
</div>

<?php
switch ($order['order_reference_type']) {
    case ORDER_REFERENCE_MEMBERSHIP:
        $column = '3';
        break;
    case ORDER_REFERENCE_PRODUCT:
        $column = '4';
        break;
    default:
        $column = '4';
}
?>

<div class="portlet box green">
    <div class="portlet-title">
        <div class="caption">
            <i class="fa fa-shopping-cart"></i>
            <strong>Order #<?= $order['order_id'] ?> </strong>
            <small> / <?= date("Y-m-d h:i a", strtotime($order['order_createdon'])) ?></small>
        </div>
        <div class="tools">
            <a onclick="print_div();" class="label label-white"><i class="fa fa-print"></i>
            </a>
        </div>
    </div>

    <div class="portlet-body form">

        <div class="invoice container" id="invoice" style="padding: 20px;">
            <div class="row invoice-logo">
                <div class="col-xs-6 invoice-logo-space">
                    <img style="height: 100px;" src="<?= get_image($this->layout_data['logo'][0]['logo_image_path'], $this->layout_data['logo'][0]['logo_image']) ?>" alt="logo" class="main-tem-logo" />
                </div>
                <div class="col-xs-3">
                </div>
                <div class="col-xs-3">
                    <?php echo date("M d, Y h:i a", strtotime($order['order_createdon'])) ?>
                </div>

            </div>

            <hr />

            <div class="row">
                <div class="col-xs-<?= $column ?>">
                    <span><strong>Billing Information:</strong></span>
                    <ul class="list-unstyled">
                        <li><strong>First name: </strong><?= $order['order_firstname'] ?? NA; ?> </li>
                        <li><strong>Last name: </strong><?= $order['order_lastname'] ?? NA; ?></li>
                        <li><strong>Email: </strong><a href="mailto:<?= $order['order_email']; ?>"><?= $order['order_email']; ?></a> </li>
                        <li><strong>Phone: </strong><a href="<?= $order['order_phone'] ? 'tel:' . $order['order_phone'] : 'javascript:;'; ?>"><?= $order['order_phone'] ?? NA; ?> </a></li>
                    </ul>
                </div>

                <div class="col-xs-<?= $column ?>">
                    <span><strong>Address Information:</strong></span>
                    <ul class="list-unstyled">
                        <li><strong>Address: </strong><?= $order['order_address1'] ?? NA; ?> </li>
                        <li><strong>City: </strong><?= $order['order_city'] ?? NA; ?> </li>
                        <li><strong>Zip: </strong><?= $order['order_zip'] ?? NA; ?> </li>
                        <li><strong>State: </strong><?= $order['order_state'] ? $order['order_state'] : NA; ?> </li>
                        <li><strong>Country: </strong><?= $order['order_country'] ?? NA; ?> </li>
                    </ul>
                </div>

                <div class="col-xs-<?= $column ?>">
                    <span><strong>Payment Information:</strong></span>

                    <ul class="list-unstyled">
                        <li><strong>Status: </strong> <?= $this->model_order->get_payment_status($order['order_payment_status']); ?></li>
                        <li><strong>Currency: </strong> <?= $order['order_currency']; ?></li>
                        <li><strong>Subtotal: </strong> <?= price($order['order_amount']); ?></li>
                        <li><strong>Service fee (<?= $order['order_fee'] > 0 ? number_format(($order['order_fee'] / $order['order_amount']) * 100, 0) : 0 ?>%): </strong> <?= price($order['order_fee']); ?></li>
                        <li><strong>Total amount: </strong><?= $order['order_currency'] . ' ' . price_without_sign($order['order_total']) ?></li>

                        <?php if ($order['order_transaction_id']) : ?>
                            <li><strong>Stripe charge ID: </strong>
                                <span style="word-wrap: break-word;"><?= $order['order_transaction_id'] ?? NA; ?></span>
                            </li>
                        <?php endif; ?>
                        <li>
                            <button type="button" class="btn" data-toggle="modal" data-target="#responseModal">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>
                            <div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="responseModalLabel"><?= $order['order_transaction_id'] ? 'Charge response' : ($order['order_session_checkout_id'] ? 'Checkout session response' : 'Response'); ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <?//= $order['order_response'] ? '<pre>' . $order['order_response'] . '</pre>' : NA ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <?php if ($order['order_reference_type'] == ORDER_REFERENCE_MEMBERSHIP) : ?>
                    <div class="col-xs-<?= $column ?>">
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

            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h4><strong>Item details:</strong></h4>
                    </div>
                    <div class="col-md-12 col-xs-12">
                        <?php $this->load->view('widgets/order/detail.php'); ?>
                    </div>
                </div>
            </div>

            <?php foreach ($order_items as $key => $value) : ?>
                <div class="modal fade" id="trasnferResponseModal<?= $key ?>" tabindex="-1" role="dialog" aria-labelledby="trasnferResponseModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="trasnferResponseModalLabel">Transfer response</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?//= $value['order_item_stripe_transfer_response'] ? '<pre>' . $value['order_item_stripe_transfer_response'] . '</pre>' : '' ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>


            <hr />

            <?php $this->load->view('widgets/order/summary.php'); ?>

            <?php $this->load->view('widgets/order/question.php'); ?>

        </div>
    </div>
</div>

<? //create_modal_html("address_update", "", "", 'method="POST" action="' . $config['base_url'] . 'admin/order/save_address"', false) ?>

<script>
    function print_div() {
        var printContents = document.getElementById('invoice').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents
    }
</script>