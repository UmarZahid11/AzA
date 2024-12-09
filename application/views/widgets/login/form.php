<div class="loginBox">

    <?php if (isset($login_cms[0]['cms_page_content'])) : ?>
        <h2><?= ($login_cms[0]['cms_page_title']) ?></h2>
    <?php else : ?>
        <h2>Login Your Account</h2>
    <?php endif; ?>

    <form class="login-form" action="javascript:;" method="post" novalidate>
        <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
        <input type="hidden" name="signup_reverified" value="0" />
        <input type="hidden" name="redirect_url" value="<?= isset($_GET['redirect_url']) ? $_GET['redirect_url'] : '' ?>" />
        <div class="mb-3 mt-3">
            <input type="email" class="form-control inputForm" id="email" placeholder="E-mail" name="signup[signup_email]" required />
        </div>
        <div class="mb-3">
            <div class="container2">
                <input type="password" class="form-control inputForm" id="password" placeholder="Password" name="signup[signup_password]" required minlength="6" />
                <div id="eye-wrapper" onclick="toggleEye()">
                    <svg id="open" width="25" height="25">
                        <g stroke="#7D4262" stroke-miterlimit="10">
                            <path d="M21.632 12.5a9.759 9.759 0 01-18.264 0 9.759 9.759 0 0118.264 0z" fill="none" />
                            <circle cx="12.5" cy="12.5" r="3" fill="#7D4262" />
                            <path fill="none" d="M12.5 5v1-4M9.291 6.337L7.709 2.663M15.709 6.337l1.582-3.674" />
                        </g>
                    </svg>
                    <svg id="close" width="25" height="25">
                        <g fill="none" stroke="#7D4262" stroke-miterlimit="10">
                            <path d="M21.632 12.5a9.759 9.759 0 01-18.264 0M12.5 19.5v-1 4M9.291 18.163l-1.582 3.674M15.709 18.163l1.582 3.674" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <?php if (defined('CAPTCHA_SITE_KEY')): ?>
            <div class="g-recaptcha" data-sitekey="<?= CAPTCHA_SITE_KEY ?>"></div>
            <script src="https://www.google.com/recaptcha/api.js"></script>
        <?php endif; ?>

        <div class="form-btm mb-2">
            <label>
                <input type="checkbox" class="rememberMe">
                <span class="font-13"><?= __('Remember me') ?></span>
            </label>
            <a data-fancybox data-animation-duration="700" data-src="#animatedModal" href="javascript:;" class="btn"><?= __('Forgot Password') ?>?</a>
        </div>
        <div class="btnInline">
            <button type="submit" class="btn btn-custom loginBtn" id="login-submit">
                Sign In
            </button>
            <a href="<?= l('signup') . (isset($_GET['redirect_url']) ? '?redirect_url=' . urlencode($_GET['redirect_url']) : ''); ?>" class="btn btn-custom submitBtn">
                Create New Account
            </a>
        </div>
    </form>
    <div class="text-center">
        <a class="vouchedModalBtn d-none" href="javascript:;" data-fancybox data-animation-duration="700" data-src="#vouchedModal"><?= __('Relaunch Vouched') ?>&nbsp;<span class="fa fa-question-circle" data-toggle="tooltip" data-bs-placement="top" title="<?= __('Verify your identity with vouched.') ?>"></span></a>
    </div>
</div>