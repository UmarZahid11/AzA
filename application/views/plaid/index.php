<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Plaid Authorization' ?>

                    </h1>

                </div>

            </div>
            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

        <input type="hidden" name="type" value="<?= isset($type) && $type ? $type : '' ?>" />
        <input type="hidden" name="income_type" value="<?= isset($income_type) && $income_type ? $income_type : '' ?>" />

    </div>

</section>

<div class="container text-center my-5">
    <div class="load-more loadImg">
        <img src="<?= g('images_root') ?>tail-spin-dark.svg" alt="" />
    </div>
</div>

<script src="https://cdn.plaid.com/link/v2/stable/link-initialize.js"></script>

<script>
    $(document).ready(function() {
        new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: '<?= l('plaid/generate_token') ?>',
                data: {
                    '_token': $('meta[name=csrf-token]').attr("content"),
                    'type': $('input[name=type]').val(),
                    'income_type': $('input[name=income_type]').val(),
                },
                dataType: 'JSON',
                async: true,
                success: function(response) {
                    resolve(response)
                },
                complete: function(xhr, txt) {
                    hideLoader()
                },
                beforeSend: function() {
                    showLoader()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                }
            });
        }).then(
            function(response) {
                if (response.status) {
                    const handler = Plaid.create({
                        token: response.link_token,
                        // receivedRedirectUri: window.location.href,
                        onSuccess: (public_token, metadata) => {
                            console.log(metadata)
                            var data = {
                                '_token': $('meta[name=csrf-token]').attr("content"),
                                'public_token': public_token,
                                'type': $('input[name=type]').val(),
                                'income_type': $('input[name=income_type]').val(),
                                'link_session_id' : metadata.link_session_id,
                                'account_id': metadata.account_id,
                                // called for the connection intent
                                'connection': 1,
                            }
                            var url = '<?= l('plaid/exchange_token') ?>';

                            new Promise((resolve, reject) => {
                                $.ajax({
                                    type: "POST",
                                    url: url,
                                    data: data,
                                    dataType: 'JSON',
                                    async: true,
                                    success: function(response) {
                                        resolve(response)
                                    },
                                    error: function(jqXHR, textStatus, errorThrown) {
                                        toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                                    }
                                });
                            }).then(
                                function(response) {
                                    if (response.status) {
                                        $.dialog({
                                            backgroundDismiss: true,
                                            title: '<?= __("Success!") ?>',
                                            content: response.message,
                                            onClose: function() {
                                                // redirect to statistic page?
                                                location.href = response.redirect
                                            }
                                        });
                                    } else {
                                        $.dialog({
                                            backgroundDismiss: true,
                                            title: '<?= __("Error!") ?>',
                                            content: response.message,
                                            onClose: function() {
                                                window.location.reload()
                                            }
                                        });
                                    }
                                }
                            )
                        },
                        onLoad: () => {},
                        onExit: (err, metadata) => {
                             console.log(err)
                            //  console.log('message: ' + err.error_message)
                            if (err) {
                                // console.log(err)
                                $.dialog({
                                    backgroundDismiss: true,
                                    title: '<?= __("Error!") ?>',
                                    content: err.error_message,
                                    onClose: function() {
                                        window.location.reload()
                                    }
                                });
                            }
                             console.log(metadata)
                            // Save data from the onExit handler
                            // handler.report({
                            //     error: error,
                            //     institution: metadata.institution,
                            //     link_session_id: metadata.link_session_id,
                            //     plaid_request_id: metadata.request_id,
                            //     status: metadata.status,
                            // });
                        },
                        onEvent: (eventName, metadata) => {
                            if(eventName == 'EXIT') {
                                $('.loadImg').addClass('d-none')
                            }
                        },
                        // required for OAuth; if not using OAuth, set to null or omit:
                        // receivedRedirectUri: window.location.href,
                    });
                    // Open Link
                    handler.open();
                } else {
                    $.dialog({
                        backgroundDismiss: true,
                        title: '<?= __("Error!") ?>',
                        content: response.message,
                        onClose: function() {
                            window.location.reload()
                        }
                    });
                }
            }
        )
    })
</script>