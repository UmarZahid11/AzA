<div class="dashboard-content">
    <i class="fa-regular fa-money-bill"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <?php if (property_exists($entityArray, 'transactions')) : ?>
        <table class="style-1">
            <thead>
                <tr>
                    <th class="col-3"><?= __('Id') ?></th>
                    <th class="col-3"><?= __('Name') ?></th>
                    <th class="col-3"><?= __('Category') ?></th>
                    <th class="col-3"><?= __('Amount') ?></th>
                    <th>&centerdot;</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($entityArray->transactions) > 0): ?>
                    <?php foreach ($entityArray->transactions as $key => $value) : ?>
                        <tr>
                            <td>
                                <?= $value->transaction_id; ?>
                            </td>
                            <td>
                                <?= $value->name; ?>
                            </td>
                            <td>
                                <?php foreach ($value->category as $keyh => $valueh) : ?>
                                    <?= $valueh . ((count($value->category) == $keyh - 1) ? '' : '<br/>') ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?= $value->iso_currency_code . ' ' . $value->amount; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td>
                            <?= __('No transactions available') ?>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>