<div class="dashboard-content">
    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <!-- <i class="fa-light fa-chart-simple"></i>
        <hr />
        <div class="graph-screen mt-4 pt-4">
            <div class="row">
                <div class="col-md-9">
                    <canvas id="barChart"></canvas>
                </div>
                <div class="col-md-3">
                    <div class="bar-detail">
                        <span style="background:#d2d6db;">10%</span> First Quater
                    </div>
                    <div class="bar-detail">
                        <span style="background:#58595b;">30%</span> Second Quater
                    </div>
                    <div class="bar-detail">
                        <span style="background:#3a3a3b;">50%</span> Third Quater
                    </div>
                    <div class="bar-detail">
                        <span style="background:#290038;">70%</span> Previous Quater
                    </div>
                    <div class="bar-detail">
                        <span style="background:#8204aa;">90%</span> Last Quater
                    </div>
                </div>
            </div>
        </div> -->

    <?php else : ?>
        <i class="fa-regular fa-warning"></i>
        <h4><?= __("Upgrade Your Account") ?></h4>
        <!-- <hr/> -->
        <p>Welcome <?= $this->model_signup->signupName($this->user_data, false); ?>,</p>
        <span><?= __('You have a active subscription') ?>:&nbsp;<b><?= $this->model_membership->membership_by_pk($this->model_signup->getRoleId()) ?></b></span>

        <?php
        switch(TRUE) {
            case ($this->model_signup->hasRole(ROLE_1)):
                echo '<p>Note: ' .  __("You can upgrade your account and can unlock the following features.") . '</p>';
                break;
            case ($this->user_data['signup_type'] == ROLE_3 && $this->user_data['signup_membership_status'] == STATUS_INACTIVE):
                echo '<p>Note: ' .  __("You can start accessing premium functionalities by subscribing to the membership on the subscription page.") . '</p>';
                break;
        } ?>
        <ul>
            <li><i class="fa-regular fa-input-text"></i> - <?= __('Post bio & detailed profile') ?></li>
            <li><i class="fa-regular fa-file-image"></i> - <?= __('Post photo/company logo') ?></li>
            <li><i class="fa-regular fa-lightbulb-o"></i> - <?= __('Posting/specify work function of interest') ?></li>
            <li><i class="fa-regular fa-face-thinking"></i> - <?= __('Posting/specify work type of interest') ?></li>
            <li><i class="fa-regular fa-clock"></i> - <?= __('List number of hours available to work or hours of work available') ?></li>
            <li><i class="fa-regular fa-calendar"></i> - <?= __('Specify weeks, days and date available for work or for work to be done') ?></li>
            <li><i class="fa-regular fa-paper-plane"></i> - <?= __('Send and receive messages') ?></li>
            <li><i class="fa-regular fa-bell"></i> - <?= __('Notification of work that meet search criteria') ?></li>
            <li><i class="fa fa-analytics"></i> - <?= __('Analytics of profile or work listing') ?></li>
            <li><i class="fa fa-edit"></i> - <?= __('Able to write posts') ?></li>
            <li><i class="fa-regular fa-face-sunglasses"></i> - <?= __('Able to make endorsements') ?></li>
        </ul>

        <?php if ($this->model_signup->hasRole(ROLE_1)): ?>
            <a class="btn btn-custom upgradeBtn" href="<?= l('membership') ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __("You are currently a ") . $this->model_signup->getRawRole() . ", you can upgrade your account to " . RAW_ROLE_3 . "." ?>">
                <?php echo __("Upgrade my account"); ?>
            </a>
        <?php elseif($this->user_data['signup_type'] == ROLE_3 && $this->user_data['signup_membership_status'] == STATUS_INACTIVE) : ?>
            <a class="btn btn-custom upgradeBtn" href="<?= l('membership') ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= __("") ?>">
                <?php echo __("Activate my premium functionalities"); ?>
            </a>
        <?php endif; ?>

    <?php endif; ?>

    <?php 
        $account = $this->model_quickbook_account_request->getAccount($this->userid);

        if($this->model_quickbook_account_request->requestExists($this->userid) && $account && isset($account['quickbook_account_request_status']) && $account['quickbook_account_request_status'] && $this->model_signup->hasPremiumPermission()) : 
    ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5 12C19.5 16.1421 16.1421 19.5 12 19.5C7.85786 19.5 4.5 16.1421 4.5 12C4.5 7.85786 7.85786 4.5 12 4.5C16.1421 4.5 19.5 7.85786 19.5 12ZM21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12ZM11.25 13.5V8.25H12.75V13.5H11.25ZM11.25 15.75V14.25H12.75V15.75H11.25Z" fill="#080341"/>
            </svg>
            Your <strong>Quickbooks account</strong> will be enabled in 24 to 48 hours.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
        <?php $this->load->view('widgets/calendar/index.php'); ?>
    <?php endif; ?>

    <section class="mt-2 testiom-sec themes-padd">
        <div class="">
            <div class="row">
                <div class="col-lg-12">
                    <div class="test-sli">

                        <?php foreach ($testimonial as $key => $value) : ?>
                            <div>

                                <div class="testiom-box">

                                    <ul>

                                        <li>

                                            <div class="img-b">

                                                <img class="lazy" src="<?= get_image($value['testimonial_image_path'], $value['testimonial_image']) ?>" data-src="<?= get_image($value['testimonial_image_path'], $value['testimonial_image']) ?>" alt="images" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />

                                            </div>

                                        </li>

                                        <li>

                                            <h5><?= substr($value['testimonial_name'], 0, 13) ?></h5>

                                            <h6><?= substr($value['testimonial_designation'], 0, 10) ?></h6>

                                        </li>

                                    </ul>

                                    <p><?= $value['testimonial_description'] ?></p>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="succes-story-sec pt-0">
        <div class="container">
            <!--<div class="row align-items-center wow fadeInLeft">-->
            <!--  <div class="col-lg-6">-->
            <!--<h2>Our Success <span> Stories</span>-->
            <!--</h2>-->
            <!--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incid <br /> idunt ut labore et dolore magna aliqua ut enim ad minim veniam. </p>-->
            <!--  </div>-->
            <!--  <div class="col-lg-6 wow bounceIn">-->
            <!--    <div class="slid-numbr"> 01 / <span> 04</span>-->
            <!--    </div>-->
            <!--  </div>-->
            <!--</div>-->
            <div class="succes-slider">
                <?php if (isset($story) && count($story)) : ?>
                    <?php foreach ($story as $key => $value) : ?>
                        <div class="succes-box">
                            <img class="lazy" data-src="<?= get_image($value['story_image_path'], $value['story_image']) ?>" src="<?= get_image($value['story_image_path'], $value['story_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />

                            <div class="ss-cont">

                                <span><?= $value['story_author'] ?? 'Anonymous' ?> | Comment, 0 </span>

                                <span><?= date("d/m/Y", strtotime($value['story_createdon'])) ?></span>

                                <a href="<?= l('story/detail/') . $value['story_slug'] ?>">

                                    <h4><?= $value['story_title'] ?? "..." ?></h4>

                                </a>

                                <p><?= $value['story_short_detail'] ?? "..." ?></p>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script>
    function validURL() {
        var pattern = new RegExp("^" +
            // protocol identifier
            "(?:(?:https?|http)://)" +
            // user:pass authentication
            "(?:\\S+(?::\\S*)?@)?" +
            "(?:" +
            // IP address exclusion
            // private & local networks
            "(?!(?:10|127)(?:\\.\\d{1,3}){3})" +
            "(?!(?:169\\.254|192\\.168)(?:\\.\\d{1,3}){2})" +
            "(?!172\\.(?:1[6-9]|2\\d|3[0-1])(?:\\.\\d{1,3}){2})" +
            // IP address dotted notation octets
            // excludes loopback network 0.0.0.0
            // excludes reserved space >= 224.0.0.0
            // excludes network & broacast addresses
            // (first & last IP address of each class)
            "(?:[1-9]\\d?|1\\d\\d|2[01]\\d|22[0-3])" +
            "(?:\\.(?:1?\\d{1,2}|2[0-4]\\d|25[0-5])){2}" +
            "(?:\\.(?:[1-9]\\d?|1\\d\\d|2[0-4]\\d|25[0-4]))" +
            "|" +
            // host name
            "(?:(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)" +
            // domain name
            "(?:\\.(?:[a-z\\u00a1-\\uffff0-9]-*)*[a-z\\u00a1-\\uffff0-9]+)*" +
            // TLD identifier
            "(?:\\.(?:[a-z\\u00a1-\\uffff]{2,}))" +
            ")" +
            // port number
            "(?::\\d{2,5})?" +
            // resource path
            "(?:/\\S*)?" +
            "$", "i");
        var website = document.querySelector(".website");
        var str = $('.website').val();

        if (!!pattern.test(str) || str == "") {
            website.setCustomValidity("");
            $('.website.invalid-feedback').html("");
        } else {
            $('.website').focus();
            website.setCustomValidity("Invalid url string.");
            $('.website.invalid-feedback').html("Invalid url string.");
            return false;
        }
    }
</script>