<div class="dashboard-content">
    <?php if (isset($product['product_updatedon']) && $product['product_updatedon'] && validateDate($product['product_updatedon'], 'Y-m-d H:i:s')) : ?>
        <div class="float-right">
            <small><?= 'Last updated on: ' . date('d M, Y h:i a', strtotime($product['product_updatedon'])) ?></small>
        </div>
    <?php endif; ?>
    <i class="fa-light fa-box"></i>
    <!--ucfirst($type)-->
    <h4><?= 'List' . ' ' . ucfirst($reference) ?> </h4>
    <hr />
    <small class="text-danger">* Indicates required field</small>

    <input type="hidden" name="enable_product_listing_subscription" value="<?= g('db.admin.enable_technology_listing_subscription') ?>" />
    <input type="hidden" name="product_listing_subscription_fee" value="<?= g('db.admin.technology_listing_subscription_fee') ?>" />
    <input type="hidden" name="product_subscription_expired" value="<?= (isset($product['product_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($product['product_subscription_expiry'])) ?>" />

    <?php if (isset($reference) && $reference == PRODUCT_REFERENCE_TECHNOLOGY && 0) : ?>
        <?php if(isset($product['product_id']) && strtotime(date('Y-m-d H:i:s')) > strtotime($product['product_subscription_expiry'])): ?>
            <p class="text-danger">This technology's subscription has expired on <?= date('d M, Y h:i a', strtotime($product['product_subscription_expiry'])) ?>. <small>Renew the subscription to continue listing of this technology.</small></p>
        <?php endif; ?>
    <?php endif; ?>

    <input type="hidden" name="currency" value="<?= DEFAULT_CURRENCY_SYMBOL ?>" />

    <div class="create-profile-form">
        <form class="productForm" id="productForm" method="POST" action="javascript:;" novalidate>
            <?php if (isset($product['product_id'])) : ?>
                <input type="hidden" name="product_id" value="<?= $product['product_id']; ?>" />
            <?php endif; ?>
            <input type="hidden" name="_token" value="" />
            <input type="hidden" name="type" value="<?= isset($type) && $type ? $type : 'insert'; ?>" />
            <input type="hidden" class="slug" name="product[product_slug]" value="<?= isset($product['product_slug']) ? $product['product_slug'] : '' ?>" />
            <input type="hidden" name="product[product_signup_id]" value="<?= $this->userid; ?>" />
            <input type="hidden" name="product[product_reference_type]" value="<?= $reference; ?>" />

            <div class="row">
                <div class="col-md-6">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Number') ?> </label>
                    <!--required-->
                    <input type="text" class="form-control" placeholder="Enter a unique <?= $reference ?> number" name="product[product_number]" value="<?= isset($product['product_number']) ? $product['product_number'] : '' ?>" maxlength="50" />
                </div>

                <div class="col-md-6">
                    <label><?= __('Name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter <?= $reference ?> name" name="product[product_name]" required value="<?= isset($product['product_name']) ? $product['product_name'] : '' ?>" maxlength="200" />
                </div>

                <div class="col-md-6">
                    <label><?= $reference == PRODUCT_REFERENCE_SERVICE ? __('Fee') : __('Cost') ?> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" placeholder="Enter <?= $reference ?> cost" name="product[product_cost]" required value="<?= isset($product['product_cost']) ? $product['product_cost'] : '' ?>" min="0" max="9999999" />
                </div>

                <?php if ($reference == PRODUCT_REFERENCE_PRODUCT) : ?>
                    <div class="col-md-6">
                        <label><?= __('Quantity') ?> <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" placeholder="Enter <?= $reference ?> quantity" name="product[product_quantity]" required value="<?= isset($product['product_quantity']) ? $product['product_quantity'] : 1 ?>" min="0" max="9999999" />
                    </div>
                <?php else : ?>
                    <input type="hidden" class="form-control" name="product[product_quantity]" required value="<?= isset($product['product_quantity']) ? $product['product_quantity'] : 1 ?>" min="1" max="9999999" />
                <?php endif; ?>

                <div class="col-md-6">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Industry') ?></label>
                    <!--required-->
                    <input type="text" class="form-control" placeholder="Enter <?= $reference ?> industry" name="product[product_industry]" value="<?= isset($product['product_industry']) ? $product['product_industry'] : '' ?>" maxlength="200" />
                </div>

                <div class="col-md-6">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Category') ?></label>
                    <!--required-->
                    <select name="product[product_category][]" class="productCategory form-select" multiple>
                        <?php
                        $fetched_product_category = array();
                        if (isset($product['product_category']) && $product['product_category'] != NULL && @unserialize($product['product_category']) !== FALSE) {
                            $fetched_product_category = unserialize($product['product_category']);
                        }
                        ?>

                        <option value="" hidden><?= __('Choose Category') ?></option>
                        <?php foreach (CATEGORY_TYPE as $key => $value) : ?>
                            <option value="<?= $value ?>" <? //= (isset($value) && in_array($value, $fetched_product_category)) ? 'selected' : '' 
                                                            ?>><?= $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php if (isset($reference) && $reference == PRODUCT_REFERENCE_TECHNOLOGY && 0) : ?>
                    <div class="col-md-12">
                        <label><?= __('Tech type') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" name="product[product_job_type]" required>
                            <?php foreach ($product_job_type as $key => $value) : ?>
                                <option value="<?= $value['job_type_id'] ?>" <?= isset($product['product_job_type']) && $product['product_job_type'] == $value['job_type_id'] ? 'selected' : ''; ?>><?= $value['job_type_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="col-md-12">
                    <!--<span class="text-danger">*</span>-->
                    <label><?= __('Function') ?> <span data-toggle="tooltip" data-bs-placement="top" title="Describe the functionality of the <?= $reference ?>."><i class="fa fa-circle-question"></i></span></label>
                    <!--required-->
                    <textarea class="form-control" name="product[product_function]" maxlength="5000" placeholder="Details about how the <?= $reference ?> functions."><?= isset($product['product_function']) ? $product['product_function'] : '' ?></textarea>
                </div>

                <?php if (isset($reference) && $reference == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
                    <div class="col-md-12">
                        <label><?= __('Description') ?> <span class="text-danger">*</span> <span data-toggle="tooltip" data-bs-placement="top" title="Describe the <?= $reference ?>."><i class="fa fa-circle-question"></i></span></label>
                        <textarea class="form-control" name="product[product_description]" required maxlength="5000" placeholder="<?= ucfirst($reference) ?> description."><?= isset($product['product_description']) ? $product['product_description'] : '' ?></textarea>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label><?= __('Video attachment') ?>&nbsp;(<small><?= __('The size limit for video is 10 MB') ?>):</small>
                        <span data-toggle="tooltip" data-bs-placement="top" title="A detailed video describing the <?= $reference ?>."><i class="fa fa-circle-question"></i></span>
                    </label>
                    <label class="form__container" id="upload-container"><?= __('Choose or Drag & Drop Video') ?>
                        <input type="file" name="product_attachment" class="form__file" id="upload-product-video" accept="video/*" />
                    </label>
                    <p id="files-area">
                        <span id="videoList">
                            <span id="video-names"></span>
                        </span>
                    </p>
                    <div class="videoDiv">
                        <?php if (isset($product['product_attachment']) && $product['product_attachment']) : ?>
                            <a data-fancybox href="<?= get_image($product['product_attachment_path'], $product['product_attachment']) ?>">
                                <img src="<?= g('images_root') . 'video-placeholder.png' ?>" width="300" onerror="this.onerror=null;this.src='https://placehold.co/800&@2x.png';" />
                            </a>
                            <a class="video-del-btn" style="bottom: 70px !important; color: #fff;" href="javascript:;" data-id="<?= isset($product['product_id']) && $product['product_id'] ? (int) $product['product_id'] : 0 ?>" data-toggle="tooltip" data-bs-placement="top" title="Delete this video"><i class="fa fa-close" aria-hidden="true"></i></a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($reference) && $reference == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
                    <div class="col-md-3">
                        <label><?= __('Looking for co-founders') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" name="product[product_require_cofounder]" required>
                            <option value="0" <?= isset($product['product_require_cofounder']) && $product['product_require_cofounder'] == 0 ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?= isset($product['product_require_cofounder']) && $product['product_require_cofounder'] == 1 ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><?= __('Looking for collaborators') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" name="product[product_require_collaborator]" required>
                            <option value="0" <?= isset($product['product_require_collaborator']) && $product['product_require_collaborator'] == 0 ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?= isset($product['product_require_collaborator']) && $product['product_require_collaborator'] == 1 ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><?= __('Looking for investors') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" name="product[product_require_investor]" required>
                            <option value="0" <?= isset($product['product_require_investor']) && $product['product_require_investor'] == 0 ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?= isset($product['product_require_investor']) && $product['product_require_investor'] == 1 ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label><?= __('Looking for advisors') ?> <span class="text-danger">*</span></label>
                        <select class="form-select" name="product[product_require_advisor]" required>
                            <option value="0" <?= isset($product['product_require_advisor']) && $product['product_require_advisor'] == 0 ? 'selected' : ''; ?>>No</option>
                            <option value="1" <?= isset($product['product_require_advisor']) && $product['product_require_advisor'] == 1 ? 'selected' : ''; ?>>Yes</option>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="col-md-12">
                    <label><?= __('Status') ?> <span class="text-danger">*</span> <span data-toggle="tooltip" data-bs-placement="top" title="Activate or deactivate the <?= $reference ?>."><i class="fa fa-circle-question"></i></span></label>
                    <select class="form-select" name="product[product_status]">
                        <option value="1" <?= isset($product['product_status']) && $product['product_status'] == 1 ? 'selected' : ''; ?>>Active</option>
                        <option value="0" <?= isset($product['product_status']) && $product['product_status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <div class="row" id="stripeDiv">
                        <?php if (isset($reference) && $reference == PRODUCT_REFERENCE_TECHNOLOGY) : ?>
                            <?php if (g('db.admin.enable_technology_listing_subscription') && ((!isset($product['product_id'])) || (isset($product['product_id']) && $product['product_subscription_expiry'] && strtotime(date('Y-m-d H:i:s')) > strtotime($product['product_subscription_expiry'])))) : ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select product post duration <span class="text-danger">*</span> <span data-toggle="tooltip" title="The number of duration the product post will be active."><i class="fa fa-circle-question"></i></span></label>
                                        <select class="form-select" name="product[product_subscription_interval]" required>
                                            <?php for($i = 1; $i <= 28; $i++): ?>
                                                <option value="<?= $i ?>"><?= $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <label><small class="text-danger">The technology post will be listed for <span class="subscriptionIntervalText">1</span> <span class="subscriptionIntervalType"></span>(s).</small></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select product post duration type <span class="text-danger">*</span></label>
                                        <select class="form-select" name="product[product_subscription_interval_type]" required>
                                            <option value="day">Day</option>
                                            <option value="week">Week</option>
                                            <option value="month">Month</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?php if (isset($company_contact_exists) && $company_contact_exists) : ?>
                                        <input type="hidden" name="mount_stripe" value="1" />

                                        <div class="form-row card-group" id="card-group">
                                            <label for="card-element">
                                                Credit or debit card <span class="text-danger">*</span>
                                            </label>
                                            <div id="card-element" class="form-control card-elements">
                                            </div>

                                            <small id="card-errors" class="text-danger" role="alert"></small>
                                        </div>
                                        <label><small class="text-danger">Note: You will be charged <span class="subcsriptionPrice"><?= price(g('db.admin.technology_listing_subscription_fee')) ?></span> per technology post.</small></label>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- calculateProductSubscriptionFee -->

                <div class="col-12 mt-4" id="triggerContactDiv">
                    <?php if (isset($company_contact_exists) && $company_contact_exists) : ?>
                        <button class="btn btn-custom" id="productFormBtn"><?= __('Publish') ?></button>
                    <?php else : ?>
                        <small class="text-danger">Add company contact number before adding new <?= $reference ?>.</small>
                        <a id="triggerContactBtn" href="javascript:;" data-toggle="tooltip" data-bs-placement="top" title="refresh">
                            <i class="fa fa-refresh"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>

<script>

    var formId = 'productForm'
    var form = document.getElementById(formId);
    var is_mounted = false;
    //
    var currency = $('input[name=currency]').val();

    function mount_stripe(card) {
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        return true;
    }

    function unmount_stripe(card) {
        card.unmount();
        return false;
    }

    function calculateProductSubscriptionFee(currency, fee, interval, interval_type) {
        var calculatedFee = fee;
        switch(interval_type) {
            case 'day':
                calculatedFee = (fee) * interval;
                break;
            case 'week':
                calculatedFee = (fee * 7) * interval;
                break;
            case 'month':
                calculatedFee = (fee * 28) * interval;
                break;
        }
        return (currency + ' ' + parseFloat(calculatedFee).toFixed(2));
    }

    const stripe = Stripe('<?= STRIPE_PUBLISHABLE_KEY ?>');
    const elements = stripe.elements();
    // Custom styling can be passed to options when creating an Element.
    const style = {
        base: {
            // Add your base input styles here. For example:
            fontSize: '16px',
            color: '#32325d',
        },
    };

    // Create an instance of the card Element.
    const card = elements.create('card', {
        style
    });

    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    if ($('input[name=mount_stripe]').length) {
        is_mounted = mount_stripe(card)
    }

    async function saveData() {

        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        var data = new FormData(document.getElementById('productForm'))
        var url = "<?php echo l('dashboard/product/saveData'); ?>";
        //
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                async: true,
                dataType: 'json',
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                },
                beforeSend: function() {
                    $('#productFormBtn').attr('disabled', true)
                    $('#productFormBtn').html('Publishing ...')
                },
                complete: function() {
                    $('#productFormBtn').attr('disabled', false)
                    $('#productFormBtn').html('Publish')
                }
            });
        })
    }

    async function deleteAttachment(data) {
        var url = base_url + 'dashboard/product/deleteAttachment'
        //
        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(response) {
                    resolve(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                complete: function(jqXHR, textStatus) {
                    hideLoader()
                },
                beforeSend: function() {
                    showLoader()
                }
            });
        })
    }

    async function appendToken(formId) {
        //
        $('#productFormBtn').attr('disabled', true)
        $('#productFormBtn').html('Publishing ...')
        //
        let promise = new Promise((resolve, reject) => {
            if(is_mounted) {
                var success;

                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                        resolve(false)
                    } else {
                        const form = document.getElementById(formId);
                        const hiddenInput = document.createElement('input');
                        hiddenInput.setAttribute('type', 'hidden');
                        hiddenInput.setAttribute('name', 'stripeToken');
                        hiddenInput.setAttribute('value', result.token.id);
                        form.appendChild(hiddenInput);
                        //
                        resolve(true)
                    }
                });
            } else {
                resolve(true)
            }
        })
        
        //
        $('#productFormBtn').attr('disabled', false)
        $('#productFormBtn').html('Publish')

        return promise;
    }

    function saveDataRequest() {
        saveData().then(
            function(response) {
                if (response.status == 0) {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error") ?>',
                        content: response.txt,
                    });
                    return false;
                } else if (response.status == 1) {
                    AdminToastr.success(response.txt, 'Success');
                    if(response.type == 'insert') {
                        if(response.slug) {
                            location.href = base_url + 'dashboard/product/detail/' + response.slug
                        } else {
                            location.reload()
                        }
                    }
                }
            }
        )
    }

    $(document).ready(function() {

        var product_listing_subscription_fee = calculateProductSubscriptionFee(currency, $('input[name=product_listing_subscription_fee]').val(), $('select[name="product[product_subscription_interval]"]').val(), $('select[name="product[product_subscription_interval_type]"]').val())

        $('.subcsriptionPrice').html(product_listing_subscription_fee)
        $('.subscriptionIntervalText').html($('select[name="product[product_subscription_interval]"]').val())
        $('.subscriptionIntervalType').html($('select[name="product[product_subscription_interval_type]"]').val())

        //
        $('select[name="product[product_subscription_interval]"]').on('change', function(){
            var product_listing_subscription_fee = calculateProductSubscriptionFee(currency, $('input[name=product_listing_subscription_fee]').val(), $('select[name="product[product_subscription_interval]"]').val(), $('select[name="product[product_subscription_interval_type]"]').val())
            $('.subcsriptionPrice').html(product_listing_subscription_fee)
            $('.subscriptionIntervalText').html($(this).val())
            $('.previewSubmit').attr('data-html', $('.previewSubmit').html())
        })

        //
        $('select[name="product[product_subscription_interval_type]"]').on('change', function(){
            var product_listing_subscription_fee = calculateProductSubscriptionFee(currency, $('input[name=product_listing_subscription_fee]').val(), $('select[name="product[product_subscription_interval]"]').val(), $('select[name="product[product_subscription_interval_type]"]').val())
            $('.subcsriptionPrice').html(product_listing_subscription_fee)
            $('.subscriptionIntervalType').html($(this).val())
            $('.previewSubmit').attr('data-html', $('.previewSubmit').html())
        })

        $('body').on('click', '#triggerContactBtn', function() {
            $(this).html('<img src="<?= g('images_root') . 'tail-spin-dark.svg' ?>" width="20" />')
            setTimeout(function() {
                $("#triggerContactDiv").load(location.href + " #triggerContactDiv>*", function() {
                    if($('input[name="product[product_reference_type]"]').val() == '<?= PRODUCT_REFERENCE_TECHNOLOGY ?>') {
                        $("#stripeDiv").load(location.href + " #stripeDiv>*", function() {
                            $('[data-toggle="tooltip"]').tooltip()
                            is_mounted = mount_stripe(card)
                        });
                    } else {
                        $('[data-toggle="tooltip"]').tooltip()
                    }
                });
            }, 1000)
        })

        // submit after preview
        $('body').on('submit', '.productForm', function() {
            error = false;
            size_error = false;

            $('#upload-product-video').each(function(index, ele) {
                for (var i = 0; i < ele.files.length; i++) {
                    const file = ele.files[i];
                    if (file.size > 10000000) {
                        size_error = true;
                        error = true;
                    }
                }
            })

            if (!$('#productForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#productForm').addClass('was-validated');
                $('#productForm').find(":invalid").first().focus();
                error = true;
            } else {
                $('#productForm').removeClass('was-validated');
            }

            if (!error) {
                if ($('input[name=mount_stripe]').length) {
                    appendToken(formId).then(
                        function(response) {
                            if(response) {
                                saveDataRequest()
                            }
                        }
                    )
                } else {
                    saveDataRequest()
                }
            } else {
                if (size_error) {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error") ?>',
                        content: '<?= __("1 or more file(s) has exceeded upload size limit!") ?>',
                    });
                }
            }
        })

        /**
         * Method generateSlug
         *
         * @return void
         */
        function generateSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        $('input[name="product[product_name]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(generateSlug($(this).val()))
        })

        $('body').on('click', '.video-del-btn', function() {
            swal({
                title: "<?= __('Warning') ?>",
                text: 'Delete this video',
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    var data = {
                        'id': $(this).data('id')
                    }
                    deleteAttachment(data).then(
                        function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                                $('.videoDiv').remove();
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

        //
        const dt = new DataTransfer();

        $('#upload-product-video').on('change', function() {
            for (var i = 0; i < this.files.length; i++) {
                // 100000000 = 100 MB
                // 50000000 = 50 MB
                // 10000000 = 10 MB
                // 1000000 = 1 MB
                // 100000 = 100 KB
                let fileBloc = $('<span/>', {
                        class: 'file-block'
                    }),
                    fileName = $('<span/>', {
                        class: 'name',
                        text: this.files.item(i).name
                    });
                console.log(this.files.item(i).size);
                if (this.files.item(i).size < 10000000) {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a></span>').append(fileName);
                } else {
                    fileBloc.html('<a href="javascript:;" class="file-delete"><span><i class="fa fa-trash-can"></i></a><i class="fa fa-warning text-danger" data-toggle="tooltip" data-bs-placement="top" title="<?= __(ERROR_UPLOAD_LIMIT_EXCEED) ?>"></i>&nbsp;</span>').append(fileName);
                }
                $("#videoList > #video-names").html('')
                $("#videoList > #video-names").append(fileBloc);
                $('[data-toggle="tooltip"]').tooltip()
            };
            dt.items.remove(0);
            for (let file of this.files) {
                dt.items.add(file);
            }
            this.files = dt.files;

            $('a.file-delete').click(function() {
                let name = $(this).next('span.name').text();
                $(this).parent().remove();
                for (let i = 0; i < dt.items.length; i++) {
                    if (name === dt.items[i].getAsFile().name) {
                        dt.items.remove(i);
                        continue;
                    }
                }
            });
        })
    });

    // make select2
    $('.productCategory').select2({
        tags: true,
        maximumSelectionSize: 10,
        data: <?= json_encode($fetched_product_category) ?>
    });
    $('.productCategory').val(<?= json_encode($fetched_product_category) ?>).trigger('change');
</script>