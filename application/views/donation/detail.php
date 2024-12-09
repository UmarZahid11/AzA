<section class="causes-details-area style-1">
    <div class="container">
        <div class="row">
            <?php if(isset($fundraising)) : ?>
                <div class="col-lg-4">
                    <div class="author-profile">
                        <div class="author-img">
                            <img src="<?= get_image($fundraising['fundraising_attachment_path'], $fundraising['fundraising_attachment']) . '1' ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/225x225?text=<?= $fundraising['fundraising_title'] ?>';" />
                        </div>
                        <div class="content">
                            <div class="details-inner">
                                <h1 class="author-name"> <?= $fundraising['fundraising_title'] ?> </h1>
                                <p class="desc"><?= $fundraising['fundraising_desc'] ?></p>
                            </div>
                            <div class="goals_actions-inner">
                                <div class="goals_actions-content">
                                    <div class="actions">
                                        <h1>Raised</h1>
                                        <h2>$ <span class="counter"><?= ($donation) ?></span></h2>
                                    </div>
                                    <div class="divider">
                                        <div class="outline"></div>
                                    </div>
                                    <div class="goals">
                                        <h1>Goals</h1>
                                        <h2>$ <span class="counter"><?= ((int) $fundraising['fundraising_amount']) ?></span> </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tags-content">
                        <div class="tags_share-inner">
                            <div class="share-link-inner">
                                <h1>Share On:</h1>
                                <div class="all-share-link">
                                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url=<?= l('donation/detail/' . $fundraising['fundraising_slug']) ?>">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </a>
                                    <a target="_blank" href="https://twitter.com/intent/tweet?text=<?= l('donation/detail/' . $fundraising['fundraising_slug']) ?>">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?= l('donation/detail/' . $fundraising['fundraising_slug']) ?>">
                                        <i class="fa-brands fa-facebook-f"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-lg-4">
                    <div class="author-profile">
                        <div class="author-img">
                            <img 
                                class="lazy" 
                                src="data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%200%200'%3E%3C/svg%3E" 
                                data-src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" 
                                onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" 
                                alt="" 
                            />
                        </div>
                        <div class="content">
                            <div class="details-inner">
                                <h1 class="author-name"> <?= g('db.admin.title') ?> </h1>
                            </div>
                            <div class="goals_actions-inner">
                                <div class="goals_actions-content">
                                    <div class="actions">
                                        <h1>Raised</h1>
                                        <h2>$ <span class="counter"><?= ($donation) ?></span></h2>
                                    </div>
                                    <div class="divider">
                                        <div class="outline"></div>
                                    </div>
                                    <div class="goals">
                                        <h1>Goals</h1>
                                        <h2>$ <span class="counter"><?= (g('db.admin.target')) ?></span> </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="col-lg-8">
                <form id="donationForm" method="POST" action="javascript:;" novalidate>
                    <input type="hidden" name="_token" />
                    <input type="hidden" name="merchant" value="<?= STRIPE ?>" />
                    <div>
                        <div id="give-form-721-wrap" class="give-form-wrap give-display-onpage">
                            <p class="typewrite form-title" data-period="1500" data-type="[&quot;Be a Change Maker&quot;,&quot;Invest in the Future&quot;,&quot;Support Our Mission&quot;]">
                                <span class="wrap">MAKE</span>
                            </p>
                            <div class="give-goal-progress">
                                <div class="raised">
                                    <?= price($donation) ?>  <span> of <?= isset($fundraising) ? (price($fundraising['fundraising_amount'])) : (price(g('db.admin.target'))) ?> raised</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="give-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="55.74">
                                        <span style="width:<?= $donation_percentage ?>%; background-blend-mode: multiply;"></span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="give-total-wrap">
                                <div class="give-donation-amount form-row-wide">
                                    <span class="give-currency-symbol give-currency-position-before">$</span>
                                    <input class="give-text-input give-amount-top" id="give-amount" name="give-amount" type="text" inputmode="decimal" placeholder="" value="1" autocomplete="off" data-amount="25">
                                </div>
                            </div> -->
                            <ul>
                                <li>
                                    <label>
                                        <input type="radio" name="donationAmountRadio" value="10" data-default="0" /> <span>$10</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="donationAmountRadio" value="25" data-default="0" /> <span>$25</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="donationAmountRadio" value="50" data-default="0" /> <span>$50</span>
                                    </label>
                                </li>
                                <li>
                                    <label>
                                        <input type="radio" name="donationAmountRadio" value="custom" /> <span>Set Amount</span>
                                    </label>
                                    <input type="number" name="customDonationAmount" min="1" class="form-control d-none" />
                                </li>
                            </ul>
                            <div id="give_purchase_form_wrap">
                                <?php if(isset($fundraising)) : ?>
                                    <input type="hidden" name="fundraising" value="<?= $fundraising['fundraising_id'] ?>" />
                                <?php endif; ?>
                                <legend> Personal Info </legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Donate: </label>
                                        <input type="number" class="form-control" name="donationAmount" value="0" min="1" readonly required />
                                        <span class="invalid-feedback">Select a valid donation amount.</span>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="donationEmail" placeholder="Email address" value="<?= $this->userid > 0 ? $this->user_data['signup_email'] : '' ?>" required="" />
                                    </div>
                                </div>
                                <div class="d-flex mt-4" style="gap: 20px;">
                                    <p>Pay with: </p>
                                    <!-- <input type="submit" id="paypal-donate-button" value="<?//= PAYPAL ?>" /> -->
                                    <!-- <div id="paypal-donate-button-container"></div> -->
                                    <input type="submit" id="stripe-donate-button" value="<?= STRIPE ?>" />
                                    <?php if($this->userid) : ?>
                                        <?php if($this->plaid_token) : ?>
                                            OR
                                            <input type="submit" id="plaid-donate-button" value="<?= PLAID ?>" />
                                        <?php else: ?>
                                            OR
                                            <a href="<?= l('plaid/link/auth ') ?>">Connect plaid to for ach transfers</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <!--<a href="https://www.paypal.com/donate/?hosted_button_id=FD2NW5EPHT92Y">-->
                <!--    <img src="https://www.paypalobjects.com/webstatic/en_US/i/btn/png/btn_buynow_107x26.png" alt="Buy Now" item_number="1" />-->
                <!--</a>-->
            </div>
        </div>
    </div>
