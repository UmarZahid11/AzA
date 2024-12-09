<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/' . $entity) ?>" target="_blank" class="btn btn-custom float-right"><i class="fa fa-plus text-white"></i> <?= __('Create') . ' ' . ucfirst($entity) ?> </a>
    <i class="fa-regular fa-book"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Id') ?></th>
                <th class="col-2"><?= __('Name') ?></th>
                <th class="col-2"><?= __('Create time') ?></th>
                <th>&centerdot;</th>
            </tr>
        </thead>
        <?php if (isset($entityArray) && count($entityArray) > 0) : ?>

            <tbody>
                <?php foreach ($entityArray as $key => $value) : ?>
                    <tr>
                        <td>
                            <?= $value->Id ?>
                        </td>
                        <td>
                            <?php if($entity == 'bill' || $entity == 'billpayment'): ?>
                                <?php
                                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                                    $VendorRef = NULL;
                                    if(property_exists($value, 'VendorRef') && $value->VendorRef) {
                                        $VendorRef = $this->dataService->FindbyId('vendor', $value->VendorRef);
                                    }
                                ?>
                                <?= property_exists($VendorRef, 'FullyQualifiedName') && $VendorRef->FullyQualifiedName ? $VendorRef->FullyQualifiedName : (property_exists($VendorRef, 'DisplayName') ? $VendorRef->DisplayName : ''); ?>
                            <?php elseif($entity == 'timeactivity'): ?>
                                <?php
                                    $this->dataService = $this->session->userdata['quickbook']['service_instance'];
                                    $NameOfRef = NULL;
                                    if(property_exists($value, 'VendorRef') && $value->VendorRef) {
                                        $NameOfRef = $this->dataService->FindbyId('vendor', $value->VendorRef);
                                    }
                                    if(property_exists($value, 'EmployeeRef') && $value->EmployeeRef) {
                                        $NameOfRef = $this->dataService->FindbyId('employee', $value->EmployeeRef);
                                    }
                                ?>
                                <?= property_exists($NameOfRef, 'FullyQualifiedName') && $NameOfRef->FullyQualifiedName ? $NameOfRef->FullyQualifiedName : (property_exists($NameOfRef, 'DisplayName') ? $NameOfRef->DisplayName : ''); ?>
                            <?php else: ?>
                                <?= property_exists($value, 'FullyQualifiedName') && $value->FullyQualifiedName ? $value->FullyQualifiedName : (property_exists($value, 'DisplayName') ? $value->DisplayName : ''); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= $value->MetaData->CreateTime ?>
                        </td>
                        <td>
                            <?php if ($entity == 'customer') : ?>
                                <a href="<?= l('dashboard/home/quickbook-listing/invoice/0/' . $value->Id) ?>" target="_blank"><i class="fa fa-paper-plane"></i>&nbsp;<?= __('View saved invoices') ?></a>
                                |
                                <a href="<?= l('dashboard/home/quickbook-save/invoice/0/' . $value->Id) ?>" target="_blank"><i class="fa fa-address-card"></i>&nbsp;<?= __('Send invoice') ?></a>
                                |
                            <?php endif; ?>
                            <a href="<?= l('dashboard/home/quickbook-view/' . $entity . '/' . $value->Id) ?>" target="_blank"><i class="fa fa-eye"></i>&nbsp;<?= __('View') ?></a>
                            |
                            <a href="<?= l('dashboard/home/quickbook-save/' . $entity . '/' . $value->Id) ?>" target="_blank"><i class="fa fa-pencil"></i>&nbsp;<?= __('Edit') ?></a>
                            <?php if($entity == 'timeactivity'): ?>
                                |
                                <a href="javascript:;" class="deleteByApiBtn" data-id="<?= $value->Id ?>" data-param="Id" data-value="<?= $value->Id ?>" data-param2="SyncToken" data-value2="<?= $value->SyncToken ?>"><i class="fa fa-trash-can"></i>&nbsp;<?= __('Make inactive') ?></a>
                            <?php elseif($entity != 'bill'): ?>
                                |
                                <a href="javascript:;" class="deleteBtn" data-id="<?= $value->Id ?>" data-param="Active" data-value="false"><i class="fa fa-trash-can"></i>&nbsp;<?= __('Make inactive') ?></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <tbody>
                <tr>
                    <td colspan="2"><?= isset($page) && $page > 0 ? __('No more ' . $entity . '(s) available') : __('No ' . $entity . ' available') ?>.</td>
                </tr>
            </tbody>
            <a href="<?= l('dashboard/home/quickbook-listing/' . $entity . '/' . (($page - 1) > 0 ? $page - 1 : 0) . (isset($customerId) && $customerId ? '/' . $customerId : '' )) ?>"><i class="fa fa-arrow-left">&nbsp;</i><?= __('Go to previous page') ?></a>
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
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/home/quickbook-listing/' . $entity . '/' . $prev . (isset($customerId) && $customerId ? '/' . $customerId : '' )); ?>">
                            <i class="far fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php for ($i = 1; $i <= $page; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/home/quickbook-listing/' . $entity . '/' . $i . (isset($customerId) && $customerId ? '/' . $customerId : '' )); ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php echo l('dashboard/home/quickbook-listing/' . $entity . '/' . $next . (isset($customerId) && $customerId ? '/' . $customerId : '' )); ?>">
                            <i class="far fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('click', '.deleteBtn', function() {
            swal({
                title: "<?= __('Are you sure?') ?>",
                text: 'Make this <?= $entity ?> inactive.',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes, make inactive') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {

                    var data = {
                        'entity': '<?= $entity ?>',
                        'id': $(this).data('id'),
                        [$(this).data('param')]: $(this).data('value'),
                    }
                    if($(this).data('param2')) {
                        data[$(this).data('param2')] = $(this).data('value2')
                    }
                    var url = base_url + 'quickbook/saveEntity';

                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            async: true,
                            dataType: "json",
                            success: function(response) {
                                resolve(response)
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
            		}).then(
            		    function(response) {
            		        if (response.status) {
                                swal({
                                    title: "Success",
                                    text: response.txt,
                                    icon: "success",
                                }).then(() => {
                                    $(this).parent().parent().remove();
                                })
                            } else {
                                swal({
                                    title: "Error",
                                    text: response.txt.hasOwnProperty(0) && typeof response.txt == "object" ? response.txt[0] : (response.txt != undefined ? response.txt : '<?= __(ERROR_MESSAGE) ?>'),
                                    icon: "warning"
                                }).then((isConfirm) => {
                                    if (response.refresh) {
                                        location.reload();
                                    }
                                });
                            }
            		    }
                    )
                }
            })
        })

        $('body').on('click', '.deleteByApiBtn', function() {
            swal({
                title: "<?= __('Are you sure?') ?>",
                text: 'Make this <?= $entity ?> inactive.',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes, make inactive') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'entity': '<?= $entity ?>',
                        'id': $(this).data('id'),
                        [$(this).data('param')]: $(this).data('value'),
                    }
                    var url = base_url + 'quickbook/deleteEntity';

                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            async: true,
                            dataType: "json",
                            success: function(response) {
                                resolve(response)
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        })
            		}).then(
            		    function(response) {
            		        if (response.status) {
                                swal({
                                    title: "Success",
                                    text: response.txt,
                                    icon: "success",
                                }).then(() => {
                                    $(this).parent().parent().remove();
                                })
                            } else {
                                swal({
                                    title: "Error",
                                    text: response.txt.hasOwnProperty(0) && typeof response.txt == "object" ? response.txt[0] : (response.txt != undefined ? response.txt : '<?= __(ERROR_MESSAGE) ?>'),
                                    icon: "warning"
                                }).then((isConfirm) => {
                                    if (response.refresh) {
                                        location.reload();
                                    }
                                });
                            }
            		    }
                    )
                }
            })
        })
    })
</script>