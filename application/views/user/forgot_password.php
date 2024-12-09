<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Change Password' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="canvs-sec">
	<div class="container">
		<div class="row">
			<div class="col-md-5 mt-1">
                <form method="post" action="<?= l('user/reset_password') ?>" id="update-pa" novalidate>
                    <div class="col-lg-12 col-md-12 col-sm-12 " >
                        <input type="hidden" name="token" value="<?=$token_user?>">
                        <input type="hidden" name="user_id" value="<?=$user_id?>">
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control inputForm" minlength="6" required placeholder="New Password" />
                        </div>
                        <div class="btnInline">
                            <button type="submit" id="updateBtn" class="btn btn-reset-password btn-custom loginBtn">
                                Update Now
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>

    $(function(){
        $('#update-pa').submit(function(event) {
            event.preventDefault();

            if (!$('#update-pa')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#update-pa').addClass('was-validated');
                $('#update-pa').find(":invalid").first().focus();
                return false;
            } else {
                $('#update-pa').removeClass('was-validated');
            }

            var url = $(this).attr('action');
            var data = $(this).serialize();

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
                        $('#updateBtn').attr('disabled', true)
                        $('#updateBtn').html('<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
                    },
                    complete: function() {
                        $('#updateBtn').attr('disabled', false)
                        $('#updateBtn').html('Update Now')
                    }
                })
            }).then(
                function(response) {
                    if (response.status) {
                        toastr.success(response.txt)
                        setTimeout(function() {
                            window.location.href = '<?= l("login") ?>'
                        }, 1000)
                    } else {
                        toastr.error(response.txt);
                    }
                }    
            );
        });
    });

</script>
