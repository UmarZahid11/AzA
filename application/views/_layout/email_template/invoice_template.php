<body style="word-break: break-word;font: 15px/25px 'Montserrat', sans-serif;color: #393939;overflow-x: hidden;">
    <section class="invoice_main all-section" style="display: block;">
        <div class="container">
            <div class="row">
                <div class="centerCol col-lg-7 col-xl-6 col-md-10 col-sm-10 col-12" style="float: none;margin: 0 auto;">
                    <div class="invoice_template">
                        <div class="invoice_card" style="padding: 30px;background: #fff;box-shadow: 0 0 20px #00000021;border-radius: 30px;margin-bottom: 30px;">
                            <p style="font-family: Avenir;font-size: 15px;color: #000;margin-bottom: 0;">Recipt From <?= $title ?></p>
                            <h2 style="line-height: 1;margin: 0 0 10px;font-size: 55px;color: #80060a;font-weight: 700;margin-bottom: 0;"><?= $order['order_total'] ?></h2>
                            <p style="font-family: Avenir;font-size: 15px;color: #000;margin-bottom: 0;"><?= $order['order_payment_status'] ? 'Paid' : 'Unpaid'; ?> <?= date("M d, Y", strtotime($order['order_createdon'])) ?></p>
                            <hr>

                            <ul class="recipt_detail" style="margin: 0 0 20px;padding: 0;list-style-type: none;">
                                <li style="display: flex;align-items: center;justify-content: space-between;"><span>Customer Id: &nbsp;</span> <span><?//= $signup_customer_response->id ?></span></li>
                                <li style="display: flex;align-items: center;justify-content: space-between;"><span>Customer Name: &nbsp;</span> <span><strong><?//= $order_stripe_response->customer_details->name ?></strong></span></li>
                                <li style="display: flex;align-items: center;justify-content: space-between;"><span>Invoice Prefix: &nbsp;</span> <span><?//= $signup_customer_response->invoice_prefix ?></span></li>
                            </ul>
                        </div>

                        <?php $this->load->view('widgets/order/detail.php'); ?>

                        <div class="invoice_card" style="padding: 30px;background: #fff;box-shadow: 0 0 20px #00000021;border-radius: 30px;margin-bottom: 30px;">
                            <p style="font-family: Avenir;font-size: 15px;color: #000;margin-bottom: 0;">Recipt #<?= $order['order_id'] ?></p>

                            <ul class="recipt_detail recipt_detail2" style="margin: 0 0 20px;padding: 0;list-style-type: none;">
                                <li style="display: flex;align-items: center;justify-content: space-between;">
                                    <p><?//= date("M d", $signup_customer_response->subscriptions->data[0]->current_period_start) ?> - <?//= date("M d, Y", $signup_customer_response->subscriptions->data[0]->current_period_end) ?></p>
                                </li>
                                <li style="display: flex;align-items: center;justify-content: space-between;margin-bottom: 10px;border-bottom: 1px solid #878787;">
                                    <span class="frst" style="font-weight: 700;color: #000000;">Package: &nbsp;</span>
                                    <span class="frst" style="font-weight: 700;color: #000000;"><?//= $order['membership_title'] ?>
                                    </span>
                                </li>
                                <li style="display: flex;align-items: center;justify-content: space-between;margin-bottom: 10px;border-bottom: 1px solid #878787;">
                                    <span class="frst" style="font-weight: 700;color: #000000;">Qty: &nbsp;</span>
                                    <span style="font-weight: 700;color: #000000;" class="frst"><?= $order['order_quantity'] ?></span>
                                </li>
                                <li style="display: flex;align-items: center;justify-content: space-between;margin-bottom: 10px;border-bottom: 1px solid #878787;">
                                    <span style="font-weight: 700;color: #000000;" class="frst">Total: &nbsp;</span>
                                    <span style="font-weight: 700;color: #000000;" class="frst"><?= $order['order_total'] ?></span>
                                </li>
                            </ul>
                            <p style="font-size: 15px;color: #686767;margin-bottom: 0;" class="questions">Questions? Contact Us at <a href="" style="text-decoration: none;color: #80060a;white-space: initial;"><?//= $contact_email ?></a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>