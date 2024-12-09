<div class="dashboard-content">
    <?php if ($this->userid == $product['product_signup_id'] && $this->model_signup->hasPremiumPermission()) : ?>
        <a href="<?= l('dashboard/product/save/' . UPDATE . '/' . $product['product_reference_type'] . '/' . $product['product_slug']) ?>" class="float-right" data-toggle="tooltip" data-bs-placement="left" title="Edit this <?= $product['product_reference_type'] ?>">
            <i class="fa fa-edit"></i>
        </a>
    <?php endif; ?>
    <i class="fa fa-product-hunt"></i>
    <h4><?= ucfirst($product['product_reference_type']) . ' ' . __('details') ?> </h4>
    <hr />

    <div class="product-card">

        <div class="followCountArea">
            <?php if (isset($product['signup_id']) && $product['signup_id'] != $this->userid) : ?>
                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                    <div class="float-right">
                        <button class="btn btn-outline-custom followBtn" data-reference_id="<?= $product['product_id'] ?>" data-reference="<?= $type ?>">
                            <?= $this->model_signup_follow->isFollowing($product['product_id'], $this->userid, $product['product_reference_type']) ? __('Unfollow') : __('Follow') ?>
                        </button>
                        <?php if (in_array($product['product_reference_type'], [PRODUCT_REFERENCE_TECHNOLOGY, PRODUCT_REFERENCE_SERVICE])) : ?>
                            <?php $title = $product['product_reference_type'] == PRODUCT_REFERENCE_TECHNOLOGY ? 'Request for information' : 'Request for service'; ?>
                            <hr />
                            <div id="request_area">
                                <?php if ($this->model_product_request->requestExists($this->userid, $product['product_id'])) : ?>
                                    <p class="text-success"><i class="fa fa-check-circle"></i> Sent</p>
                                    <?php
                                        $product_request = $this->model_product_request->requestExists($this->userid, $product['product_id'], TRUE);
                                        $product_request_id = $product_request && isset($product_request['product_request_id']) ? $product_request['product_request_id'] : 0;
                                    ?>
                                    <a href="<?= l('dashboard/product/handle/' . JWT::encode($product_request_id)) ?>">View details <i class="fa fa-external-link"></i></a>
                                <?php else : ?>
                                    <button data-fancybox data-animation-duration="700" data-src="#requestModal" href="javascript:;" class="btn btn-outline-custom" data-toggle="tooltip" title="<?= $title ?>" data-bs-placement="top">Add to cart</button>
                                    <div class="grid">
                                        <div style="display: none;" id="requestModal" class="animated-modal">
                                            <h2><?= $title; ?></h2>
                                            <form class="requestForm" id="requestForm" action="javascript:;" novalidate>
                                                <input type="hidden" name="_token" />
                                                <input type="hidden" name="product_request[product_request_signup_id]" value="<?= $this->userid ?>" />
                                                <input type="hidden" name="product_request[product_request_product_id]" value="<?= $product['product_id'] ?>" />
                                                <?php if($product['product_reference_type'] == PRODUCT_REFERENCE_SERVICE): ?>
                                                    <div class="form-group">
                                                        <label>Proposed fee <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="product_request[product_request_proposed_fee]" min="0" max="999999" required value="<?= $product['product_cost'] ?>" />
                                                    </div>
                                                <?php endif; ?>
                                                <div class="form-group">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="product_request[product_request_description]" maxlength="1000" required></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Attachment</label>
                                                    <input type="file" class="form-control font-12" name="product_request_attachment" />
                                                </div>
                                                <div class="form-group mt-2">
                                                    <button type="submit" class="btn btn-custom" id="requestFormBtn">Send</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <p>
                <a href="<?= l('dashboard/profile/users/') . JWT::encode($product['product_id'], CI_ENCRYPTION_SECRET) . '/' . TYPE_FOLLOWER . '/' . $type ?>" target="_blank">
                    <?= $follower_count . ' ' . __('follower') ?>
                </a>
            </p>
        </div>

        <!--class="main-images"-->
        <div>
            <?php if (isset($product['product_attachment']) && $product['product_attachment']) : ?>
                <a data-fancybox href="<?= base_url() . $product['product_attachment_path'] . $product['product_attachment'] ?>">
                    <img src="<?= g('images_root') . 'video-placeholder.png' ?>" class="active" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                </a>
            <?php else : ?>
                <img id="blue" class="blue active" src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" />
            <?php endif; ?>
        </div>
        <hr />
        <div>
            <label class="float-right">Posted by:
                <a href="<?= l('dashboard/profile/detail/' . JWT::encode($product['signup_id'])) . '/' . $product['signup_type'] ?>" data-toggle="tooltip" data-bs-placement="top" title="See provider detail" >
                    <?= $this->model_signup->profileName($product, FALSE) ?> <i class="fa fa-external-link"></i>
                </a>
            </label>
            <h5 class="m-0">
                <?= $product['product_name'] ?>
            </h5>
        </div>

        <div class="float-right">
            <label><?= price($product['product_cost']); ?></label>
        </div>
        <?php if (isset($product['product_number']) && $product['product_number']) : ?>
            <div>
                <label>Id Number:</label><small> <?= $product['product_number'] ?></small>
            </div>
        <?php endif; ?>

        <?php if (isset($product['product_quantity'])) : ?>
            <?php if ($this->model_signup->hasPremiumPermission() && $product['product_reference_type'] == PRODUCT_REFERENCE_PRODUCT && $this->model_product->isProductInCart($product['product_id'])) : ?>
                <form class="update_cart_form" method="POST">
                    <input type="hidden" name="_token" value="" />
                    <input type="hidden" name="rowid" value="<?= $this->model_product->getProductRow($product['product_id']) ?>" />
                    <input type="hidden" name="id" value="<?= $this->model_product->getProductRow($product['product_id'], 'id') ?>" />
                    <input type="hidden" name="qty" value="<?= $this->model_product->getProductRow($product['product_id'], 'qty') ?>" />
                    <div class="color-price">
                        <div class="color-option">
                            <small>Quantity:</small>
                            <div class="circles">
                                <input type="number" name="product_quantity" class="form-control" value="<?= $this->model_product->getProductRow($product['product_id'], 'qty') ?? 1 ?>" min="1" max="99999" />
                            </div>
                            <button class="btn btn-custom" id="update_qty" disabled>Update</button>
                        </div>
                    </div>
                </form>
            <?php elseif ($this->model_signup->hasPremiumPermission()) : ?>
                <div>
                    <label>Product Quantity:</label><small> <?= $product['product_quantity'] ?></small>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($product['product_industry']) && $product['product_industry']) : ?>
            <label>Industry:</label><small> <?= $product['product_industry'] ?></small>
        <?php endif; ?>

        <?php
        $fetched_product_category = array();
        if (isset($product['product_category']) && $product['product_category'] != NULL && @unserialize($product['product_category']) !== FALSE) {
            $fetched_product_category = unserialize($product['product_category']);
        }
        ?>
        <?php if ($fetched_product_category) : ?>
            <div>
                <label>Category:</label>
                <small>
                    <?php foreach ($fetched_product_category as $key => $value) : ?>
                        <?= ($value) . (array_key_last($fetched_product_category) == $key ? '.' : ',&nbsp;') ?>
                    <?php endforeach; ?>
                </small>
            </div>
        <?php endif; ?>

        <?php if (isset($product['product_job_type']) && $product['product_job_type']) : ?>
            <diV>
                <label>Job type:</label>
                <small>
                    <?= $this->model_job_type->find_by_pk($product['product_job_type'])['job_type_name'] ?>
                </small>
            </diV>
        <?php endif; ?>

        <?php if ($product['product_function']) : ?>
            <div>
                <label>Function:</label>
                <small>
                    <?= $product['product_function'] ?? 'Not available' ?>
                </small>
            </div>
        <?php endif; ?>

        <?php if (isset($type) && $type == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
            <label>Description:</label>
            <small>
                <?= $product['product_description'] ?>
            </small>

        <?php endif; ?>

        <?php if ($product['product_reference_type'] == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
            <div>
                <label>Looking for Co-Founders:</label><small> <?= $product['product_require_cofounder'] ? 'Yes' : 'No' ?></small>
            </div>
            <div>
                <label>Looking for Collaborators:</label><small> <?= $product['product_require_collaborator'] ? 'Yes' : 'No' ?></small>
            </div>
            <div>
                <label>Looking for Investors:</label><small> <?= $product['product_require_investor'] ? 'Yes' : 'No' ?></small>
            </div>
            <div>
                <label>Looking for Advisors:</label><small> <?= $product['product_require_advisor'] ? 'Yes' : 'No' ?></small>
            </div>
        <?php endif; ?>

        <?php if ($this->userid != $product['product_signup_id'] && $this->model_signup->hasPremiumPermission() && in_array($product['product_reference_type'], [PRODUCT_REFERENCE_PRODUCT, PRODUCT_REFERENCE_TECHNOLOGY])) : ?>
            <div class="mt-2">
                <?php if (!$this->model_product->isProductInCart($product['product_id'])) : ?>
                    <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Add this product to shopping cart.") ?>" href="javascript:;" class="btn btn-custom w-100 add_to_cart" data-quantity="1" data-id="<?= $product['product_id'] ?>"><i class="fa fa-shopping-cart text-white"></i></a>
                <?php else : ?>
                    <a data-toggle="tooltip" data-bs-placement="top" title="<?= __("Remove this product from shopping cart.") ?>" href="javascript:;" class="btn btn-custom w-100 delete_cart_item" data-id="<?= $this->model_product->getProductRow($product['product_id']) ?>"><i class="fa fa-cart-circle-check text-white"></i></a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <hr />
        <?php $this->load->view('widgets/comment_widget.php'); ?>

    </div>

</div>

<script>
    $(document).ready(function() {
        $('body').on('change', 'input[name=product_quantity]', function() {
            if ($('.add_to_cart').length) {
                $('.add_to_cart').attr('data-quantity', $(this).val())
            }
            if ($('#update_qty').length) {
                if ($(this).val() == '<?= $this->model_product->getProductRow($product['product_id'], 'qty') ?>') {
                    $('#update_qty').attr('disabled', true)
                } else {
                    $('#update_qty').attr('disabled', false)
                }
                $('input[name=qty]').val($(this).val())
            }
        })

        $('body').on('submit', '#requestForm', function() {

            if (!$('#requestForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#requestForm').addClass('was-validated');
                $('#requestForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#requestForm').removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = new FormData(document.getElementById('requestForm'))
            var url = base_url + 'dashboard/product/saveRequest'

            AjaxRequest.fileAsyncRequest(url, data, false, '#requestFormBtn', 'Sending ...', 'Send').then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.message)
                        $(".followCountArea").load(location.href + " .followCountArea>*", function() {
                            $('.fancybox-close-small').trigger('click')
                            $('input[name="product_request[product_request_proposed_fee]"').val("")
                            $('textarea[name="product_request[product_request_description]"').val("")
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                        });
                    } else {
                        AdminToastr.error(response.message)
                    }
                }
            )
        })
    })
</script>