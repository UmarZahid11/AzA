<?php

$banner_text = EMAIL_CONFIRMATION_TEXT;

?>

<script>
    $(function() {

        function appendBanner(bannerText, showBtn = false) {

            bannerHtml = '\
                <div class= "trial-banner text-center">\
                    <p class="banner-text">' + '<i class="fa fa-exclamation-circle"></i>' + '&nbsp;' + bannerText + '</p>\
                ';
            if (showBtn) {
                bannerHtml += '<a href="javascript:;" class="resend_confirmation btn btn-primary btn-icon banner-button" data-text="Re-send email confirmation">\
                        Re-send email confirmation\
                    </a>\
                ';
            }

            bannerHtml += '\
                <span class="toggler-close"><i class="fa fa-close text-white"></i></span>\
            </div>\
            ';
            $("body").prepend(bannerHtml)
        }

        var bannerText = '<?= $banner_text ?>';
        showBtn = true;
        appendBanner(bannerText, showBtn);

        $(".trial-banner .toggler-close").on("click", function() {
            $(".trial-banner").slideUp(300);
        });
    });
</script>