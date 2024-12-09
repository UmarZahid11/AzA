<div class="dashboard-content posted-theme">
    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <div class="float-right">
            <a data-toggle="tooltip" title="Post new <?= ($reference) ?>" class="btn btn-custom" href="<?= l('dashboard/product/save/' . CREATE . '/' . $reference) ?>">Add <?= $reference ?></a>
            &nbsp;
            <?php if (JWT::decode($userid, CI_ENCRYPTION_SECRET)) : ?>
                <a class="btn btn-custom" href="<?= l('dashboard/product/listing/' . $reference) ?>">All <?= $reference_plural ?></a>
            <?php else : ?>
                <a data-toggle="tooltip" title="See your posted <?= ($reference_plural) ?>" class="btn btn-custom" href="<?= l('dashboard/product/listing/' . $reference . '/' . JWT::encode($this->userid)) ?>">My <?= $reference_plural ?></a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <i class="fa fa-product-hunt"></i>
    <h4><?= ucfirst($reference_plural); ?></h4>
    <hr />
    <?php $tutorial = strtoupper($reference) . '_TUTORIAL'; ?>
    <a href="<?= l(TUTORIAL_PATH . constant($tutorial)) ?>" target="_blank"><i class="fa fa-film"></i> <?= ucfirst($reference) ?> Tutorial</a>
    <hr />
    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <div class="row">
            <div class="col-md-6">
                <small class="line-height-2"><?= record_detail($offset, $products, $products_count) ?></small>
            </div>
            <div class="offset-2 col-md-4">
                <div class="search-box-table">
                    <i class="fa-regular fa-magnifying-glass"></i>
                    <form class="searchForm" action="javascript:;">
                        <input type="text" class="form-control" name="product_search" placeholder="Search <?= $reference ?>" value="<?= isset($search) ? $search : '' ?>" />
                    </form>
                </div>
            </div>
        </div>

        <hr />
    <?php endif; ?>

    <table class="style-1">
        <thead>
            <tr>
                <th><?= __('Name') ?></th>
                <th><?= __('Id Number') ?></th>
                <th><?= $reference == PRODUCT_REFERENCE_SERVICE ? __('Fee') : __('Cost') ?></th>
                <?php if ($reference == PRODUCT_REFERENCE_PRODUCT) : ?>
                    <th><?= __('Quantity') ?></th>
                <?php endif; ?>
                <th class="col-2"><?= __('Industry') ?></th>
                <th><?= __('Category') ?></th>
                <th><?= __('Post date') ?></th>
                <?php if (JWT::decode($userid, CI_ENCRYPTION_SECRET)) : ?>
                    <?php if($userid == JWT::encode($this->userid)) : ?>
                        <th><?= __('Status') ?></th>
                    <?php endif; ?>
                <?php endif; ?>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($products) && count($products) > 0) : ?>
            <tbody>
                <?php foreach ($products as $key => $value) : ?>
                    <tr>
                        <td>
                            <a href="<?= l('dashboard/product/detail/' . $value['product_slug']) ?>"><?= isset($value['product_name']) && $value['product_name'] ? $value['product_name'] : '' ?></a>
                        </td>
                        <td>
                            <?= isset($value['product_number']) && $value['product_number'] ? $value['product_number'] : '' ?>
                        </td>
                        <td>
                            <?= isset($value['product_cost']) && $value['product_cost'] ? price($value['product_cost']) : '' ?>
                        </td>
                        <?php if ($reference == PRODUCT_REFERENCE_PRODUCT) : ?>
                            <td>
                                <?= isset($value['product_quantity']) ? ($value['product_quantity']) : '' ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?= isset($value['product_industry']) && $value['product_industry'] ? $value['product_industry'] : '' ?>
                        </td>
                        <td>
                            <?php if (isset($value['product_category']) && $value['product_category'] != NULL && @unserialize($value['product_category']) !== FALSE && unserialize($value['product_category']) !== 'N;' && unserialize($value['product_category']) != '') : ?>
                                <?php foreach (unserialize($value['product_category']) as $ke => $val) : ?><?php echo (($ke > 0 ? ', ' : '') . ($val)) ?><?php endforeach; ?>
                            <?php else : ?>
                                <?= NA ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= isset($value['product_createdon']) && validateDate($value['product_createdon'], 'Y-m-d H:i:s') ? date('d M, Y h:i a', strtotime($value['product_createdon'])) : '' ?>
                        </td>
                        <?php if (JWT::decode($userid, CI_ENCRYPTION_SECRET)) : ?>
                            <?php if($userid == JWT::encode($this->userid)) : ?>
                                <td>
                                    <?php if(isset($value['product_status'])) : ?>
                                    <?php switch($value['product_status']) {
                                        case STATUS_ACTIVE:
                                            echo '<span class="stats">Active</span>';
                                            break;
                                        case STATUS_INACTIVE:
                                            echo '<span class="stats expired-stats">Inactive</span>';
                                            break;
                                        case STATUS_DELETE:
                                            echo '<span class="stats expired-stats">Expired</span>';
                                            break;
                                    }
                                    ?>
                                    <?php else: ?>
                                        ...
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>
                        <?php endif; ?>
                        <td>
                            <?php if ($this->model_signup->hasPremiumPermission() && $this->userid == $value['product_signup_id']) : ?>
                                <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Edit this " . $value['product_reference_type'] . ".") ?>" href="<?= l('dashboard/product/save/' . UPDATE . '/' . $value['product_reference_type'] . '/' . $value['product_slug']) ?>" target="_blank"><i class="fa fa-edit"></i></a>&nbsp;
                                <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Delete this " . $value['product_reference_type'] .   ".") ?>" href="javascript:;" class="delete_product" data-id="<?= $value['product_id'] ?>"><i class="fa fa-trash"></i></a>
                            <?php elseif ($this->model_signup->hasPremiumPermission() && in_array($value['product_reference_type'], [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_TECHNOLOGY])) : ?>
                                <?php if (!$this->model_product->isProductInCart($value['product_id'])) : ?>
                                    <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Add this item to shopping cart.") ?>" href="javascript:;" class="add_to_cart" data-id="<?= $value['product_id'] ?>" data-quantity="1"><i class="fa fa-shopping-cart"></i></a>&nbsp;
                                <?php else : ?>
                                    <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Remove this item from shopping cart.") ?>" href="javascript:;" class="delete_cart_item" data-id="<?= $this->model_product->getProductRow($value['product_id']) ?>"><i class="fa fa-cart-circle-check"></i></a>&nbsp;
                                <?php endif; ?>
                            <?php endif; ?>
                            <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("View detail") ?>" href="<?= l('dashboard/product/detail/' . $value['product_slug']) ?>"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No ' . $reference_plural . ' available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>

