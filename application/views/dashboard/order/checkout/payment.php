<div class="dashboard-content">
    <i class="fa-light fa-bank"></i>
    <h4><?= __('Payment') ?> </h4>
    <hr />
    <p class="text-danger">* Indicates required field</p>

    <div class="create-profile-form">

        <form id="payment-form" method="POST" action="javascript:;">
            <input type="hidden" name="_token" />
            <input type="hidden" name="order_id" value="<?= JWT::encode($order['order_id']) ?>" />
            <input type="hidden" name="order_reference_type" value="<?= $order['order_reference_type'] ?>" />

            <p>Payment Details</p>
            <div class="row">
                <div class="col-md-7">
                    <label for="card-element">
                        Credit or debit card <span class="text-danger">*</span>
                    </label>

                    <div id="card-element" class="form-control"></div>
                    <small class="text-danger font-12" id="card-errors" role="alert"></small>
                    
                    <!--<hr/>-->
                    <!--<div class="text-center">OR</div>-->
                    <!--<hr/>-->

                    <!--<div id="paypal-button-container"></div>-->

                </div>

                <div class="col-md-5 order">
                    <!-- <p>Your Order</p> -->
                    <div>
                        <h4 class="d-inline">Product<span class="float-right">Total</span></h4>
                    </div>
                    <hr />
                    <div>
                        <?php if(isset($order_item) && $order_item): ?>
                            <?php foreach ($order_item as $key => $value) : ?>
                                <?php $product = $this->model_product->find_by_pk($value['order_item_product_id']); ?>
                                <b class="d-inline">
                                    <a href="<?= l('dashboard/product/detail/' . $product['product_slug']) ?>" target="_blank">
                                        <?= $product['product_name'] ?>
                                    </a>
                                    <small>x</small> <?= $value['order_item_qty'] ?><span class="float-right"><?= price($value['order_item_qty'] * $value['order_item_price']) ?></span><br />
                                    <span class="font-12"><?= $value['order_item_qty'] . ' x ' . price($value['order_item_price']) ?></span>
                                </b><br/>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <hr />
                    
                    <div>
                        <h6 class="d-inline">Subtotal<span class="float-right"><?= price($order['order_amount']) ?></span></h6>
                    </div>

                    <div>
                        <b class="d-inline">Platform fee (<?= $order['order_fee'] > 0 ? number_format(($order['order_fee'] / $order['order_amount']) * 100, 0) : 0 ?>%)<span class="float-right"><?= price($order['order_fee']) ?></span></b>
                    </div>

                    <?php if($order['order_shipping']): ?>
                        <div>
                            <b class="d-inline">Estimated shipping fee (<?= $order['order_shipping'] > 0 ? number_format(($order['order_shipping'] / $order['order_amount']) * 100, 0) : 0 ?>%)<span class="float-right"><?= price($order['order_shipping']) ?></span></b>
                        </div>
                    <?php endif; ?>

                    <div>
                        <b class="d-inline">Tax
                            <span>
                                (<?= number_format(($order['order_tax'] / $order['order_amount']) * 100 , 1); ?>%)
                            </span>
                            <span class="float-right">
                                <?= price($order['order_tax']) ?>
                            </span>
                        </b>
                    </div>

                    <hr />

                    <div>
                        <b class="d-inline">Order Total<span class="float-right"><?= price($order['order_total']) ?></span></b>
                    </div>

                    <hr />

                    <button type="submit" id="payment-submit" class="btn btn-custom w-100">Pay Credit or debit card</button>

                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://www.paypal.com/sdk/js?client-id=<?= PAYPAL_CLIENTID ?>&currency=USD&intent=authorize&disable-funding=paylater,credit,card"></script>

<script>

    // Create a Stripe client.
    var stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY; ?>');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#000',
            lineHeight: '18px',
            fontSmoothing: 'antialiased',
            fontSize: '14px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {
        style: style
    });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        // Createtoken  = for simple payment
        stripe.createToken(card).then(function(result) {

            // Createresource  = for 3d
            //stripe.createSource(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);
        
                $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
        
                // Submit the form
                var data = $('#payment-form').serialize()
                var url = base_url + 'dashboard/order/paymentAction'

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
                            $('#payment-submit').attr('disabled', true)
                            $('#payment-submit').html('<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
                        },
                        complete: function() {
                            $('#payment-submit').attr('disabled', false)
                            $('#payment-submit').html('Pay Credit or debit card')
                        }
                    })
        		}).then(
        		    function(response) {
                        if (response.status) {
                            AdminToastr.success(response.txt);
                            location.href = response.redirect_url;
                        } else {
                            AdminToastr.error(response.txt ?? '<?= ERROR_MESSAGE ?>');
                        }
                    }
    		    )
            }
        });
    });

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
                    var url = base_url + "dashboard/order/createOrder";
                    var data = {'_token': $('meta[name=csrf-token]').attr("content"), 'order_id': $('input[name=order_id]').val()}
    
                    AjaxRequest.asyncRequest(url, data, false).then(
                        function(response) {
                            console.log(response);
                        }
                    )

                    // const response = await fetch(url, {
                    //     method: "POST",
                    //     headers: {
                    //         "Content-Type": "application/json",
                    //     },
                    //     body: JSON.stringify({
                    //         cart: [data],
                    //     }),
                    // });
                    
                    // const orderData = await response.json();
                    // return orderData.response.id
                } catch (error) {
                    console.error(error);
                }
            },
            onApprove: function(data) {
                var url = base_url + "dashboard/order/authorizeOrder";
                var data = {
                    '_token': $('meta[name=csrf-token]').attr("content"), 
                    'order_id': $('input[name=order_id]').val(),
                    'orderID': data.orderID,
                    'payerID': data.payerID,
                    'paymentID': data.paymentID,
                    'facilitatorAccessToken': data.facilitatorAccessToken,
                }
    
                return fetch(url, {
                    method: 'post',
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then((authorizePayload) => {
                    const authorizationID = authorizePayload.authorizationID;
                    toastr.success(`You have authorized this transaction. Order ID: ${data.orderID} Authorization ID: ${authorizationID}`);
                    // redirect
                });
            },
            onError: function (err) {
                console.log(err)
            }
        }).render('#paypal-button-container');
    }
    
</script>