<div class="dashboard-content">
    <i class="fa-regular fa-list-alt"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <?php if (property_exists($entityArray, 'categories')) : ?>
        <table class="style-1">
            <thead>
                <tr>
                    <th class="col-3"><?= __('Id') ?></th>
                    <th class="col-3"><?= __('Group') ?></th>
                    <th class="col-3"><?= __('Hierarchy') ?></th>
                    <th>&centerdot;</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($entityArray->categories) > 0) : ?>
                    <?php foreach ($entityArray->categories as $key => $value) : ?>
                        <tr>
                            <td>
                                <?= $value->category_id; ?>
                            </td>
                            <td>
                                <?= $value->group; ?>
                            </td>
                            <td>
                                <?php foreach ($value->hierarchy as $keyh => $valueh) : ?>
                                    <?= $valueh . ((count($value->hierarchy) == $keyh - 1) ? '' : '<br/>') ?>
                                <?php endforeach; ?>
                            </td>
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