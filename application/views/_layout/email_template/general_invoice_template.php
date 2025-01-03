<style type="text/css">
    html { background: #999; cursor: default; }
body { box-sizing: border-box; margin: 0 auto; overflow: hidden; padding: 0.5in; width: 8.5in; } 
body { background: #FFF; border-radius: 1px; box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5); }
    .body {
        font-family: "Raleway", sans-serif;
        font-size: 14px;
        font-weight: 400;
        line-height: 18px;
        color: #000;
    }
</style>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
    <link rel="shortcut icon" type="image/png" href="<?= get_image($logo_data['logo_image_path'], $logo_data['logo_favicon']); ?>">
</head>

<body class="body">
    <?php if ($data['test_mode']) : ?>
        <?php echo '<center><h3>TEST MODE</h3></center>'; ?>
    <?php endif; ?>

    <table width="622" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#f3f4f8; border:#d8d8d8 1px solid; padding: 20px;">
        <tr>
            <td>
                <table width="622" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr> </tr>
                </table>
            </td>
        </tr>
        <tr align="center" style="font-size:18px; font-family:Tahoma, Geneva, sans-serif;">
            <td>
                <h5 class="header" style="background: #8204aa; border-radius: 0.25em; color: #FFF; margin: 0 0 1em; padding: 0.5em 0; text-transform: uppercase;     font-weight: 100;">Invoice</h5>
            </td>
        </tr>
        <tr>
            <td>
                <center>
                    <img src="<?= $data['logo'] ?>" style='padding: 15px;width:25%; ' />
                </center>
            </td>
        </tr>
        <tr>
            <td height="25"></td>
        </tr>
        <tr>
            <td bgcolor="#f5f9f6" style="font-family:Arial, Helvetica, sans-serif;">
                <table width="622" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="padding:8px 15px;">
                            <table style="margin: 0 0 1em; font-size: 14px;">
                                <tr>
                                    <td>
                                        <p><?= ucfirst($data['order_name']); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><?= isset($order) && $order ? ucfirst($order['order_address1']) : 'Unkown'; ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><?= isset($order) && $order ? ucfirst($order['order_phone']) : 'Unkown'; ?></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <table style="float: right; width: 40%; margin: 0 0 1em; text-align: left; font-size: 14px;">
                        <tr>
                            <th><span>Invoice #</span></th>
                            <td><span><?= $data['order_no'] ?></span></td>
                        </tr>
                        <tr>
                            <th><span>Date</span></th>
                            <td><span><?= isset($order) && $order ? date('d-M-Y', strtotime($order['order_createdon'])) : date('d-M-Y'); ?></span></td>
                        </tr>
                        <tr>
                            <th><span>Total</span></th>
                            <td><span><?= isset($order) && $order ? price($order['order_total']) : price(0); ?></span></td>
                        </tr>
                        <tr>
                            <th><span>Status</span></th>
                            <td><span><?= isset($order) && $order ? ucfirst($order['order_status_message']) : 'Unkown'; ?></span></td>
                        </tr>
                    </table>

                    <tr>
                        <td height="25">
                            <hr />
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table class="inventory" style="clear: both; width: 100%;">
                                <thead>
                                    <tr style="text-transform: uppercase;">
                                        <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Name</span></th>
                                        <?php if (isset($order) && $order && in_array($order['order_reference_type'], [ORDER_REFERENCE_PRODUCT, ORDER_REFERENCE_TECHNOLOGY])) : ?>
                                            <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Cost</span></th>
                                            <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Quantity</span></th>
                                        <?php endif; ?>
                                        <?php if (isset($order) && $order && in_array($order['order_reference_type'], [ORDER_REFERENCE_JOB, ORDER_REFERENCE_TECHNOLOGY_LISTING])) : ?>
                                            <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Interval</span></th>
                                        <?php endif; ?>
                                        <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Total</span></th>
                                        <?php if (isset($order) && $order) : ?>
                                            <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;">
                                                <span>
                                                    <?php
                                                        switch ($order['order_reference_type']) {
                                                            case ORDER_REFERENCE_MEMBERSHIP:
                                                                echo 'Membership status';
                                                                break;
                                                            case ORDER_REFERENCE_PRODUCT:
                                                                echo 'Transfer status';
                                                                break;
                                                            case ORDER_REFERENCE_JOB:
                                                                echo 'Job listing subscription status';
                                                                break;
                                                            case ORDER_REFERENCE_TECHNOLOGY_LISTING:
                                                                echo 'Technology listing subscription status';
                                                                break;
                                                            case ORDER_REFERENCE_COACHING:
                                                                echo 'Coaching application status';
                                                                break;
                                                        }
                                                    ?>
                                                </span>
                                            </th>
                                        <?php endif; ?>
                                        <?php if (isset($order) && $order && in_array($order['order_reference_type'], [ORDER_REFERENCE_MEMBERSHIP, ORDER_REFERENCE_JOB, ORDER_REFERENCE_TECHNOLOGY_LISTING])) : ?>
                                            <th style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;">
                                                ..
                                            </th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <?php if(isset($order_items) && $order_items) : ?>
                                    <tbody>
                                        <?php foreach($order_items as $order_item) : ?>
                                            <tr>
                                                <?php
                                                    switch ($order['order_reference_type']) {
                                                        case ORDER_REFERENCE_MEMBERSHIP:
                                                            $item_detail = $this->model_membership->find_by_pk($order_item['order_item_product_id']);
                                                                echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>Front End Consultation</span></td>';
                                                                echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span data-prefix>$</span><span>150.00</span></td>';
                                                                echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>4</span></td>';
                                                                echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span data-prefix>$</span><span>600.00</span></td>';
                                                            break;
                                                        case ORDER_REFERENCE_TECHNOLOGY:
                                                        case ORDER_REFERENCE_PRODUCT:
                                                            $product_cost = 0;
                                                            $item_detail = $this->model_product->find_by_pk($order_item['order_item_product_id']);
                                                            switch ($item_detail['product_reference_type']) {
                                                                case PRODUCT_REFERENCE_PRODUCT:
                                                                case PRODUCT_REFERENCE_TECHNOLOGY:
                                                                    $product_cost = $item_detail['product_cost'];
                                                                    break;
                                                                case PRODUCT_REFERENCE_SERVICE:
                                                                    $product_request = $this->model_product_request->find_by_pk($order_item['order_item_product_request_id']);
                                                                    if ($product_request['product_request_proposed_fee']) {
                                                                        $product_cost = $product_request['product_request_proposed_fee'];
                                                                    } else {
                                                                        $product_cost = $item_detail['product_cost'];
                                                                    }
                                                                    break;
                                                            }
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($item_detail['product_name']) ? $item_detail['product_name'] : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . ($order_item['order_item_qty']) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (price($product_cost)) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (price($order_item['order_item_qty'] * $product_cost)) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . ($order_item['order_item_stripe_transfer_status'] ? 'Transfer completed' : 'Transfer pending') . '</span></td>';
                                                            break;
                                                        case ORDER_REFERENCE_TECHNOLOGY:
                                                            break;
                                                        case ORDER_REFERENCE_SERVICE:
                                                            break;
                                                        case ORDER_REFERENCE_JOB:
                                                            $item_detail = $this->model_job->find_by_pk($order_item['order_item_product_id']);
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($item_detail['job_title']) ? $item_detail['job_title'] : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . ($order_item['order_item_qty']) . ' ' . ($order_item['order_item_qty_interval']) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (price($order_item['order_item_price'])) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($order['order_payment_status']) ? $this->model_membership->subscriptionStatusString($order['order_payment_status']) : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>';
                                                            echo (isset($item_detail['job_subscription_current_period_start']) && $item_detail['job_subscription_current_period_start'] ? date('d M, Y H:i a', strtotime($item_detail['job_subscription_current_period_start'])) : '');
                                                            echo ' - ' . (isset($item_detail['job_subscription_current_period_end']) && $item_detail['job_subscription_current_period_end'] ? date('d M, Y H:i a', strtotime($item_detail['job_subscription_current_period_end'])) : '');
                                                            echo '</span></td>';
                                                            break;
                                                        case ORDER_REFERENCE_TECHNOLOGY_LISTING:
                                                            $item_detail = $this->model_product->find_by_pk($order_item['order_item_product_id']);
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($item_detail['product_name']) ? $item_detail['product_name'] : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . ($order_item['order_item_qty']) . ' ' . ($order_item['order_item_qty_interval']) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (price($order_item['order_item_price'])) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($order['order_payment_status']) ? $this->model_membership->subscriptionStatusString($order['order_payment_status']) : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>';
                                                            echo (isset($item_detail['product_subscription_current_period_start']) && $item_detail['product_subscription_current_period_start'] ? date('d M, Y H:i a', strtotime($item_detail['product_subscription_current_period_start'])) : '');
                                                            echo ' - ' . (isset($item_detail['product_subscription_current_period_end']) && $item_detail['product_subscription_current_period_end'] ? date('d M, Y H:i a', strtotime($item_detail['product_subscription_current_period_end'])) : '');
                                                            echo '</span></td>';
                                                            break;
                                                        case ORDER_REFERENCE_COACHING:
                                                            $item_detail_reference = '';
                                                            $item_detail = $this->model_coaching_application->find_by_pk($order_item['order_item_product_id']);
                                                            if($item_detail) {
                                                                $item_detail_reference = $this->model_coaching->find_by_pk($item_detail['coaching_application_coaching_id']);
                                                            }
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($item_detail_reference['coaching_title']) ? $item_detail_reference['coaching_title'] : NA) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . price($order_item['order_item_price']) . '</span></td>';
                                                            echo '<td style="border: 1px solid #DDD;border-width: 1px; padding: 0.5em;position: relative; text-align: left; font-size: 14px;"><span>' . (isset($order['order_payment_status']) ? ucfirst($this->model_membership->subscriptionStatusString($order['order_payment_status'])) : NA) . ' </span></td>';
                                                            break;
                                                    }
                                                    ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                <?php endif; ?>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table style="float: right; width: 40%; margin: 2em 0; text-align: left; font-size: 14px;">
                                <tr>
                                    <th><span>Sub-Total </span></th>
                                    <td><span><?= isset($order) && $order ? price($order['order_amount']) : price(0) ?></span></td>
                                </tr>
                                <tr>
                                    <th><span>Tax </span></th>
                                    <td><span><?= isset($order) && $order ? price($order['order_tax']) : price(0) ?></span></td>
                                </tr>
                                <tr>
                                    <th><span>Total </span></th>
                                    <td><span><?= isset($order) && $order ? price($order['order_total']) : price(0); ?></span></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td height="25">
                            <hr />
                        </td>
                    </tr>

                    <tr>
                        <td style="font-size:13px; line-height:22px; padding:0 15px; margin-bottom:15px; padding-bottom:10px;">
                            <p>Questions? Email: <a href="mailto:<?= g('db.admin.email') ?>"><?= g('db.admin.email') ?></a></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>