</section>

<!-- <div class="donors_area_wrapper">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-12">
                <h1>Respectable Donors</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit proin mi pellentesque lorem turpis feugiat non sed sed sed aliquam lectus
                    sodales gravida turpis maassa odio. we'll explore the importance of design in the software development process and how it
                    can lead to more successful projects.</p>
            </div>
            <div class="col">
                <div class="donor-profile">
                    <div class="image-wrapper">
                        <a href="#"><img decoding="async" src="https://azaverze.com/stagging/assets/front_assets/images/newauthor2.jpg" alt="Donor"></a>
                    </div>
                    <div class="donor-details">
                        <h6 class="name"> <a href="#">Jacob Smith</a></h6>
                        <p>May 12, 2024</p>
                    </div>
                    <div class="divider"></div>
                    <div class="donate-amount">
                        <p>Donated Amount:</p>
                        <h5>$<span class="counter">160.00</span></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<script src="https://www.paypalobjects.com/donate/sdk/donate-sdk.js" charset="UTF-8"></script>

<script>
    PayPal.Donation.Button({
        env: 'sandbox',
        hosted_button_id: 'NP67PFUAHRUUU',
        // business: 'YOUR_EMAIL_OR_PAYERID',
        image: {
            src: 'https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif',
            title: 'PayPal - The safer, easier way to pay online!',
            alt: 'Donate with PayPal button'
        },
        onComplete: function(params) {
            // {
            //     "tx": "58U19164RC757080T",
            //     "st": "Completed",
            //     "amt": "1.00",
            //     "cc": "USD",
            //     "cm": "",
            //     "item_number": "",
            //     "item_name": ""
            // }
            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: base_url + 'donation/save',
                    type: "POST",
                    data: { 'donationAmount': params.amt, 'donationEmail': $('input[name=donationEmail]').val(), 'txId': params.tx, 'status': params.st, 'params': params, 'merchant': '<?= PAYPAL ?>'},
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        Loader.show()
                    },
                    complete: function() {
                        Loader.hide()
                    }
                })
            }).then(
                function(response) {
                    if(response.status) {
                        toastr.success(response.txt)
                    } else {
                        toastr.error(response.txt)
                    }
                }
            )
        },
    }).render('#paypal-donate-button-container');

    $(document).ready(function() {
        $('input[name=donationAmountRadio]').on('change', function() {
            if($(this).val() == 'custom') {
                $('input[name=customDonationAmount]').removeClass('d-none')
                $('input[name=donationAmount]').val('')
            } else {
                $('input[name=customDonationAmount]').addClass('d-none')
                $('input[name=donationAmount]').val($(this).val())
            }
        })

        $('input[name=customDonationAmount]').on('input keyup change', function() {
            $('input[name=donationAmount]').val($(this).val())
        })

        $('#donationForm').on('submit', function() {

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            if($('input[name=donationAmount]').val() == 0) {
                $('.invalid-feedback').show()
                return false
            } else {
                $('.invalid-feedback').hide()
            }

            //
            $('input[name=merchant]').val(($(document.activeElement).val()))
            $('input[name=_token]').val($('meta[name="csrf-token"]').attr('content'))
            //
            var data = $(this).serialize()
            var url  = base_url + 'donation/save';

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
                        Loader.show()
                    },
                    complete: function() {
                        Loader.hide()
                    }
                })
            }).then(
                function(response) {
                    if(response.status) {
                        if(response.url) {
                            location.href = response.url
                        } else if(response.id && response.transfer_status) {
                            toastr.success('An ach transfer has been created with status: ' + response.transfer_status)
                        } else {
                            toastr.error('<?= ERROR_MESSAGE ?>')    
                        }
                    } else {
                        toastr.error(response.txt)
                    }
                }
            )
        })
    })
</script>