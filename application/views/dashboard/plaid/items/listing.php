<div class="dashboard-content">
    <i class="fa-regular fa-cubes"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />

    <?php if (property_exists($entityArray, 'item')) : ?>
        <?php if (property_exists($entityArray->item, 'available_products')) : ?>
            <table class="style-1">
                <thead>
                    <tr>
                        <th class="col-3"><?= __('Available Products') ?></th>
                        <th>&centerdot;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($entityArray->item->available_products) > 0): ?>
                        <?php foreach($entityArray->item->available_products as $key => $value): ?>
                            <tr>
                                <td>
                                    <?= $value; ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <?= __('No products available') ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php if (property_exists($entityArray->item, 'billed_products')) : ?>
            <table class="style-1">
                <thead>
                    <tr>
                        <th class="col-3"><?= __('Billed Products') ?></th>
                        <th>&centerdot;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($entityArray->item->billed_products) > 0): ?>
                        <?php foreach($entityArray->item->billed_products as $key => $value): ?>
                            <tr>
                                <td>
                                    <?= $value; ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td>
                                <?= __('No institutions available') ?>.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>