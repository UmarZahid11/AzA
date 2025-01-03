<section class="pricing-serr" id="membrshp">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <h2>A <b>Plan</b> to <b>Power</b> for any of your Project.</h2>
                
                <?php if(isset($sectionData)) : ?>
                    <?php foreach($sectionData as $sectionKey => $data) : ?>
                        <div class="listinghy">
                            <span><?= $sectionKey ?></span>
                            <div>
                                <?php foreach($data as $sectionList): ?>
                                    <p><?= $sectionList['membership_attribute_name'] ?></p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <div class="row">
                    <?php if(isset($membershipData)) : ?>
                        <?php if(count($membershipData) < 4): ?>
                            <div class="col-md-3"></div>
                        <?php endif; ?>
                        <?php foreach($membershipData as $membershipKey => $data) : ?>
                            <div class="col-md-3">
                                <div class="privcestrp <?= $this->model_membership->isCurrentMembership($membershipKey) ? 'selected' : '' ?> <?php
                                        switch($membershipKey) {
                                            case 3:
                                                echo 'entrtry';
                                                break;
                                            case 4:
                                                echo 'intier';
                                                break;
                                            case 5:
                                                echo 'lader';
                                                break;
                                        }

                                    ?>">
                                    
                                    <?php if($data['membership']['membership_icon']) : ?>
                                        <img src="<?= get_image($data['membership']['membership_image_path'], $data['membership']['membership_icon']) ?>" alt="" />
                                    <?php else: ?>
                                        <img src="<?= g('images_root') ?>prc1.png" alt="" />
                                    <?php endif; ?>

                                    <h6><?= strtoupper($data['membership']['membership_title']) ?></h6>
                                    <h3>
                                        <?= $data['membership']['membership_cost'] == 0 ? FREE_COST_KEYWORD : price($data['membership']['membership_cost']) ?> 
                                        <?php if($data['membership']['membership_cost'] != 0) : ?>
                                            <?= ('<small>' . 
                                                ($data['membership']['membership_interval']['membership_interval_name'] != 'Custom' ? (' / ' . $data['membership']['membership_interval']['membership_interval_name']) : $data['membership']['membership_custom_description'])
                                            . '</small>') ?>
                                        <?php endif; ?>
                                    </h3>
                                    <div class="ponyidk">
                                        <?php foreach($data['data'] as $keyList => $valueList) : ?>
                                            <?php
                                                if(($data['membership']['membership_id'] == 3) AND ($keyList == 9)){
                                                    echo "<p style='color:#fff;font-weight:bold'>Coming Soon</p>";
                                                }
                                                else{
                                                    switch($valueList['membership_pivot_value']) {
                                                        case '0':
                                                            echo '<img src="' . g('images_root') . 'x.png" alt="" />';
                                                            break;
                                                        case '1':
                                                            echo '<img src="' . g('images_root') . 'tick.png" alt="" />';
                                                            break;
                                                    }
                                                }
                                            ?>
                                        <?php endforeach; ?>
                                    </div>

                                    <?php if($this->model_membership->isCurrentMembership($membershipKey)) : ?>
                                        <h3>Active</h3>
                                    <?php else: ?>
                                        <?php if(!in_array($membershipKey, [ROLE_5])) : ?>
                                            <h3><a href="<?= $this->userid == 0 ? l('login') : (l('membership/payment/') . JWT::encode($data['membership']['membership_id'])) ?>">Select</a></h3>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <p class="sadee-as">You may cancel funds anytime. Strictly no refunds.</p>
        </div>
        <div class="joina-zaza">
            <h3>Join AzAverze today</h3>
            <p>and start transforming the way you do business.</p>
        </div>
    </div>
</section>