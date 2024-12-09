<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/css/intlTelInput.css">
<style>
    .iti {
        width: 100%;
    }
</style>
<div class="dashboard-content">
    <i class="fa-regular fa-building"></i>
    <h4><?= __('Create Company Profile') ?></h4>
    <hr />
    <a href="<?= l(TUTORIAL_PATH . COMPANY_PROFILE_TUTORIAL) ?>" target="_blank"><i class="fa fa-film"></i> Company Profile Tutorial</a>
    <hr />

    <div class="create-profile-form">
        <div class="avatar-upload">
            <div class="avatar-edit">
                <form action="javascript:void(0)" method="post" id="form-profile-image">
                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                    <input type="file" name="file" id="profileUpload" accept="image/*" />
                    <label for="profileUpload"></label>
                </form>
                <button class="trash_img trash_company_img" data-toggle="tooltip" data-bs-placement="right" title="<?= __('Delete company profile picture.') ?>"><i class="fa fa-trash"></i></button>
            </div>

            <input type="hidden" name="signup_company_image" value="<?= (isset($this->user_data['signup_company_image']) && $this->user_data['signup_company_image'] != "") ? $this->user_data['signup_company_image'] : '' ?>" />

            <div class="avatar-preview">
                <?php if (isset($this->user_data['signup_company_image']) && $this->user_data['signup_company_image'] != "") : ?>
                    <div id="imagePreview" style="background-image: url(<?= get_image($this->user_data['signup_company_image_path'], $this->user_data['signup_company_image']) ?>);">
                    </div>
                <?php else : ?>
                    <div id="imagePreview" style="background-image: url(<?= g('dashboard_images_root') ?>upload-img.jpg);">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <small class="text-danger font-11">Note: <?= str_replace('{height}', '300', str_replace('{width}', '300', UPLOAD_GUIDELINES_PROFILE_IMAGE)) ?></small>

        <form class="profileForm" id="profileForm" method="POST" action="javascript:;" novalidate>
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <input type="hidden" name="signup_company[signup_company_signup_id]" value="<?= $this->userid ?>" />
            <div class="row">

                <div class="col-12 mt-4">
                    <h5><?= __('Company Representative') ?></h5>
                </div>
                <div class="col-md-6">
                    <label><?= __('Name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Complete Company Representative Name" name="signup_company[signup_company_representative_name]" required value="<?= isset($this->user_data['signup_company_representative_name']) ? $this->user_data['signup_company_representative_name'] : (isset($this->user_data['signup_firstname']) && isset($this->user_data['signup_lastname']) ? ($this->user_data['signup_firstname'] . ' ' . $this->user_data['signup_lastname']) : '') ?>" minlength="3" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Email') ?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="info@domain.com" name="signup_company[signup_company_representative_email]" required value="<?= isset($this->user_data['signup_company_representative_email']) ? $this->user_data['signup_company_representative_email'] : '' ?>" maxlength="500" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Phone') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="phone form-control" id="phone" name="signup_company[signup_company_representative_phone]" required value="<?= isset($this->user_data['signup_company_representative_phone']) ? $this->user_data['signup_company_representative_phone'] : '' ?>" maxlength="20" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Designation') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Complete Company Representative Designation" name="signup_company[signup_company_representative_designation]" required value="<?= isset($this->user_data['signup_company_representative_designation']) ? $this->user_data['signup_company_representative_designation'] : '' ?>" minlength="3" maxlength="100" pattern="([a-zA-Z',.-]+( [a-zA-Z',.-]+)*){3,100}" />
                </div>

                <div class="col-12 mt-4">
                    <h5><?= __('Company details') ?></h5>
                </div>
                <div class="col-md-6">
                    <label><?= __('Name') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" placeholder="Enter Company Name" name="signup_company[signup_company_name]" required value="<?= isset($this->user_data['signup_company_name']) ? $this->user_data['signup_company_name'] : '' ?>" maxlength="500" />
                    <input type="hidden" class="slug" name="signup_company[signup_company_slug]" value="<?= isset($this->user_data['signup_company_slug']) ? $this->user_data['signup_company_slug'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Email') ?> <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" placeholder="info@domain.com" name="signup_company[signup_company_email]" required value="<?= isset($this->user_data['signup_company_email']) ? $this->user_data['signup_company_email'] : '' ?>" maxlength="500" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Phone') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="phone2 form-control" id="phone2" name="signup_company[signup_company_phone]" required value="<?= isset($this->user_data['signup_company_phone']) ? $this->user_data['signup_company_phone'] : '' ?>" maxlength="20" />
                </div>

                <div class="col-md-6">
                    <label><?= __('Type') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="signup_company[signup_company_type]" required>
                        <?php if (isset($organization_type) && count($organization_type) > 0) : ?>
                            <?php foreach ($organization_type as $key => $value) : ?>
                                <option value="<?= $value['organization_type_name'] ?>" <?= isset($this->user_data['signup_company_type']) && $this->user_data['signup_company_type'] == $value['organization_type_name'] ? 'selected' : '' ?>><?= $value['organization_type_name'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label><?= __('Industry') ?> <span class="text-danger">*</span></label>
                    <select class="form-select" name="signup_company[signup_company_industry]" id="company_industry" required>
                        <option value="">Select Industry</option>
                        <?php foreach ($job_category as $key => $value) : ?>
                            <option value="<?= $value['job_category_name'] ?>" <?= isset($this->user_data['signup_company_industry']) && $this->user_data['signup_company_industry'] ==  $value['job_category_name'] ? 'selected' : '' ?>><?= $value['job_category_name'] ?></option>
                        <?php endforeach; ?>
                        <option value="other" <?= (isset($this->user_data['signup_company_industry']) && $this->user_data['signup_company_industry'] != "" && !in_array($this->user_data['signup_company_industry'], $job_category_array)) ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="col-md-6 other_industry <?= (isset($this->user_data['signup_company_industry']) && $this->user_data['signup_company_industry'] != "" && !in_array($this->user_data['signup_company_industry'], $job_category_array)) ? '' : 'd-none' ?>">
                    <label><?= __('Specify Industry') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control other_industry_input" name="signup_company[signup_company_industry]" value="<?= isset($this->user_data['signup_company_industry']) ? $this->user_data['signup_company_industry'] : '' ?>" maxlength="100" <?= (isset($this->user_data['signup_company_industry']) && $this->user_data['signup_company_industry'] != "" && !in_array($this->user_data['signup_company_industry'], $job_category_array)) ? '' : 'disabled' ?> />
                </div>

                <div class="col-md-6">
                    <label><?= __('Revenue generated (per year)') ?></label>
                    <input type="number" class="form-control" placeholder="Enter Revenue (per year)" name="signup_company[signup_company_revenue]" value="<?= isset($this->user_data['signup_company_revenue']) ? $this->user_data['signup_company_revenue'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Founded on') ?></label>
                    <select class="form-select" name="signup_company[signup_company_founded]">
                        <option value="">Select year of foundation of company </option>
                        <?php for ($i = 1800; $i <= date('Y'); $i++) : ?>
                            <option value="<?= $i ?>" <?= isset($this->user_data['signup_company_founded']) && $this->user_data['signup_company_founded'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label><?= __('Company size (Employee number)') ?></label>
                    <input type="number" class="form-control" placeholder="Enter Company Size" name="signup_company[signup_company_size]" value="<?= isset($this->user_data['signup_company_size']) ? $this->user_data['signup_company_size'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Open to opportunities and collaborations') ?></label>
                    <select class="form-select" name="signup_company[signup_company_open_to_opportunity]">
                        <option value="1" <?= isset($this->user_data['signup_company_open_to_opportunity']) && $this->user_data['signup_company_open_to_opportunity'] == 1 ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($this->user_data['signup_company_open_to_opportunity']) && $this->user_data['signup_company_open_to_opportunity'] == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label><?= __('Hiring') ?></label>
                    <select class="form-select" name="signup_company[signup_company_hiring]">
                        <option value="1" <?= isset($this->user_data['signup_company_hiring']) && $this->user_data['signup_company_hiring'] == 1 ? 'selected' : '' ?>>Yes</option>
                        <option value="0" <?= isset($this->user_data['signup_company_hiring']) && $this->user_data['signup_company_hiring'] == 0 ? 'selected' : '' ?>>No</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label><?= __('Location') ?> <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="address" placeholder="Enter location" autocomplete="off" name="signup_company[signup_company_location]" required value="<?= isset($this->user_data['signup_company_location']) ? $this->user_data['signup_company_location'] : '' ?>" maxlength="1000" />
                </div>

                <div class="col-12">
                    <label><?= __('Detail of work function at your company') ?> <span class="text-danger">*</span></label>
                    <div>
                        <textarea class="ckeditor form-control" id="editor" name="signup_company[signup_company_detail]" contenteditable="true"><?= isset($this->user_data['signup_company_detail']) ? $this->user_data['signup_company_detail'] : 'Company Details' ?></textarea>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <h5><?= __('Social accounts') ?></h5>
                </div>
                <div class="col-md-6">
                    <label><?= __('Website') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_website]" value="<?= isset($this->user_data['signup_company_website']) ? $this->user_data['signup_company_website'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Facebook') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_facebook]" value="<?= isset($this->user_data['signup_company_facebook']) ? $this->user_data['signup_company_facebook'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Twitter') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_twitter]" value="<?= isset($this->user_data['signup_company_twitter']) ? $this->user_data['signup_company_twitter'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Youtube') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_youtube]" value="<?= isset($this->user_data['signup_company_youtube']) ? $this->user_data['signup_company_youtube'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Vimeo') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_vimeo]" value="<?= isset($this->user_data['signup_company_vimeo']) ? $this->user_data['signup_company_vimeo'] : '' ?>" />
                </div>
                <div class="col-md-6">
                    <label><?= __('Linkedin') ?> </label>
                    <input type="url" class="form-control" placeholder="" name="signup_company[signup_company_linkedin]" value="<?= isset($this->user_data['signup_company_linkedin']) ? $this->user_data['signup_company_linkedin'] : '' ?>" />
                </div>

                <div class="col-12 mt-4">
                    <button class="btn btn-custom" id="profileFormBtn"><?= __('Save') ?></button>
                </div>
            </div>
        </form>
        <hr />
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/intlTelInput.min.js"></script>

