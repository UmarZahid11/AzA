<!-- banner start -->

<section class="banner inner-banner">

    <!-- <img src="<?= g('images_root') ?>inner-banner.jpg" alt="">

<div class="baner-cnt"> -->

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Careers' ?>

                    </h1>

                </div>

            </div>
            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

    </div>

    <!-- </div> -->

</section>

<!-- banner end -->

<section class="career-sec">

    <div class="container">

        <div class="career-form">

            <div class="col-lg-12">
                <?php if (isset($cms[0]['cms_page_content'])) : ?>
                    <?= html_entity_decode($cms[0]['cms_page_content']) ?>
                <?php else : ?>
                    <h2>Careers</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor <br> incididunt ut labore et dolore magna aliqua.</p>
                <?php endif; ?>
            </div>

            <?php if ($this->model_signup->hasRole(ROLE_0)) : ?>

                <form action="javascript:;" class="careerForm" id="careerForm" method="POST" novalidate>
                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />

                    <div class="row">

                        <div class="col-12">
                            <h3>Basic Info</h3>
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control" placeholder="Job Title *" name="career[career_job_title]" maxlength="500" required />
                            <input type="hidden" class="form-control slug" name="career[career_slug]" maxlength="500" />
                        </div>

                        <div class="col-md-6">

                            <select class="form-select" name="career[career_category]" required>
                                <?php if (isset($job_category) && count($job_category) > 0) : ?>
                                    <option value="">Select category *</option>
                                    <?php foreach ($job_category as $key => $value) : ?>
                                        <option value="<?= $value['job_category_name'] ?>"><?= $value['job_category_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                        </div>

                        <div class="col-md-6">

                            <select class="form-select" name="career[career_job_type]" required>
                                <?php if (isset($job_type) && count($job_type) > 0) : ?>
                                    <option value="">Select job type *</option>
                                    <?php foreach ($job_type as $key => $value) : ?>
                                        <option value="<?= $value['job_type_name'] ?>"><?= $value['job_type_name'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                        </div>

                        <div class="col-md-6">
                            <input type="date" class="form-control" id="datePickerId" placeholder="Application deadline DD/MM/YYYY *" name="career[career_application_deadline]" required />
                        </div>

                        <div class="col-md-6">
                            <select class="form-select" name="career[career_salary_currency]">
                                <?php if (isset($currency) && count($currency) > 0) : ?>
                                    <option value="">Select salary currency</option>
                                    <?php foreach ($currency as $key => $value) : ?>
                                        <option value="<?= $value['currency_code'] ?>"><?= $value['currency_code'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <textarea placeholder="Job description *" class="form-control" name="career[career_description]" maxlength="1000" required></textarea>
                        </div>

                        <div class="col-12">

                            <h3>Company Information</h3>

                        </div>

                        <div class="col-12">

                            <input type="text" class="form-control" placeholder="Company name" name="career[career_company_name]" maxlength="500" />

                        </div>

                        <div class="col-md-6">

                            <input type="url" class="form-control" placeholder="Company website" name="career[career_website]" />

                        </div>

                        <div class="col-md-6">

                            <input type="text" class="form-control" placeholder="Company industry" name="career[career_industry]" maxlength="500" />

                        </div>

                        <div class="col-md-6">

                            <input type="url" class="form-control" placeholder="Facebook page (Link)" name="career[career_facebook]" />

                        </div>

                        <div class="col-md-6">

                            <input type="url" class="form-control" placeholder="Linkedin page (Link)" name="career[career_linkedin]" />

                        </div>

                        <div class="col-md-6">

                            <input type="url" class="form-control" placeholder="Twitter page (Link)" name="career[career_twitter]" />

                        </div>

                        <div class="col-md-6">

                            <input type="url" class="form-control" placeholder="Instagram page (Link)" name="career[career_instagram]" />

                        </div>

                        <div class="col-md-12">

                            <textarea class="form-control" placeholder="Company Description" name="career[career_company_description]"></textarea>

                        </div>

                        <div class="col-12 logo-wrp">

                            <h3>Logo (optional)</h3> <br>

                            <label>Select Image: <small>Maximum file size: </small> 2 MB</label> <br>

                            <input type="file" name="career_company_logo" />

                        </div>

                        <div class="col-12">

                            <h3>Recruiter Information</h3>

                        </div>

                        <div class="col-md-6">

                            <input type="text" class="form-control" placeholder="Company name" name="career[career_recruiter_name]" maxlength="500" />

                        </div>

                        <div class="col-md-6">

                            <input type="text" class="form-control" placeholder="Company business" name="career[career_recruiter_business]" maxlength="500" />

                        </div>

                        <?php if(defined('CAPTCHA_SITE_KEY')): ?>
                            <div class="g-recaptcha" data-sitekey="<?= CAPTCHA_SITE_KEY ?>"></div>
                            <script src="https://www.google.com/recaptcha/api.js"></script>
                        <?php endif; ?>

                        <div class="col-12 mt-2">

                            <label>
                                <input type="checkbox" class="terms-check" />

                                <span>Accept <a href="<?= l('terms-and-conditions') ?>" target="_blank"> Terms & conditions</a> and <a href="<?= l('privacy') ?>" target="_blank"> Privacy policy</a></span>
                            </label>
                        </div>

                        <div class="col-12 mt-4">

                            <button type="submit" class="btn btn-custom submitBtn">Submit</button>

                        </div>

                    </div>

                </form>

                <hr />
            <?php endif; ?>

            <?php if (isset($career) && count($career) > 0) : ?>
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="job-list">
                                <?php foreach ($career as $key => $value) : ?>
                                    <li class="job-preview">
                                        <a data-fancybox data-animation-duration="700" data-src="#careerModal<?= $key ?>" href="javascript:;">
                                            <div class="content float-left">
                                                <h4 class="job-title">
                                                    <?= $value['career_job_title'] ?>
                                                </h4>
                                                <h5 class="company">
                                                    <p><?= $value['career_category'] ?></p>
                                                    <p><?= $value['career_job_type'] ?></p>
                                                </h5>
                                            </div>
                                        </a>
                                    </li>

                                    <div class="grid">
                                        <div style="display: none;" id="careerModal<?= $key ?>" class="animated-modal">
                                            <h4><?= $value['career_job_title'] ?></h4>
                                            <?php if($value['career_company_logo']): ?>
                                                <img src="<?= get_image($value['career_company_logo_path'], $value['career_company_logo']) ?>" onerror="this.onerror=null;this.src='<?=g('images_root').'dummy-image.png'?>';" />
                                            <?php endif; ?>
                                            <p>Category: <?= $value['career_category'] ?? NA ?></p>
                                            <p>Job type: <?= $value['career_job_type'] ?? NA ?></p>
                                            <p>Application deadline: <?= date('d M, Y', strtotime($value['career_application_deadline'])) ?></p>
                                            <p>Salary currency: <?= $value['career_salary_currency'] ?? NA ?></p>
                                            <p>Description: <?= $value['career_description'] ?? NA ?></p>
                                            <p>Company: <?= $value['career_company_name'] ?? NA ?></p>
                                            <p>Website: <?= $value['career_website'] ?? NA ?></p>
                                            <p>Facebook: <?= $value['career_facebook'] ?? NA ?></p>
                                            <p>Linkedin: <?= $value['career_linkedin'] ?? NA ?></p>
                                            <p>Twitter: <?= $value['career_twitter'] ?? NA ?></p>
                                            <p>Instagram: <?= $value['career_instagram'] ?? NA ?></p>
                                            <p>Comapny description: <?= $value['career_company_description'] ?? NA ?></p>
                                            <p>Recruiter: <?= $value['career_recruiter_name'] ?? NA ?></p>
                                            <p>Recruiter business: <?= $value['career_recruiter_business'] ?? NA ?></p>
                                            <a href="mailto:<?= g('db.admin.email') ?>">Apply</a>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>

                <?php if (isset($career_count) && ($career_count) > 0) : ?>
                    <div class="row">
                        <div class="col-lg-12">

                            <nav aria-label="Page navigation example mt-5">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php if ($page <= 1) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" href="<?php if ($page <= 1) {
                                                                                    echo '#';
                                                                                } else {
                                                                                    echo l('career/index/') . $prev . '/' . $limit;
                                                                                } ?>"><i class="far fa-chevron-left"></i></a>
                                    </li>

                                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                        <li class="page-item <?php if ($page == $i) {
                                                                    echo 'active';
                                                                } ?>">
                                            <a class="page-link" href="<?= l('career/index/') . $i . '/' . $limit; ?>"> <?= $i; ?> </a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item <?php if ($page >= $totalPages) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                                    echo '#';
                                                                                } else {
                                                                                    echo l('career/index/') . $next . '/' . $limit;
                                                                                } ?>"><i class="far fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="container">
                    <small>No career opportunites available.</small>
                </div>
            <?php endif; ?>

        </div>

    </div>

</section>

<script>
    $(document).ready(function() {

        if(document.getElementById('datePickerId')) {
            datePickerId.min = new Date(Date.now() + (3600 * 1000 * 24)).toISOString().split("T")[0];
        }

        // TERMS CHECKBOX CHECK!
        if ($('.terms-check').is(':checked')) {
            $('.submitBtn').prop('disabled', false)
        } else {
            $('.submitBtn').prop('disabled', true)
        }

        $('.terms-check').on('change', function() {
            if ($(this).is(':checked')) {
                $('.submitBtn').prop('disabled', false)
            } else {
                $('.submitBtn').prop('disabled', true)
            }
        })

        // SLUG GENERATOR
        $('input[name="career[career_job_title]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(convertToSlug($(this).val()))
        })

        function convertToSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }


        // SUBMIT FORM
        $('.careerForm').submit(function() {
            if (!$('.careerForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('.careerForm').addClass('was-validated');
                setTimeout(function() {
                    Loader.hide();
                }, 100);
                $('.careerForm').find(":invalid").first().focus();
                return false;
            } else {
                $('.careerForm').removeClass('was-validated');
            }

            var data = new FormData(document.getElementById('careerForm'));
            var url = base_url + 'career/save';
            var type = 'json'

            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                dataType: type,
                async: true,
                success: function(response) {
                    Loader.hide();
                    if (response.status == 0) {
                        AdminToastr.error(response.txt, 'Error');
                    } else if (response.status == 1) {
                        AdminToastr.success(response.txt);
                        $('.careerForm').each(function() {
                            this.reset();
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Loader.hide();
                    AdminToastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown, 'Error');
                },
                beforeSend: function() {
                    Loader.show();
                }
            });
            return false;
        })
    })
</script>