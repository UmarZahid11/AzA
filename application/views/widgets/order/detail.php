<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th class="font-12">
                Name
            </th>
            <?php if (in_array($order['order_reference_type'], [ORDER_REFERENCE_PRODUCT, ORDER_REFERENCE_TECHNOLOGY])) : ?>
                <th class="font-12">
                    Qty
                </th>
            <?php endif; ?>
            <?php if (in_array($order['order_reference_type'], [ORDER_REFERENCE_PRODUCT, ORDER_REFERENCE_TECHNOLOGY])) : ?>
                <th class="font-12">
                    Cost
                </th>
            <?php endif; ?>
            <?php if (in_array($order['order_reference_type'], [ORDER_REFERENCE_JOB, ORDER_REFERENCE_TECHNOLOGY_LISTING])) : ?>
                <th class="font-12">
                    Interval
                </th>
            <?php endif; ?>
            <th class="font-12">
                Total
            </th>
            <th class="font-12">
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
                }
                ?>
            </th>
            <?php if (in_array($order['order_reference_type'], [ORDER_REFERENCE_MEMBERSHIP, ORDER_REFERENCE_JOB])) : ?>
                <th class="font-12">
                    ..
                </th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($order_items as $key => $value) :
            echo '<tr class="font-12">';
            switch ($order['order_reference_type']) {
                case ORDER_REFERENCE_MEMBERSHIP:
                    $item_detail = $this->model_membership->find_by_pk($value['order_item_product_id']);
                    if ($item_detail) {
                        $param = array();
                        $param['where']['membership_pivot_attribute_id'] = COST_ATTRIBUTE;
                        $param['where']['membership_pivot_membership_id'] = $item_detail['membership_id'];
                        $membership_pivot = $this->model_membership_pivot->find_one($param);
                        $cost = (!empty($membership_pivot) && intval($membership_pivot['membership_pivot_value'])) ? $membership_pivot['membership_pivot_value'] : 0;
                    }
                    echo '<td>';
                    echo isset($item_detail['membership_title']) ? $item_detail['membership_title'] : '';
                    echo '</td>';
                    echo '<td>';
                    echo price($value['order_item_price']);
                    echo '</td>';
                    echo '<td>';
                    echo isset($order['order_payment_status']) ? $this->model_membership->subscriptionStatusString($order['order_payment_status']) : NA;
                    echo '</td>';
                    echo '<td>';
                    echo (isset($order['signup_subscription_current_period_start']) && $order['signup_subscription_current_period_start'] ? date('d M, Y H:i a', strtotime($order['signup_subscription_current_period_start'])) : '');
                    echo ' - ' . (isset($order['signup_subscription_current_period_end']) && $order['signup_subscription_current_period_end'] ? date('d M, Y H:i a', strtotime($order['signup_subscription_current_period_end'])) : '');
                    echo '</td>';
                    break;
                case ORDER_REFERENCE_TECHNOLOGY:
                case ORDER_REFERENCE_PRODUCT:
                    $item_detail = $this->model_product->find_by_pk($value['order_item_product_id']);
                    switch ($item_detail['product_reference_type']) {
                        case PRODUCT_REFERENCE_PRODUCT:
                        case PRODUCT_REFERENCE_TECHNOLOGY:
                            $product_cost = $item_detail['product_cost'];
                            break;
                        case PRODUCT_REFERENCE_SERVICE:
                            $product_request = $this->model_product_request->find_by_pk($value['order_item_product_request_id']);
                            if ($product_request['product_request_proposed_fee']) {
                                $product_cost = $product_request['product_request_proposed_fee'];
                            } else {
                                $product_cost = $item_detail['product_cost'];
                            }
                            break;
                    }
                    echo '<td>';
                    echo isset($item_detail['product_name']) ? $item_detail['product_name'] : '';
                    echo '</td>';
                    echo '<td>';
                    echo $value['order_item_qty'];
                    echo '</td>';
                    echo '<td>';
                    echo price($product_cost);
                    echo '</td>';
                    echo '<td>';
                    echo price($value['order_item_qty'] * $product_cost);
                    echo '</td>';
                    echo '<td>';
                    echo $value['order_item_stripe_transfer_status'] ? 'Transfer completed' : 'Transfer pending';
                    echo '</td>';
                    break;
                case ORDER_REFERENCE_TECHNOLOGY:
                    break;
                case ORDER_REFERENCE_SERVICE:
                    break;
                case ORDER_REFERENCE_JOB:
                    $item_detail = $this->model_job->find_by_pk($value['order_item_product_id']);
                    echo '<td>';
                    echo isset($item_detail['job_title']) ? $item_detail['job_title'] : '';
                    echo '</td>';
                    echo '<td>';
                    echo ($value['order_item_qty']) . ' ' . ($value['order_item_qty_interval']);
                    echo '</td>';
                    echo '<td>';
                    echo price($value['order_item_price']);
                    echo '</td>';
                    echo '<td>';
                    echo isset($order['order_payment_status']) ? $this->model_membership->subscriptionStatusString($order['order_payment_status']) : NA;
                    echo '</td>';
                    echo '<td>';
                    echo (isset($item_detail['job_subscription_current_period_start']) && $item_detail['job_subscription_current_period_start'] ? date('d M, Y H:i a', strtotime($item_detail['job_subscription_current_period_start'])) : '');
                    echo ' - ' . (isset($item_detail['job_subscription_current_period_end']) && $item_detail['job_subscription_current_period_end'] ? date('d M, Y H:i a', strtotime($item_detail['job_subscription_current_period_end'])) : '');
                    echo '</td>';
                    break;
                case ORDER_REFERENCE_TECHNOLOGY_LISTING:
                    $item_detail = $this->model_product->find_by_pk($value['order_item_product_id']);
                    echo '<td>';
                    echo isset($item_detail['product_name']) ? $item_detail['product_name'] : '';
                    echo '</td>';
                    echo '<td>';
                    echo ($value['order_item_qty']) . ' ' . ($value['order_item_qty_interval']);
                    echo '</td>';
                    echo '<td>';
                    echo price($value['order_item_price']);
                    echo '</td>';
                    echo '<td>';
                    echo isset($order['order_payment_status']) ? $this->model_membership->subscriptionStatusString($order['order_payment_status']) : NA;
                    echo '</td>';
                    echo '<td>';
                    echo (isset($item_detail['product_subscription_current_period_start']) && $item_detail['product_subscription_current_period_start'] ? date('d M, Y H:i a', strtotime($item_detail['product_subscription_current_period_start'])) : '');
                    echo ' - ' . (isset($item_detail['product_subscription_current_period_end']) && $item_detail['product_subscription_current_period_end'] ? date('d M, Y H:i a', strtotime($item_detail['product_subscription_current_period_end'])) : '');
                    echo '</td>';
                    break;
            }
            echo '</tr>';
        ?>
        <?php endforeach; ?>
    </tbody>
</table>