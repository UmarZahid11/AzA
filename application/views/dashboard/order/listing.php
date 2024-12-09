<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <?php if (in_array($order_reference, [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_TECHNOLOGY])) : ?>
            <?php switch ($type) {
                case ORDER_UNPAID:
                    echo '<a href="' . l('dashboard/order/listing/' . $order_reference . '/' . ORDER_PAID) . '" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="left" title="View purchased products">Purchased orders</a>';
                    break;
                case ORDER_PAID:
                    echo '<a href="' . l('dashboard/order/listing/' . $order_reference . '/' .  ORDER_UNPAID) . '" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="left" title="View pending product orders">Pending orders</a>';
                    break;
            }
            ?>
        <?php elseif ($order_reference == PRODUCT_REFERENCE_MEMBERSHIP) : ?>
            <a href="<?= l('dashboard/order/invoices/') ?>" class="btn btn-custom" data-toggle="tooltip" data-bs-placement="top" title="View your current subscription invoices.">Subscription invoices</a>
        <?php endif; ?>

    </div>

    <i class="fa fa-box"></i>
    <h4><?= ucfirst($type) . ' ' . __('orders'); ?></h4><small data-toggle="tooltip" data-bs-placement="top" title="Order type">(<?=($order_reference)?>)</small>
    <hr />

    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="offset-4 col-md-2">
            <div class="float-right">
                <span>Filter:</span>
                <span class="dropdown" data-toggle="tooltip" data-bs-placement="top" title="Filter orders by type">
                    <button><?= ucfirst($order_reference) ?></button>
                    <label>
                        <input type="checkbox" />
                        <ul>
                            <li class="<?= $order_reference == PRODUCT_REFERENCE_PRODUCT ? 'active pe-none' : '' ?>"><a href="<?= $order_reference == PRODUCT_REFERENCE_PRODUCT ? 'javascript:;' : l('dashboard/order/listing/' . PRODUCT_REFERENCE_PRODUCT . '/' . ORDER_PAID) ?>" data-toggle="tooltip" data-bs-placement="left" title="View purchased products">
                                    <?= $order_reference == PRODUCT_REFERENCE_PRODUCT ? '<i class="fa fa-circle-check"></i>' : '<i class="fa fa-filter"></i>' ?> <?= ucfirst(PRODUCT_REFERENCE_PRODUCT) ?>
                                </a></li>
                            <li class="<?= $order_reference == PRODUCT_REFERENCE_MEMBERSHIP ? 'active pe-none' : '' ?>"><a href="<?= $order_reference == PRODUCT_REFERENCE_MEMBERSHIP ? 'javascript:;' : l('dashboard/order/listing/' . PRODUCT_REFERENCE_MEMBERSHIP . '/' . ORDER_PAID) ?>" data-toggle="tooltip" data-bs-placement="left" title="View purchased membership">
                                    <?= $order_reference == PRODUCT_REFERENCE_MEMBERSHIP ? '<i class="fa fa-circle-check"></i>' : '<i class="fa fa-filter"></i>' ?> <?= ucfirst(PRODUCT_REFERENCE_MEMBERSHIP) ?>
                                </a></li>
                            <li class="<?= $order_reference == PRODUCT_REFERENCE_JOB ? 'active pe-none' : '' ?>"><a href="<?= $order_reference == PRODUCT_REFERENCE_JOB ? 'javascript:;' : l('dashboard/order/listing/' . PRODUCT_REFERENCE_JOB . '/' . ORDER_PAID) ?>" data-toggle="tooltip" data-bs-placement="left" title="View active/paused job listing subscriptions">
                                    <?= $order_reference == PRODUCT_REFERENCE_JOB ? '<i class="fa fa-circle-check"></i>' : '<i class="fa fa-filter"></i>' ?> <?= ucfirst(PRODUCT_REFERENCE_JOB) ?>
                                </a></li>
                            <li class="<?= $order_reference == PRODUCT_REFERENCE_TECHNOLOGY ? 'active pe-none' : '' ?>"><a href="<?= $order_reference == PRODUCT_REFERENCE_TECHNOLOGY ? 'javascript:;' : l('dashboard/order/listing/' . PRODUCT_REFERENCE_TECHNOLOGY . '/' . ORDER_PAID) ?>" data-toggle="tooltip" data-bs-placement="left" title="View purchased technologies">
                                    <?= $order_reference == PRODUCT_REFERENCE_TECHNOLOGY ? '<i class="fa fa-circle-check"></i>' : '<i class="fa fa-filter"></i>' ?> <?= ucfirst(PRODUCT_REFERENCE_TECHNOLOGY) ?>
                                </a></li>
                        </ul>
                    </label>
                </span>
            </div>
        </div>
    </div>

    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th>
                    <?php switch ($order_reference) {
                        case PRODUCT_REFERENCE_MEMBERSHIP:
                            echo __('Subscription');
                            break;
                        case PRODUCT_REFERENCE_TECHNOLOGY_LISTING:
                        case PRODUCT_REFERENCE_TECHNOLOGY:
                        case PRODUCT_REFERENCE_PRODUCT:
                            echo __('Product');
                            break;
                        case PRODUCT_REFERENCE_JOB:
                            echo __('Job');
                            break;
                    } ?>
                </th>
                <th><?= __('Amount') ?></th>
                <th><?= __('Total') ?></th>
                <th><?= __('Status') ?></th>
                <th><?= __('Date') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($orders) && count($orders) > 0) : ?>
            <tbody>
                <?php foreach ($orders as $key => $value) : ?>
                    <tr>
                        <td>
                            <?php switch ($value['order_reference_type']) {
                                case ORDER_REFERENCE_MEMBERSHIP:
                                    $membership = $this->model_membership->find_by_pk($value['order_reference_id']);
                                    echo '<a href="' . l('membership') . '">' . $membership['membership_title'] . '</a>';
                                    break;
                                case ORDER_REFERENCE_TECHNOLOGY_LISTING:
                                case ORDER_REFERENCE_TECHNOLOGY:
                                case ORDER_REFERENCE_PRODUCT:
                                    $order_item = $this->model_order_item->find_all(array('where' => array('order_item_order_id' => $value['order_id'])));
                                    foreach ($order_item as $key => $item) {
                                        $product = $this->model_product->find_by_pk($item['order_item_product_id']);
                                        if($product) {
                                            echo '<a href="' . l('dashboard/product/detail/' . $product['product_slug']) . '">';
                                            echo $product['product_name'] . (array_key_last($order_item) == $key ? '' : ',');
                                            echo '</a>';
                                        } else {
                                            echo 'Not available anymore.';
                                        }
                                    }
                                    break;
                                case ORDER_REFERENCE_JOB:
                                    $job = $this->model_job->find_by_pk($value['order_reference_id']);
                                    if($job) {
                                        echo '<a href="' . l('dashboard/job/detail/') . $job['job_slug'] . '">' . $job['job_title'] . '</a>';
                                    }
                                    break;
                            }
                            ?>
                        </td>
                        <td><?= price($value['order_amount']) ?></td>
                        <td><?= price($value['order_total']) ?></td>
                        <td><?php echo $this->model_order->get_payment_status($value['order_payment_status']); ?></td>
                        <td><?= validateDate($value['order_createdon'], 'Y-m-d H:i:s') ? date('d M, Y h:i a', strtotime($value['order_createdon'])) : NA ?></td>
                        <td>
                            <?php switch ($type) {
                                case ORDER_UNPAID:
                                    echo '<a href="' . l('dashboard/order/checkout/' . JWT::encode($value['order_id'])) . '" class="btn btn-custom">Pay</a>';
                                    break;
                            }
                            ?>
                            <a href="<?= l('dashboard/order/detail/' . JWT::encode($value['order_id'])) ?>" class="btn btn-custom">Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small>
                    <?php switch ($order_reference) {
                        case PRODUCT_REFERENCE_MEMBERSHIP:
                            echo __('No previous subscription(s) found.');
                            break;
                        case PRODUCT_REFERENCE_PRODUCT:
                            echo __('No ' . $type . ' orders available');
                            break;
                        case PRODUCT_REFERENCE_JOB:
                            echo __('No active/paused job listing subscription found.');
                            break;
                        default:
                            echo __('No ' . $type . ' orders found.');
                    } ?>
                </small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($orders_count) && ($orders_count) > 0) : ?>
    <div class="row mt-4">
        <div class="col-lg-12">

            <nav aria-label="Page navigation example mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/order/listing/') . $order_reference . '/' . $type . '/' . $prev . '/' . $limit;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/order/listing/') . $order_reference . '/' . $type . '/' . $i . '/' . $limit; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/order/listing/') . $order_reference . '/' . $type . '/' . $next . '/' . $limit;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>