<div class="dashboard-content">
    <?php if(!$payment_method): ?>
        <a class="btn btn-custom float-right" href="<?= l('dashboard/payment/method/update') ?>">Update card</a>
    <?php endif; ?>
    <i class="fa-regular fa-credit-card"></i>
    <h4>Saved card</h4>
    <hr />
    <div class="mt-4">
        <div class="row">
            <div class="col-lg-12 col-md-12 mb-5">
                <table class="style-1">
                    <thead>
                        <tr>
                            <th><?= __('Card number') ?></th>
                            <th class="col-4"><?= __('Brand') ?></th>
                            <th><?= __('Month') ?></th>
                            <th><?= __('Year') ?></th>
                            <th>.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php if($payment_method): ?>
                                <td>**** **** **** <?= $payment_method->card->last4 ?></td>
                                <td><?= $payment_method->card->brand ?></td>
                                <td><?= $payment_method->card->exp_month ?></td>
                                <td><?= $payment_method->card->exp_year ?></td>
                                <td><a href="<?= l('dashboard/payment/method/update') ?>">Update</a></td>
                            <?php else: ?>
                                <td>No data found.</td>
                            <?php endif; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>