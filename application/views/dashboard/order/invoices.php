<div class="dashboard-content posted-theme">
    <i class="fa fa-file-invoice"></i>
    <h4><?= __('Invoices'); ?></h4>
    <hr />

    <table class="style-1">
        <thead>
            <tr>
                <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                    <th><?= __('Id') ?></th>
                <?php endif; ?>
                <th><?= __('Number') ?></th>
                <th><?= __('Total') ?></th>
                <th><?= __('Status') ?></th>
                <th>Merchant</th>
                <th><?= __('Date') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if($type == INVOICE_SUBSCRIPTION) : ?>
                <?php if (isset($invoices) && $invoices && $invoices->data && is_array($invoices->data)) : ?>
                    <?php foreach ($invoices->data as $key => $invoice) : ?>
                        <tr>
                            <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                                <td><?= $invoice->id ?></td>
                            <?php endif; ?>
                            <td><?= $invoice->number ?></td>
                            <td><?= ($invoice) && is_int($invoice->total) > 0 ? price($invoice->total / 100) : $invoice->total ?></td>
                            <td><?= $invoice->status ?></td>
                            <td><?= date('d M, Y h:i a', ($invoice->created)) ?></td>
                            <td>
                                <a href="<?= $invoice->hosted_invoice_url ?>" target="_blank" data-toggle="tooltip" data-bs-placement="top" title="View invoice"><i class="fa fa-link"></i></a>&nbsp;|&nbsp;
                                <a href="<?= $invoice->invoice_pdf ?>" target="_blank" data-toggle="tooltip" data-bs-placement="top" title="View invoice pdf"><i class="fa fa-file-pdf-o"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td>No data available.</td>
                    </tr>
                <?php endif; ?>
            <?php elseif(in_array($type, [INVOICE_PRODUCT, INVOICE_SERVICE, INVOICE_SERVICE_PROVIDED, INVOICE_JOB, INVOICE_TECHNOLOGY])) : ?>
                <?php if (isset($invoices) && $invoices): ?>
                    <?php foreach($invoices as $key => $invoice): ?>
                        <?php
                            $decoded_response = json_decode($invoice['order_response']);
                            $amount = 0;
                            $status = 'Failed';
                            $created = '';
                            $receipt_url = '';
                        ?>
                        <tr>
                            <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                                <td><?= $invoice['order_id'] ?></td>
                            <?php endif; ?>
                            <td><?= str_replace('%0', $invoice['order_id'], ORDER_NO_MASK) ?></td>

                            <?php if($decoded_response && property_exists($decoded_response, 'amount')) : ?>
                                <?php $amount = $decoded_response->amount; ?>
                            <?php elseif($decoded_response && isset($decoded_response->plan->amount)) : ?>
                                <?php $amount = $decoded_response->plan->amount; ?>
                            <?php else: ?>
                                <?php $amount = $invoice['order_total']; ?>
                            <?php endif; ?>

                            <td><?= is_int($amount) && $amount > 0 ? price($amount / 100) : price($amount) ?></td>

                            <?php if($decoded_response && property_exists($decoded_response, 'status')) : ?>
                                <?php $status = $decoded_response->status; ?>
                            <?php endif; ?>
                            <td><?= ucfirst($status) ?></td>

                            <td><?= $invoice['order_merchant']; ?></td>

                            <?php if($decoded_response && property_exists($decoded_response, 'created')) : ?>
                                <?php $created = $decoded_response->created; ?>
                            <?php endif; ?>
                            <td><?= $created ? date('d M, Y h:i a', ($created)) : 'Not available.' ?></td>

                            <?php if($decoded_response && property_exists($decoded_response, 'receipt_url')) : ?>
                                <?php $receipt_url = $decoded_response->receipt_url; ?>
                            <?php else: ?>
                                <?php if(($decoded_response && property_exists($decoded_response, 'latest_invoice'))) : ?>
                                    <?php 
                                        try {
                                            $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                                            $invoice = $stripe->invoices->retrieve(
                                                $decoded_response->latest_invoice,
                                                []
                                            );
                                            if($invoice && isset($invoice->hosted_invoice_url)) {
                                                $receipt_url = $invoice->hosted_invoice_url;
                                            }
                                        } catch(\Exception $e) { log_message('ERROR', $e->getMessage()); }
                                    ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td>
                                <?php if($receipt_url): ?>
                                    <a href="<?= $receipt_url ?>" target="_blank" data-toggle="tooltip" data-bs-placement="top" title="View receipt"><i class="fa fa-file-pdf-o"></i></a>
                                <?php else: ?>
                                    The receipt is not available.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>No data available.</td>
                    </tr>
                <?php endif; ?>
            <?php elseif($type == INVOICE_COACHING) : ?>
                <?php if (isset($invoices) && $invoices): ?>
                    <?php foreach($invoices as $key => $invoice): ?>
                        <?php
                            $decoded_response = '';
                            $amount = 0;
                            $status = 'Failed';
                            $created = '';
                            $receipt_url = '';
                            if($invoice['order_merchant'] == STRIPE) {
                                $stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
                                $payment_intent = $stripe->paymentIntents->retrieve($invoice['order_transaction_id']);
                                if($payment_intent && ($payment_intent->charges || $payment_intent->latest_charge)) {
                                    if($payment_intent->charges) {
                                        $decoded_response = $payment_intent->charges->data[0];
                                    }
                                    if($payment_intent->latest_charge) {
                                        $decoded_response = $stripe->charges->retrieve($payment_intent->latest_charge);
                                    }
                                }
                            }

                            if($invoice['order_merchant'] == PAYPAL) {
                                $url = PAYPAL_URL . PAYPAL_CHECKOUT_URL . '/' . $invoice['order_session_checkout_id'];
                                $headers = array();
                                $headers[] = 'Content-Type: application/json';
                                $headers[] = 'Authorization: Bearer ' . $paypalAccessToken;
            
                                $response = curlRequest($url, $headers);
                                $decoded_response = json_decode($response);
                            }
                        ?>
                        <tr>
                            <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                                <td><?= $invoice['order_id'] ?></td>
                            <?php endif; ?>
                            <td><?= str_replace('%0', $invoice['order_id'], ORDER_NO_MASK) ?></td>

                            <?php if($decoded_response && isset($decoded_response->amount)) : ?>
                                <?php $amount = $decoded_response->amount; ?>
                            <?php elseif($decoded_response && isset($decoded_response->plan->amount)) : ?>
                                <?php $amount = $decoded_response->plan->amount; ?>
                            <?php elseif($decoded_response && isset($decoded_response->purchase_units) && $decoded_response->purchase_units[0]->amount->value) : ?>
                                <?php $amount = $decoded_response->purchase_units[0]->amount->value; ?>
                            <?php else: ?>
                                <?php $amount = $invoice['order_total']; ?>
                            <?php endif; ?>

                            <td><?= is_int($amount) && $amount > 0 ? price($amount / 100) : price($amount) ?></td>

                            <?php if($decoded_response && isset($decoded_response->status)) : ?>
                                <?php $status = $decoded_response->status; ?>
                            <?php else: ?>
                                <?php $status = $invoice['order_status_message']; ?>
                            <?php endif; ?>
                            <td><?= ucfirst($status) ?></td>

                            <td><?= $invoice['order_merchant']; ?></td>

                            <?php if($decoded_response && isset($decoded_response->created)) : ?>
                                <?php $created = $decoded_response->created; ?>
                            <?php elseif($decoded_response && isset($decoded_response->create_time)) : ?>
                                <?php $created = strtotime($decoded_response->create_time); ?>
                            <?php else: ?>
                                <?php $created = strtotime($invoice['order_createdon']); ?>
                            <?php endif; ?>
                            <td><?= $created ? date('d M, Y h:i a', ($created)) : 'Not available.' ?></td>

                            <?php if($decoded_response && isset($decoded_response->receipt_url)) : ?>
                                <?php $receipt_url = $decoded_response->receipt_url; ?>
                            <?php else: ?>
                                <?php if(($decoded_response && isset($decoded_response->latest_invoice))) : ?>
                                    <?php 
                                        try {
                                            $invoice = $stripe->invoices->retrieve(
                                                $decoded_response->latest_invoice,
                                                []
                                            );
                                            if($invoice && isset($invoice->hosted_invoice_url)) {
                                                $receipt_url = $invoice->hosted_invoice_url;
                                            }
                                        } catch(\Exception $e) { log_message('ERROR', $e->getMessage()); }
                                    ?>
                                <?php endif; ?>
                            <?php endif; ?>
                            <td>
                                <?php if($receipt_url): ?>
                                    <a href="<?= $receipt_url ?>" target="_blank" data-toggle="tooltip" data-bs-placement="top" title="View receipt"><i class="fa fa-file-pdf-o"></i></a>
                                <?php else: ?>
                                    <a href="<?= l('dashboard/order/invoiceReceipt/' . $invoice['order_id']) ?>" target="_blank"><i class="fa fa-file-pdf-o"></i></a>
                                    <!-- The receipt is not available. -->
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>No data available.</td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if($type == INVOICE_SUBSCRIPTION) : ?>
    <?php if (($invoices->has_more)) : ?>
        <div class="row mt-4">
            <div class="col-lg-12">
    
                <nav aria-label="Page navigation example mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item">
                            <a class="page-link" href="<?= l('dashboard/order/invoices/' . $type . '/') . urlencode($invoices->next_page); ?>"> <?= 'Next'; ?> </a>
                        </li>
                    </ul>
                </nav>
    
            </div>
        </div>
    <?php endif; ?>
<?php elseif($type == INVOICE_PRODUCT) : ?>
    <?php if (isset($invoices) && $invoices): ?>
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
                                                                                            echo l('dashboard/order/invoices/') . $type . '/' . $prev;
                                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                        </li>
    
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('dashboard/order/invoices/') . $type . '/' . $i; ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>
    
                        <li class="page-item <?php if ($page >= $totalPages) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                            echo '#';
                                                                                        } else {
                                                                                            echo l('dashboard/order/invoices/') . $type . '/' . $next;
                                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
    
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
