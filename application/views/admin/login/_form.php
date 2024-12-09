<style>
    #eye-patch {
        position: absolute;
        top: 8px;
        right: 12px;
    }
    /*loading-ellipsis*/
        .loading-ellipsis:after {
          overflow: hidden;
          display: inline-block;
          vertical-align: bottom;
          width: 0px;
          content: "\2026";
          animation: ellipsis steps(8,end) 800ms infinite;
        }
        
        @keyframes ellipsis {
          to {
            width: 1.25em;
          }
        }
        
        .loading-ellipsis {
          font-size: 16px;
        }
    /*loading-ellipsis*/
</style>

<?php global $config; ?>

<!-- BEGIN LOGIN -->

<div class="main-login col-sm-4 col-sm-offset-4">

    <div class="logo">
        <?php //echo $config['admin_title'];
        ?>
        <img style="max-width:175px;" alt="" src="<?= Links::img($logo[0]['logo_image_path'], $logo[0]['logo_image']) ?>" />
    </div>

    <!-- start: LOGIN BOX -->

    <div class="box-login text-center">

        <h3>Sign in to your account</h3>

        <p>

            Please enter your email and password to log in.

        </p>

        <form class="form-login" action="<?= $config['base_url'] ?>admin/login/" method="post">

            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />

            <? if (isset($error) && $error) { ?>

                <div class="errorHandler alert alert-danger no-display">

                    <i class="fa fa-remove-sign"></i> <?= $error ?? 'Invalid Credentials' ?>

                </div>

            <?php } ?>

            <fieldset>

                <div class="form-group">

                    <span class="input-icon">

                        <input type="text" class="form-control" name="user_email" placeholder="Email">

                        <i class="fa fa-user"></i> </span>

                </div>

                <div class="form-group form-actions">

                    <span class="input-icon">

                        <input type="password" class="form-control password" name="user_password" id="password" placeholder="Password">

                        <i class="fa fa-lock"></i>

                        <a href="javascript:;" id="eye-patch">
                            <i class="fa fa-eye"></i>
                        </a>

                    </span>

                </div>

                <?php if(defined('CAPTCHA_SITE_KEY')): ?>
                    <div class="g-recaptcha" data-sitekey="<?= CAPTCHA_SITE_KEY ?>"></div>
                    <script src="https://www.google.com/recaptcha/api.js"></script>
                <?php endif; ?>

                <div class="form-actions">

                    <!--<label for="remember" class="checkbox-inline">

                        <input type="checkbox" class="grey remember" id="remember" name="remember">

                        Keep me signed in

                    </label>-->

                    <button type="submit" class="btn btn-bricky pull-right loginBtn">

                        Login <i class="fa fa-arrow-circle-right"></i>

                    </button>

                </div>

            </fieldset>

            <input type="hidden" value="<?= (isset($_GET['redirect_url'])) ? $_GET['redirect_url'] : '' ?>" name="redirect_url" />

        </form>

    </div>

    <!-- end: LOGIN BOX -->

</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#eye-patch').on('click', function() {
            $(this).find('i').toggleClass('fa-eye')
            $(this).find('i').toggleClass('fa-eye-slash')
            if ($(this).find('i').hasClass('fa-eye-slash')) {
                $('#password').attr('type', 'text')
            } else {
                $('#password').attr('type', 'password')
            }
        })

        $('.form-login').on('submit', function(){
            $('.loginBtn').attr('disabled', true)
            $('.loginBtn').html('Processing ...')
        })
    })
</script>