<script>
    
    async function saveCompany() {
        //
        var data = $("#profileForm").serialize();
        var url = "<?php echo l('dashboard/company/update'); ?>";
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
                beforeSend: function() {
                    $('#profileFormBtn').attr('disabled', true)
                    $('#profileFormBtn').html('Saving ...')
                },
                complete: function() {
                    $('#profileFormBtn').attr('disabled', false)
                    $('#profileFormBtn').html('Save')
                }
            });
        })
    }

    $(document).ready(function() {

        //
        if ($('input[name=signup_company_image]').val() == '') {
            $('.trash_company_img').addClass('d-none')
        }

        function generateSlug(Text) {
            return Text.toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        // PHONE MASK //
        // intlTelInput
        const input = document.querySelector("#phone");
        const telInput = intlTelInput(input, {
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
            initialCountry: 'us',
            separateDialCode: false,
            nationalMode: false,
            autoHideDialCode: true,
        });
        const input2 = document.querySelector("#phone2");
        const telInput2 = intlTelInput(input2, {
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.1.0/build/js/utils.js',
            initialCountry: 'us',
            separateDialCode: false,
            nationalMode: false,
            autoHideDialCode: true,
        });

        /**
         * Method dynamicMask
         *
         * @param {string} placeholder
         *
         * @return void
         */
        function dynamicMask(placeholder, selector) {
            if (placeholder != "" && placeholder != undefined) {
                var dynamoMask = placeholder.replace(/[0-9]/g, 0);
                $(selector).mask(dynamoMask)
            } else {
                // call after 0.1 s
                setTimeout(function() {
                    var placeholder = $(selector).attr('placeholder')
                    dynamicMask(placeholder, selector)
                }, 100)
            }
        }

        // dyanmic mask on load
        dynamicMask($("#phone").attr('placeholder'), '#phone');
        dynamicMask($("#phone2").attr('placeholder'), '#phone2');

        // dyanmic mask on change
        $('#phone').on("countrychange", function(event) {
            dynamicMask($("#phone").attr('placeholder'), '#phone');
        })
        $('#phone2').on("countrychange", function(event) {
            dynamicMask($("#phone2").attr('placeholder'), '#phone2');
        })
        // PHONE MASK //

        //
        $('.phone').on('keyup keydown change focus', function() {
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                error = true;
                $('.phone').addClass('force-invalid');
            } else {
                $('.phone').removeClass('force-invalid');
            }
        })
        $('.phone2').on('keyup keydown change focus', function() {
            if ($('.phone2').val() == "" || !($.trim($('#phone2').val())) || !telInput2.isValidNumber()) {
                error = true;
                $('.phone2').addClass('force-invalid');
            } else {
                $('.phone2').removeClass('force-invalid');
            }
        })
        //

        //
        $('#company_industry').on('change', function() {
            if ($(this).val() == 'other') {
                $('.other_industry').removeClass('d-none')
                $('.other_industry_input').attr('disabled', false)
                $('.other_industry_input').val('')
            } else {
                $('.other_industry').addClass('d-none')
                $('.other_industry_input').attr('disabled', true)
            }
        })

        // $('.phone').mask('000 000 0000');

        // datePickerId.max = new Date(Date.now() - (3600 * 1000 * 24 * 31 * 12 * 18)).toISOString().split("T")[0];

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });

        // location autocomplete
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


        $("#profileForm").submit(function() {
            var error = false;
            if ($('.phone').val() == "" || !($.trim($('#phone').val())) || !telInput.isValidNumber()) {
                error = true;
                $('.phone').addClass('force-invalid');
                $('.phone').focus()
            } else {
                $('.phone').removeClass('force-invalid');
            }

            if (!$('#profileForm')[0].checkValidity() || error) {
                event.preventDefault()
                event.stopPropagation()
                $('#profileForm').addClass('was-validated');
                $('#profileForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#profileForm').removeClass('was-validated');
            }

            saveCompany().then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            )
        })

        $('body').on('click', '.trash_company_img', function(e) {
            if ($('input[name=signup_company_image]').val() != '') {
                swal({
                    title: "<?= __('Are you sure?') ?>",
                    text: "<?= __('You are about to delete your company image.') ?>",
                    icon: "warning",
                    className: "text-center",
                    buttons: ["<?= __('Cancel') ?>", "<?= __('Yes') ?>"],
                }).
                then((isConfirm) => {
                    if (isConfirm) {

                        var data = {}
                        var url = '<?= g('base_url') ?>dashboard/company/update_image'

                        updateCompanyImageAjax(data, url).then(
                            function(response) {
                                if (response.status == 0) {
                                    AdminToastr.error(response.txt, 'Error');
                                } else if (response.status == 1) {
                                    AdminToastr.success(response.txt, 'Success');
                                }
                            }
                        );
                    } else {
                        swal("<?= __('Cancelled') ?>", "<?= __('Action aborted') ?>", "error");
                    }
                })
            } else {
                swal('Error', 'No image found to delete!', 'error')
            }
        })

        $('input[name="signup_company[signup_company_name]"]').on('change keyup keydown keyup keypress', function() {
            $('.slug').val(generateSlug($(this).val()))
        })

    })
</script>