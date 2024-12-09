<div class="dashboard-content">
    <i class="fa fa-paper-plane"></i>
    <h4>Request</h4>
    <?php  
        echo 'STATUS: ' . ucfirst(REQUEST_STATUS[$product_request_detail['product_request_current_status']]) ?? 'Not available';
    ?>

    <hr />
    
    <input type="hidden" name="order_id" value="<?= !empty($order) ? $order['order_id'] : 0; ?>" />
    <input type="hidden" name="order_merchant" value="<?= !empty($order) ? $order['order_merchant'] : 'STRIPE'; ?>" />
    
    <div class="card">
        <h5><?= ucfirst($product_request_detail['product_reference_type']) . ' ' . 'detail' ?></h5>
        
        <div>
            <label>Service provider: 
                <small>
                    <a href="<?= l('dashboard/profile/detail/' . JWT::encode($product_owner_detail['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $product_owner_detail['signup_type']) ?>"><?= $this->model_signup->profileName($product_owner_detail) ?>
                    </a>
                </small>
            </label>
        </div>

        <?php if (isset($product_request_detail['product_name']) && $product_request_detail['product_name']) : ?>
            <div>
                <label>Name:<small> <?= $product_request_detail['product_name'] ?></small>
                </label>
            </div>
        <?php endif; ?>

        <?php if (isset($product_request_detail['product_number']) && $product_request_detail['product_number']) : ?>
            <div>
                <label>Id Number:<small> <?= $product_request_detail['product_number'] ?></small>
                </label>
            </div>
        <?php endif; ?>

        <?php if (isset($product_request_detail['product_cost']) && $product_request_detail['product_cost']) : ?>
            <div>
                <label>Fee:<small> <?= price($product_request_detail['product_cost']) ?></small>
                </label>
            </div>
        <?php endif; ?>

        <?php if (isset($product_request_detail['product_industry']) && $product_request_detail['product_industry']) : ?>
            <label>Industry:<small> <?= $product_request_detail['product_industry'] ?></small></label>
        <?php endif; ?>

        <?php
        $fetched_product_category = array();
        if (isset($product_request_detail['product_category']) && $product_request_detail['product_category'] != NULL && @unserialize($product_request_detail['product_category']) !== FALSE) {
            $fetched_product_category = unserialize($product_request_detail['product_category']);
        }
        ?>
        <?php if ($fetched_product_category) : ?>
            <div>
                <label>Category:
                    <small>
                        <?php foreach ($fetched_product_category as $key => $value) : ?>
                            <?= ($value) . (array_key_last($fetched_product_category) == $key ? '.' : ',&nbsp;') ?>
                        <?php endforeach; ?>
                    </small>
                </label>
            </div>
        <?php endif; ?>

        <?php if (isset($product_request_detail['product_job_type']) && $product_request_detail['product_job_type']) : ?>
            <diV>
                <label>Job type:
                    <small>
                        <?= $this->model_job_type->find_by_pk($product_request_detail['product_job_type'])['job_type_name'] ?>
                    </small>
                </label>
            </diV>
        <?php endif; ?>

        <div>
            <label>Function:
                <small>
                    <?= $product_request_detail['product_function'] ?>
                </small>
            </label>
        </div>
        <a class="font-12" href="<?= l('dashboard/product/detail/' . $product_request_detail['product_slug']) ?>" target="_blank">
            View service detail <i class="fa fa-arrow-right" style="font-size: 12px !important"></i>
        </a>

    </div>

    <input type="hidden" name="card-mount-status" value="<?= 
        ($product_request_detail['product_request_signup_id'] == $this->userid && 
        ($product_request_detail['product_request_current_status'] != REQUEST_COMPLETE) && 
        $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_PENDING && 
        in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])) ? 1 : 0; ?>" 
    />
    
    <form class="responseForm" id="requestorResponseForm" action="javascript:;" novalidate>
        <input type="hidden" name="_token" />
        <input type="hidden" name="product_request_id" value="<?= $product_request_detail['product_request_id'] ?>" />
        <input type="hidden" name="product_request[product_request_product_id]" value="<?= $product_request_detail['product_request_product_id'] ?>" />
        <input type="hidden" name="product_request[product_request_signup_id]" value="<?= $product_request_detail['product_request_signup_id'] ?>" />
        
        <table class="style-1">
            <tbody>
                <tr>
                    <td>Requestor</td>
                    <td class="col-8">
                        <a href="<?= l('dashboard/profile/detail/' . JWT::encode($product_request_detail['signup_id']) . '/' . $product_request_detail['signup_type']) ?>" target="_blank"><?php echo $this->model_signup->profileName($product_request_detail, FALSE); ?></a>
                    </td>

                </tr>
                <tr>
                    <td>Proposed Fee</td>
                    <td>
                        <?php if ($product_request_detail['product_request_signup_id'] == $this->userid && !in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])) : ?>
                            <input type="number" class="form-control font-12" name="product_request[product_request_proposed_fee]" value="<?php echo ($product_request_detail['product_request_proposed_fee']); ?>" min="0" max="99999" />
                        <?php else : ?>
                            <?php echo price($product_request_detail['product_request_proposed_fee']); ?>
                        <?php endif; ?>
                    </td>

                </tr>
                <tr>
                    <td>Description</td>
                    <td>
                        <?php if ($product_request_detail['product_request_signup_id'] == $this->userid && !in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])) : ?>
                            <textarea class="form-control font-12" name="product_request[product_request_description]" maxlength="5000"><?php echo ($product_request_detail['product_request_description']); ?></textarea>
                        <?php else : ?>
                            <?php echo $product_request_detail['product_request_description'] ?? NA; ?>
                        <?php endif; ?>
                    </td>

                </tr>
                <tr>
                    <td>Attachment</td>
                    <td>
                        <?php if ($product_request_detail['product_request_signup_id'] == $this->userid && !in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])) : ?>
                            <input type="file" class="form-control font-12" name="product_request_attachment" />
                            <br />
                        <?php endif; ?>

                        <?php if ($product_request_detail['product_request_attachment']) : ?>
                            <a href="<?= l($product_request_detail['product_request_attachment_path'] . $product_request_detail['product_request_attachment']) ?>" target="_blank"><i class="fa fa-eye"></i> View</a>
                        <?php else : ?>
                            <?= NA ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>Payment status:</td>
                    <td>
                        <?php  
                            echo ucfirst(PAYMENT_STATUS[$product_request_detail['product_request_payment_status']]) ?? 'Not available';
                        ?>
                    </td>
                </tr>
                
                <?php if (
                        $product_request_detail['product_request_signup_id'] == $this->userid 
                        && $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_PENDING
                        && in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])
                    ) : ?>
                    <tr>
                        <td>Credit/Debit:</td>
                        <td>
                            <div id="card-element" class="form-control card-elements">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <span id="card-errors" class="text-danger"></span>
                            <br />
                        </td>
                    </tr>
                    <!--<tr>-->
                    <!--    <td></td>-->
                    <!--    <td>-->
                    <!--        <div id="paypal-button-container"></div>-->
                    <!--    </td>-->
                    <!--</tr>-->
                <?php endif; ?>

                <?php if ($product_request_detail['product_request_signup_id'] == $this->userid && ($product_request_detail['product_request_current_status'] != REQUEST_COMPLETE)) : ?>
                    <tr>
                        <td>
                            <button type="submit" class="btn btn-custom">
                                <?php if (
                                    $product_request_detail['product_request_signup_id'] == $this->userid 
                                    && $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_PENDING
                                    && in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED])
                                ) : ?>
                                    Send payment to escrow
                                <?php else: ?>
                                    Save
                                <?php endif; ?>
                            </button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>
    <?php if($product_request_detail['product_request_signup_id'] == $this->userid && in_array($product_request_detail['product_request_current_status'], [REQUEST_PENDING])): ?>
        <small class="text-danger">Note: The request will not be updated once accepted.</small>
        <hr/>
    <?php endif; ?>

    <div class="row">
        <h5>Action</h5>
        <div class="col-md-6">
            <?php if ($product_request_detail['product_signup_id'] == $this->userid) : ?>
                <form class="responseForm" id="ownerResponseForm" action="javascript:;" novalidate>
                    <input type="hidden" name="_token" />
                    <input type="hidden" name="product_request_id" value="<?= $product_request_detail['product_request_id'] ?>" />
                    <input type="hidden" name="product_request[product_request_product_id]" value="<?= $product_request_detail['product_request_product_id'] ?>" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Response <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="product_request[product_request_response]" maxlength="5000" required><?= $product_request_detail['product_request_response'] ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Status <span class="text-danger">*</span></label>
                                <select class="form-select font-12" name="product_request[product_request_current_status]" required>
                                    <option <?= in_array($product_request_detail['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_PENDING ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_PENDING ? 'selected' : '' ?>>Pending</option>
                                    <option <?= in_array($product_request_detail['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_ACCEPTED ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_ACCEPTED ? 'selected' : '' ?>>Accept</option>
                                    <option <?= in_array($product_request_detail['product_request_current_status'], [REQUEST_COMPLETE, REQUEST_INCOMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_REJECTED ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_REJECTED ? 'selected' : '' ?>>Reject</option>
                                    <option <?= in_array($product_request_detail['product_request_current_status'], [REQUEST_COMPLETE]) ? 'disabled' : ''; ?> value="<?= REQUEST_UPDATED ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_UPDATED ? 'selected' : '' ?>>Updated</option>
                                    <option disabled value="<?= REQUEST_COMPLETE ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_COMPLETE ? 'selected' : '' ?>>Complete</option>
                                    <option disabled value="<?= REQUEST_INCOMPLETE ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_INCOMPLETE ? 'selected' : '' ?>>Incomplete</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="btn btn-custom">Update</button>
                    </div>
                </form>
            <?php endif; ?>

            <div class="form-group">
                <label>Status:</label>
                <p>
                    <?php
                    switch ($product_request_detail['product_request_current_status']) {
                        case REQUEST_PENDING:
                            echo 'Pending';
                            break;
                        case REQUEST_ACCEPTED:
                            echo 'Accepted';
                            break;
                        case REQUEST_REJECTED:
                            echo 'Rejected';
                            break;
                        case REQUEST_COMPLETE:
                            echo 'Completed';
                            break;
                        case REQUEST_INCOMPLETE:
                            echo 'Incomplete';
                            break;
                        case REQUEST_UPDATED:
                            echo 'Updated';
                            break;
                    }
                    ?>
                </p>
            </div>
            <div class="form-group">
                <label>Last response:</label>
                <p><?= $product_request_detail['product_request_response'] ?? NA ?></p>
            </div>
        </div>
        <div class="col-md-6 border-left-1">
            <?php if ($product_request_detail['product_signup_id'] == $this->userid) : ?>
                <?php if($has_sent_request): ?>
                    <small class="text-danger">Note: The requestor has requested for a meeting.</small> <br />
                <?php endif; ?>
                <a href="<?= l('dashboard/meeting/save/create/' . JWT::encode($product_request_detail['product_request_id']) . '/0/' . MEETING_REFERENCE_PRODUCT) ?>" class="btn">
                    Schedule a meeting <i class="fa fa-arrow-right" style="font-size: 12px !important"></i>
                </a><br />
            <?php elseif($product_request_detail['product_request_signup_id'] == $this->userid) : ?>
                <?php if(!$has_sent_request): ?>
                    <a href="javascript:;" class="btn" data-fancybox data-animation-duration="700" data-src="#meetingRequestModal" >
                        Request a meeting <i class="fa fa-arrow-right" style="font-size: 12px !important"></i>
                    </a><br />
                    <div class="grid">
                        <div style="display: none;" id="meetingRequestModal" class="animated-modal">
                            <h4 data-toggle="tooltip" data-bs-placement="top" title="Send a meeting request to the owner for this service">Send a meeting request to the service provider</h4>
                            <form id="meetingRequestForm" action="javascript:;" novalidate>
                                <input type="hidden" name="_token" />
                                <input type="hidden" name="meeting_request[meeting_request_signup_id]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="meeting_request[meeting_request_reference]" value="<?= MEETING_REQUEST_REFERENCE_PRODUCT ?>" />
                                <input type="hidden" name="meeting_request[meeting_request_reference_id]" value="<?= $product_request_detail['product_request_product_id'] ?>" />
                                <input type="hidden" name="meeting_request[meeting_request_reference_request_id]" value="<?= $product_request_detail['product_request_id'] ?>" />
                                <label>Description (optional)</label>
                                <textarea class="form-control" name="meeting_request[meeting_request_description]" maxlength="5000"></textarea>
                                <button type="submit" class="btn btn-custom">Send</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <small class="text-danger">Note: The meeting request has been sent.</small> <br />
                    <!-- URL here -->
                    <a href="javascript:;" class="btn" data-fancybox data-animation-duration="700" data-src="#meetingRequestDetailModal" >See meeting request details <i class="fa fa-arrow-right" style="font-size: 12px !important"></i></a><br />
                    <div class="grid">
                        <div style="display: none;" id="meetingRequestDetailModal" class="animated-modal">
                            <h4>Meeting request detail</h4>
                            <label>Description</label>
                            <p><?= $has_sent_request['meeting_request_description'] ?? NA ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <a class="btn" href="<?= l('dashboard/meeting/listing/' . JWT::encode($product_request_detail['product_request_id']) . '/1/' . PER_PAGE . '/' . MEETING_REFERENCE_PRODUCT) ?>" target="_blank">
                See all scheduled meetings <i class="fa fa-arrow-right" style="font-size: 12px !important"></i>
            </a>
        </div>
    </div>

    <?php if (in_array($product_request_detail['product_request_current_status'], [REQUEST_ACCEPTED, REQUEST_UPDATED]) && $product_request_detail['product_request_signup_id'] == $this->userid) : ?>
        <?php if ($product_request_detail['product_request_current_status'] != REQUEST_COMPLETE && $product_request_detail['product_request_payment_status'] == PAYMENT_STATUS_ESCROW) : ?>
            <hr />
            <div class="card">
                <h4>Review</h4>
    
                <div class="form-group">
                    <label>Mark <?= $product_request_detail['product_reference_type'] ?> as <span class="text-danger">*</span></label>
                    <select class="form-select font-12" name="product_request[product_request_current_status]" required>
                        <option value="">Select</option>
                        <option value="<?= REQUEST_COMPLETE ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_COMPLETE ? 'selected' : '' ?>>Complete</option>
                        <option value="<?= REQUEST_INCOMPLETE ?>" <?= $product_request_detail['product_request_current_status'] == REQUEST_INCOMPLETE ? 'selected' : '' ?>>Incomplete</option>
                    </select>
                </div>
    
                <div class="mt-3">
                    <!--class="add_to_cart"-->
                    <button type="button" id="proceed-btn" class="btn btn-custom w-100" disabled="disabled" data-id="<?= $product_request_detail['product_id'] ?>" data-quantity="1" data-product_request_current_status="">
                        Save
                    </button>
                </div>
            </div>
        <?php else: ?>
            <small class="text-danger">Note: Send payment to escrow for the service provider to proceed and allow you to mark the service complete (when availed).</small>
        <?php endif; ?>
    <?php endif; ?>

</div>

<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENTID ?>&currency=USD&intent=authorize&disable-funding=paylater,credit,card"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>

    // const stripeTokenHandler = (token, formId) => {
    //     // Insert the token ID into the form so it gets submitted to the server
    //     const form = document.getElementById(formId);
    //     const hiddenInput = document.createElement('input');
    //     hiddenInput.setAttribute('type', 'hidden');
    //     hiddenInput.setAttribute('name', 'stripeToken');
    //     hiddenInput.setAttribute('value', token.id);
    //     form.appendChild(hiddenInput);
    //     console.log
    //     // Submit the form
    //     // form.submit();
    // }

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

    if(parseInt($('input[name=card-mount-status]').val())) {
        card.mount('#card-element');
        card.addEventListener('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
    }
    
    async function appendToken(formId) {
        return new Promise((resolve, reject) => {
            if(parseInt($('input[name=card-mount-status]').val())) {
                if(formId == 'requestorResponseForm') {
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
                            resolve(true)
                        }
                    });
                } else {
                    resolve(true)
                }
            } else {
                resolve(true)
            }
        })
    }
    
    $(document).ready(function() {
        
        $('body').on('submit', '.responseForm', function(event) {
            
            event.preventDefault();
            event.stopPropagation()

            formId = $(this).attr('id')
            
            appendToken(formId).then(
                function(success) {
                    if(success) {
                        if (!$('#' + formId)[0].checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                            $('#' + formId).addClass('was-validated');
                            $('#' + formId).find(":invalid").first().focus();
                            return false;
                        } else {
                            $('#' + formId).removeClass('was-validated');
                        }
            
                        $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
                        var data = $('#' + formId).serialize()
                        var url = base_url + 'dashboard/product/saveRequest'
                        AjaxRequest.asyncRequest(url, data).then(
                            function(response) {
                                if (response.status) {
                                    AdminToastr.success(response.message)
                                    $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                        $('[data-toggle="tooltip"]').tooltip({
                                            html: true,
                                        })
                                        if(parseInt($('input[name=card-mount-status]').val())) {
                                            card.mount('#card-element');
                                        }
                                    });
                                } else {
                                    AdminToastr.error(response.message ?? 'An error occurred while trying to process your request.')
                                }
                            }
                        )
                    }
                }
            )
        })

        $('#proceed-btn').attr('data-product_request_current_status', $('select[name="product_request[product_request_current_status]"]').val())
        if ($('select[name="product_request[product_request_current_status]"]').val() == '<?= REQUEST_INCOMPLETE ?>') {
            // if ($('#proceed-btn').hasClass('add_to_cart')) {
            //     $('#proceed-btn').removeClass('add_to_cart')
            // }
            $('#proceed-btn').html('Update')
            $('#proceed-btn').attr('disabled', false)
        } else if ($('select[name="product_request[product_request_current_status]"]').val() == '<?= REQUEST_COMPLETE ?>') {
            // if (!$('#proceed-btn').hasClass('add_to_cart')) {
                // $('#proceed-btn').addClass('add_to_cart')
            // }
            // $('#proceed-btn').html('Proceed to checkout')
            $('#proceed-btn').html('Pay')
            $('#proceed-btn').attr('disabled', false)
        } else if ($('select[name="product_request[product_request_current_status]"]').val() == '') {
            $('#proceed-btn').attr('disabled', true)
        }

        $('body').on('change', 'select[name="product_request[product_request_current_status]"]', function() {
            $('#proceed-btn').attr('data-product_request_current_status', $(this).val())
            if ($(this).val() == '<?= REQUEST_INCOMPLETE ?>') {
                // if ($('#proceed-btn').hasClass('add_to_cart')) {
                //     $('#proceed-btn').removeClass('add_to_cart')
                // }
                $('#proceed-btn').html('Update')
                $('#proceed-btn').attr('disabled', false)
            } else if ($(this).val() == '<?= REQUEST_COMPLETE ?>') {
                // if (!$('#proceed-btn').hasClass('add_to_cart')) {
                //     $('#proceed-btn').addClass('add_to_cart')
                // }
                // $('#proceed-btn').html('Proceed to checkout')
                $('#proceed-btn').html('Pay')
                $('#proceed-btn').attr('disabled', false)
            } else if ($(this).val() == '') {
                $('#proceed-btn').attr('disabled', true)
            }
        })

        // marking complete or incomplete
        $('body').on('click', '#proceed-btn', function() {
            if (!$(this).hasClass('add_to_cart') && $('select[name="product_request[product_request_current_status]"]').val() != '') {
                var data = {
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    'order_id': $('input[name=order_id]').val(),
                    'product_request_id': '<?= $product_request_detail['product_request_id'] ?>',
                    'product_request': {
                        'product_request_signup_id': '<?= $product_request_detail['product_request_signup_id'] ?>',
                        'product_request_product_id': '<?= $product_request_detail['product_id'] ?>',
                        'product_request_current_status': $(this).data('product_request_current_status')
                    }
                }
                
                if($('input[name=order_merchant]').val() == '<?= PAYPAL ?>') {
                    var url = base_url + 'dashboard/product/authorizeOrder'
                } else {
                    var url = base_url + 'dashboard/product/saveRequest'
                }
                
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
                            AdminToastr.success(response.message)
                            $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                $('[data-toggle="tooltip"]').tooltip({
                                    html: true,
                                })
                                if(parseInt($('input[name=card-mount-status]').val())) {
                                    card.mount('#card-element');
                                }
                            });
                        } else {
                            AdminToastr.error(response.message)
                        }
        		    }
    		    )
            }
        })

        $('body').on('submit', '#meetingRequestForm', function() {
            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'dashboard/meeting/saveMeetingRequest'

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
                        AdminToastr.success(response.txt)
                        $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                            $('.fancybox-close-small').trigger('click')
                            $('[data-toggle="tooltip"]').tooltip({
                                html: true,
                            })
                            if(parseInt($('input[name=card-mount-status]').val())) {
                                card.mount('#card-element');
                            }
                        });
                    } else {
                        AdminToastr.error(response.txt)
                    }
    		    }
		    )
        })
    })
    
    if($('#paypal-button-container').length) {
        paypal.Buttons({
            style: {
                layout: 'horizontal',
                color:  'silver',
                shape:  'rect',
                label:  'paypal',
                height: 35
            },
            async createOrder() {
                try {
                    var url = base_url + "dashboard/product/createOrder";
                    var data = {'_token': $('meta[name=csrf-token]').attr("content"), 'product_request_id': $('input[name=product_request_id]').val()}
    
                    var response = await AjaxRequest.asyncRequest(url, data)
                    $('input[name=order_id]').val(response.order_id);
                    return response.response.id
                } catch (error) {
                    console.error(error);
                }
            },
            onApprove: function(data) {

                // save order
                var url = base_url + "dashboard/product/saveOrder";
                var data = {
                    '_token': $('meta[name=csrf-token]').attr("content"), 
                    'order_id': $('input[name=order_id]').val(),
                    'orderID': data.orderID,
                    'payerID': data.payerID,
                    'paymentID': data.paymentID,
                    'facilitatorAccessToken': data.facilitatorAccessToken,
                }

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
        		        if(response.status) {
                            $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                                $('[data-toggle="tooltip"]').tooltip({
                                    html: true,
                                })
                                toastr.success(response.message)
                            });
                        } else {
                            toastr.error(response.message)
                        }
        		    }
    		    )
                // return fetch(url, {
                //     method: 'post',
                //     body: JSON.stringify(data)
                // })
                // .then(response => response.json())
                // .then((authorizePayload) => {
                //     const authorizationID = authorizePayload.authorizationID;
                //     toastr.success(`You have authorized this transaction. Order ID: ${data.orderID} Authorization ID: ${authorizationID}`);
                // });
            },
            onError: function (err) {
                console.log(err)
            }
        }).render('#paypal-button-container');
    }
</script>