<?php if (isset($products_count) && ($products_count) > 0) : ?>
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
                                                                                        echo l('dashboard/product/listing/') . $reference . '/' . $userid . '/' . $prev . '/' . $limit . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>">
                            <a class="page-link" href="<?= l('dashboard/product/listing/') . $reference . '/' . $userid . '/' . $i . '/' . $limit . '/' . $search; ?>"> <?= $i; ?> </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?php if ($page >= $totalPages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                        echo '#';
                                                                                    } else {
                                                                                        echo l('dashboard/product/listing/') . $reference . '/' . $userid . '/' . $next . '/' . $limit . '/' . $search;
                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        $('body').on('submit', '.searchForm', function() {
            location.href = base_url + 'dashboard/product/listing/' + '<?= $reference ?>' + '/' + '<?= $userid ?>' + '/' + '<?= $page ?>' + '/' + '<?= $limit ?>' + '/' + $('input[name=product_search]').val();
        })

        $('body').on('click', '.delete_product', function() {
            
            var data = {
                'id': $(this).data('id'),
                '_token': $('meta[name=csrf-token]').attr("content")
            }
            var url = base_url + 'dashboard/product/delete'
            
            swal({
                title: '<?= __("Are you sure?") ?>',
                text: '<?= __("You are about to delete this product!") ?>',
                icon: "warning",
                buttons: ['<?= __("Cancel") ?>', '<?= __("Ok") ?>'],
            }).
            then((isConfirm) => {
                if (isConfirm) {
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
                                swal("Success", response.txt, "success");
                                $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                    $('[data-toggle="tooltip"]').tooltip({
                                        html: true,
                                    })
                                });
                            } else {
                                swal("Error", response.txt, "error");
                            }
            		    }
        		    )
                } else {
                    swal("Cancelled", "Action aborted", "error");
                }
            })
        })
    })
</script>