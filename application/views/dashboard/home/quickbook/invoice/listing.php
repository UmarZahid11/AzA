<?php
    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
    $customer = $this->dataService->FindById('customer', $customerId);
    $error = $this->dataService->getLastError();
?>
<div class="dashboard-content">
    <?php if(isset($customerId) && $customerId): ?>
        <a href="<?= l('dashboard/home/quickbook-save/'. $entity . '/0/' . $customerId) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-plus text-white"></i> <?= __('Create') . ' ' . ucfirst($entity) ?></a>
    <?php endif; ?>
    <i class="fa-regular fa-book"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) . ((!$error) && isset($customer->FullyQualifiedName) ? ' for "' . $customer->FullyQualifiedName . '"' : '') ?></h4>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Date') ?></th>
                <th><?= __('No') ?>.</th>
                <th class="col-2"><?= __('Customer') ?></th>
                <th><?= __('Amount') ?></th>
                <th><?= __('Status') ?></th>
                <th>&centerdot;</th>
            </tr>
        </thead>
        <?php if (isset($entityArray) && count($entityArray) > 0) : ?>
            <tbody>
                <?php foreach ($entityArray as $key => $value) : ?>
                    <tr>
                        <td>
                            <?= $value->TxnDate ?>
                        </td>
                        <td>
                            <?= $value->DocNumber ?>
                        </td>
                        <td>
                            <?php
                                $customer = $this->dataService->FindById('customer', $value->CustomerRef);
                                $error = $this->dataService->getLastError();
                            ?>
                            <?php if(!$error): ?>
                                <?= $customer->FullyQualifiedName; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $value->CurrencyRef . ' ' . $value->TotalAmt ?>
                        </td>
                        <td>
                            <?= ($value->Balance == 0 ? '<i class="fa fa-circle-check"></i>&nbsp;Paid' : 'Overdue on ' . $value->DueDate) ?>
                        </td>
                        <td>
                            <a href="<?= l('dashboard/home/quickbook-view/'.$entity.'/' . $value->Id, '/1') ?>" target="_blank"><i class="fa fa-download"></i>&nbsp;<?= __('Download PDF') ?></a>
                            |
                            <a href="<?= l('dashboard/home/quickbook-view/'.$entity.'/' . $value->Id) ?>" target="_blank"><i class="fa fa-eye"></i>&nbsp;<?= __('View') ?></a>
                            |
                            <a href="<?= l('dashboard/home/quickbook-save/'.$entity.'/' . $value->Id . '/' . $value->CustomerRef) ?>" target="_blank"><i class="fa fa-pencil"></i>&nbsp;<?= __('Edit') ?></a>
                       </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td colspan="2"><?= isset($page) && $page > 0 ? __('No more '.$entity.'(s) available') : __('No '.$entity.' available') ?>.</td>
                </tr>
            </tbody>
            <a href="<?= l('dashboard/home/quickbook-listing/'.$entity . '/' . (($page - 1) > 0 ? $page - 1 : 0) . (isset($customerId) && $customerId ? '/' . $customerId : '' ) ) ?>"><i class="fa fa-arrow-left">&nbsp;</i><?= __('Go to previous page') ?></a>
        <?php endif; ?>
    </table>

</div>

<?php if (isset($entityArray) && count($entityArray) > 0) : ?>
    <div class="row mt-4">
        <div class="col-lg-12">

            <nav aria-label="Page navigation example mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 0) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/home/quickbook-listing/' . $entity . '/') . $prev . (isset($customerId) && $customerId ? '/' . $customerId : '' ); ?>">
                            <i class="far fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $page; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/home/quickbook-listing/' . $entity . '/') . $i . (isset($customerId) && $customerId ? '/' . $customerId : '' ); ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/home/quickbook-listing/' . $entity . '/') . $next . (isset($customerId) && $customerId ? '/' . $customerId : '' ); ?>">
                            <i class="far fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>
