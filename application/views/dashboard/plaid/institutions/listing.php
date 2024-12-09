<div class="dashboard-content">
    <i class="fa-regular fa-buildings"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <?php if (property_exists($entityArray, 'institutions')) : ?>
        <table class="style-1">
            <thead>
                <tr>
                    <th class="col-3"><?= __('Id') ?></th>
                    <th class="col-3"><?= __('Name') ?></th>
                    <th class="col-3"><?= __('Country codes') ?></th>
                    <th class="col-3"><?= __('Products') ?></th>
                    <th>&centerdot;</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($entityArray->institutions) > 0): ?>
                    <?php foreach ($entityArray->institutions as $key => $value) : ?>
                        <tr>
                            <td>
                                <?= $value->institution_id; ?>
                            </td>
                            <td>
                                <?= $value->name; ?>
                            </td>
                            <td>
                                <?php foreach ($value->country_codes as $keyh => $valueh) : ?>
                                    <?= $valueh . ((count($value->country_codes) == $keyh - 1) ? '' : '<br/>') ?>
                                <?php endforeach; ?>
                            </td>
                            <td>
                                <?php foreach ($value->products as $keyh => $valueh) : ?>
                                    <?= $valueh . ((count($value->products) == $keyh - 1) ? '' : '<br/>') ?>
                                <?php endforeach; ?>
                            </td>
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
</div>

<?php if (property_exists($entityArray, 'institutions')) : ?>
    <?php if (isset($entityArray->institutions) && count($entityArray->institutions) > 0) : ?>
        <div class="row mt-4">
            <div class="col-lg-12">

                <nav aria-label="Page navigation example mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($page <= 0) {
                                                    echo 'disabled';
                                                } ?>">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/plaid/listing/' . $entity . '/' . $prev); ?>">
                                <i class="far fa-chevron-left"></i>
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $page; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) {
                                                        echo 'active';
                                                    } ?>">
                                <a class="page-link" href="<?= l('dashboard/plaid/listing/' . $entity . '/' . $i); ?>"> <?= $i; ?> </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item">
                            <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/plaid/listing/' . $entity . '/' . $next); ?>">
                                <i class="far fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>