<div class="dashboard-content">
    <i class="fa-regular fa-lock"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />

    <?php if (property_exists($entityArray, 'item')) : ?>
        <div class="row">
            <?php if (property_exists($entityArray->item, 'available_products')) : ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <h5><?= __('Available Products') ?></h5>
                    <ul>
                        <?php foreach ($entityArray->item->available_products as $key => $value) : ?>
                            <li><i class="fa-regular fa-check-circle"></i> <?= ucfirst($value) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if (property_exists($entityArray->item, 'billed_products')) : ?>
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <h5><?= __('Billed Products') ?></h5>
                    <ul>
                        <?php foreach ($entityArray->item->billed_products as $key => $value) : ?>
                            <li><i class="fa-regular fa-check-circle"></i> <?= ucfirst($value) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (property_exists($entityArray, 'accounts')) : ?>
        <table class="style-1">
            <thead>
                <tr>
                    <th class="col-3"><?= __('Id') ?></th>
                    <th class="col-2"><?= __('Name') ?></th>
                    <th class="col-3"><?= __('Official Name') ?></th>
                    <th><?= __('Type') ?></th>
                    <th><?= __('Balance') ?></th>
                    <th>&centerdot;</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($entityArray->accounts) > 0) : ?>
                    <?php foreach ($entityArray->accounts as $key => $value) : ?>
                        <tr>
                            <td>
                                <?= $value->account_id; ?>
                            </td>
                            <td>
                                <?= $value->name; ?>
                            </td>
                            <td>
                                <?= $value->official_name; ?>
                            </td>
                            <td>
                                <?= $value->type; ?>
                            </td>
                            <td>
                                <?= $value->balances->iso_currency_code . ' ' . $value->balances->available; ?>
                            </td>
                            <td></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td>
                            <?= __('No categories available') ?>.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
