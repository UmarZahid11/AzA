<!--<section class="banner inner-banner">-->
<!--    <div class="container">-->
<!--        <div class="row justify-content-center">-->
<!--            <div class="col-lg-6">-->
<!--                <div class="banner-cont inner-banner-text wow fadeInLeft">-->
<!--                    <h1>-->
<!--                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Contact Us' ?>-->
<!--                    </h1>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-lg-6">-->
<!--                <div class="inner-banner">-->
<!--                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>"-->
<!--                        onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--</section>-->

<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="contact-sec ptb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                <div class="contact-left p-5 position-relative">
                    <div class="dull-overlay"></div>

                    <?php if (isset($cms[0]['cms_page_content'])) : ?>
                    <?= html_entity_decode($cms[0]['cms_page_content']) ?>
                    <?php else : ?>
                    <h4 class="text-white">Get in Touch</h4>
                    <p class="text-white">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                        eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
                        enim ad minim veniam, quis nostrud exercitation ullamco.
                    </p>
                    <?php endif; ?>

                    <a href="mailto:<?= g('db.admin.email') ?>" class="contact-list">
                        <div class="circle-contact">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>

                        <div>
                            <h5>Email</h5>

                            <p><?= g('db.admin.email') ?></p>
                        </div>
                    </a>

                    <a href="tel:<?= g('db.admin.phone_local') ?>" class="contact-list">
                        <div class="circle-contact">
                            <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h5>Phone</h5>

                            <p><?= g('db.admin.phone_local') ?></p>
                        </div>
                    </a>

                    <a href="https://maps.google.com/?q=<?= g('db.admin.address') ?>" target="_blank"
                        class="contact-list">
                        <div class="circle-contact">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>
                            <h5>Address</h5>
                            <p><?= g('db.admin.address') ?></p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                <form class="inquiry_form contact-right p-5" action="javascript:;" method="POST" novalidate>
                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />

                    <h4>Contact Us</h4>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" class="form-control" name="inquiry[inquiry_fullname]" placeholder="Name"
                                required pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" minlength="3"
                                maxlength="100" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="text" class="form-control" name="inquiry[inquiry_address]"
                                placeholder="Address" required minlength="5" id="address" maxlength="255" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <input type="email" class="form-control" name="inquiry[inquiry_email]" placeholder="Email"
                                required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <textarea class="form-control" name="inquiry[inquiry_comments]" placeholder="Message"
                                minlength="100" required></textarea>
                        </div>
                    </div>

                    <?php if(defined('CAPTCHA_SITE_KEY')): ?>
                        <div class="g-recaptcha mt-3" data-sitekey="<?= CAPTCHA_SITE_KEY ?>"></div>
                        <script src="https://www.google.com/recaptcha/api.js"></script>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn-1 bg-lightblue btn text-white mt-4" id="inquiry_form_btn">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>

    //
    $(function() {
        $("#address").autocomplete({
            source: function(request, response) {
                $.getJSON(base_url + 'job/mapbox', {
                        _token: '<?= $this->csrf_token ?>',
                        term: request.term
                    },
                    response);
            },
            select: function(event, ui) {
                event.preventDefault();
                $("#address").val(ui.item.id);
            }
        });
    });

    $(document).ready(function() {
        $('.inquiry_form').submit(function() {
            if (!$('.inquiry_form')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.inquiry_form').addClass('was-validated');
                $('.inquiry_form').find(":invalid").first().focus();
                return false;
            } else {
                $('.inquiry_form').removeClass('was-validated');
            }
            
            var inquiry_form_btn = '#inquiry_form_btn'
            var data = $('.inquiry_form').serialize();
            var url = base_url + 'contact/save';

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
                        $(inquiry_form_btn).attr('disabled', true)
                        $(inquiry_form_btn).html('Submitting ...')
                    },
                    complete: function() {
                        $(inquiry_form_btn).attr('disabled', false)
                        $(inquiry_form_btn).html('Submit')
                    }
                })
			}).then(
			    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt);
                        $('.inquiry_form').each(function() {
                            this.reset();
                        });
                    } else {
                        AdminToastr.error(response.txt);
                    }
			    }
		    )
        })
    })
</script>