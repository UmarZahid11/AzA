<?php
$userdata = $this->model_signup->find_by_pk($this->userid);
if (!$userdata) {
    error_404();
}
?>

<div class="side-bar-dash">
    <div class="logo-wrap">
        <a href="<?= l('') ?>" target="_blank">
            <img src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" data-src="<?= Links::img($layout_data['logo']['logo_image_path'], $layout_data['logo']['logo_image']) ?>" alt="logo-small" class="logo-sm lazy" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
        </a>
    </div>
    <div class="side-bar-menu" id="menu-lh-1">

        <a 
            href="<?= l('dashboard/home') ?>"
        >
            <img src="<?= g('dashboard_images_root') ?>mn1.png" alt="">
            <span><?= __('Dashboard') ?></span></a>

        <a 
            href="<?= l('home'); ?>"
        >
            <i class="customFaIcon fa fa-home"></i>
            <span><?= __('Go to Home') ?></span>
        </a>

        <?php if (($this->model_signup->hasRole(ROLE_0))) : ?>
            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasRole(ROLE_0))) ? l('admin') : 'javascript:;'; ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->userid > 0 && (($this->model_signup->hasRole(ROLE_0)))) ? '' : 'Insufficient priviliges'; ?>">
                <i class="customFaIcon fa fa-user-cog"></i>
                <span><?= __('Tools') ?></span>
            </a>
        <?php endif; ?>

        <a 
            data-toggle="tooltip" 
            data-bs-placement="right" 
            title="<?= ($this->userid > 0 && (($this->model_signup->hasPremiumPermission()))) ? '' : 'Insufficient priviliges'; ?>"
            href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? l('dashboard/tutorial') : 'javascript:;'; ?>"
        >
            <i class="customFaIcon fa fa-film"></i>
            <span><?= __('AzAverze Tutorials') ?></span>
        </a>

        <a 
            href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? l('dashboard/coaching/listing') : 'javascript:;'; ?>"
        >
            <i class="customFaIcon fa fa-desktop"></i>
            <span><?= __('Coachings') ?></span>
        </a>

        <?php if($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
            <a 
                class="quickbk <?= !$this->model_quickbook_account->accountExists($this->userid) ? 'quickbkRequest' : '' ?>"
                href="<?php
                    if($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) {
                        echo l('dashboard/home/quickbook');
                    } else {
                        echo 'javascript:;'; 
                    }
                ?>" 
                data-toggle="tooltip" 
                data-bs-placement="right" 
                title="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? '' : ERROR_MESSAGE_SUBSCRIPTION; ?>"
            >
                <svg style="color: rgb(232, 151, 255);" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <title>QuickBooks</title>
                    <path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm.642 4.1335c.9554 0 1.7296.776 1.7296 1.7332v9.0667h1.6c1.614 0 2.9275-1.3156 2.9275-2.933 0-1.6173-1.3136-2.9333-2.9276-2.9333h-.6654V7.3334h.6654c2.5722 0 4.6577 2.0897 4.6577 4.667 0 2.5774-2.0855 4.6666-4.6577 4.6666H12.642zM7.9837 7.333h3.3291v12.533c-.9555 0-1.73-.7759-1.73-1.7332V9.0662H7.9837c-1.6146 0-2.9277 1.316-2.9277 2.9334 0 1.6175 1.3131 2.9333 2.9277 2.9333h.6654v1.7332h-.6654c-2.5725 0-4.6577-2.0892-4.6577-4.6665 0-2.5771 2.0852-4.6666 4.6577-4.6666Z" fill="#e897ff"></path>
                </svg>
                <span><?= __('Accounting: Quickbooks') ?></span>
            </a>

            <a 
                id="chawalID" 
                href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? l('dashboard/plaid') : 'javascript:;'; ?>" 
                data-toggle="tooltip" 
                data-bs-placement="right" 
                title="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? '' : ERROR_MESSAGE_SUBSCRIPTION; ?>"
            >
                <img src="<?= g('dashboard_images_root') ?>plaid.png" alt="" />
                <span><?= __('Banking: Plaid') ?></span>
            </a>
    
            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? 'https://monday.com/' : 'javascript:;'; ?>" <?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? 'target="_blank"' : ''; ?> data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? '' : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" />
                <span><?= __('Project management: Monday') ?></span>
            </a>
    
            <a 
                href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? l('dashboard/box') : 'javascript:;'; ?>" 
                data-bs-html="true" 
                data-toggle="tooltip" 
                data-bs-placement="right"  
                data-bs-title="
                <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                    <?php echo "<a href='" . l(TUTORIAL_PATH . BOX_TUTORIAL) . "'><i class='fa fa-film' aria-hidden='true'></i>&nbsp;Box Tutorial</a>"; ?>
                    <?php else : ?>
                    <?php echo ERROR_MESSAGE_SUBSCRIPTION; ?>
                <?php endif; ?>"
            >
                <svg style="width:20px;" class="logo-box" id="Layer_1m" viewBox="0 0 40 21.6" xmlns="http://www.w3.org/2000/svg">
                    <path class="box-logo-svg" d="M39.7 19.2c.5.7.4 1.6-.2 2.1-.7.5-1.7.4-2.2-.2l-3.5-4.5-3.4 4.4c-.5.7-1.5.7-2.2.2-.7-.5-.8-1.4-.3-2.1l4-5.2-4-5.2c-.5-.7-.3-1.7.3-2.2.7-.5 1.7-.3 2.2.3l3.4 4.5L37.3 7c.5-.7 1.4-.8 2.2-.3.7.5.7 1.5.2 2.2L35.8 14l3.9 5.2zm-18.2-.6c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c-.1 2.6-2.2 4.6-4.7 4.6zm-13.8 0c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c0 2.6-2.1 4.6-4.7 4.6zM21.5 6.4c-2.9 0-5.5 1.6-6.8 4-1.3-2.4-3.9-4-6.9-4-1.8 0-3.4.6-4.7 1.5V1.5C3.1.7 2.4 0 1.6 0 .7 0 0 .7 0 1.5v12.6c.1 4.2 3.5 7.5 7.7 7.5 3 0 5.6-1.7 6.9-4.1 1.3 2.4 3.9 4.1 6.8 4.1 4.3 0 7.8-3.4 7.8-7.7.1-4.1-3.4-7.5-7.7-7.5z"></path>
                </svg>
                <span><?= __('Document management: Box') ?></span>
            </a>
        <?php endif; ?>

        <!--<a href="javascript:void(0);" id="socialForum2" data-disabled="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? 0 : 1; ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? '' : ERROR_MESSAGE_SUBSCRIPTION; ?>"><i class="customFaIcon material-icons">&#xe0bf;</i><?= __('AzAverze Community') ?></a>-->
        <a 
            href="javascript:;" 
            id="socialForum" 
        >
            <!--data-disabled="<?//= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? 0 : 1; ?>" data-toggle="tooltip" data-bs-placement="right" title="<?//= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? '' : ERROR_MESSAGE_SUBSCRIPTION; ?>"-->
            <i class="customFaIcon material-icons">&#xe0bf;</i><?= __('AzAverze Community') ?>
        </a>
        
        <?php if(($this->userid > 0 && ($this->model_signup->hasPremiumPermission()))): ?>
            <a 
                href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) ? l('dashboard/payment/method') : 'javascript:;'; ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->userid > 0 && (($this->model_signup->hasPremiumPermission()))) ? '' : 'Insufficient priviliges'; ?>"
            >
                <i class="customFaIcon fa fa-credit-card"></i>
                <span><?= __('My saved cards') ?></span>
            </a>
        <?php endif; ?>

        <ul class="main-navigation-menu">
            <li>
                <a href="javascript:;" class="side-caret-anchor">
                    <i class="customFaIcon fa fa-shopping-cart"></i>
                    <span class="title">
                        My Orders
                    </span>
                    <i class="fa-solid fa-angle-down side-caret"></i>
                    <span class="selected">
                    </span>
                </a>
                <ul class="sub-menu">
                    <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/listing') : 'javascript:;'); ?>">
                                <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> My ordered products </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/listing/' . PRODUCT_REFERENCE_TECHNOLOGY) : 'javascript:;'); ?>">
                                <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> My ordered technologies </span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/request/' . PRODUCT_REFERENCE_SERVICE . '/1/' . PER_PAGE . '/' . JWT::encode($this->userid)) : 'javascript:;'); ?>">
                            <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> My ordered services </span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>

        <ul class="main-navigation-menu">
            <li>
                <a href="javascript:;" class="side-caret-anchor">
                    <i class="customFaIcon fa fa-file-invoice"></i>
                    <span class="title"> My invoices </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Subscription Invoices  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_SERVICE) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Invoices For Services Purchased  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_SERVICE_PROVIDED) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Invoices For Services Provided  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_COACHING) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Coaching Invoices  </span>
                        </a>
                    </li>
                    <?php if($this->model_signup->hasRole(ROLE_0)): ?>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_PRODUCT) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Product Invoices  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_JOB) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Job Invoices  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/invoices/' . INVOICE_TECHNOLOGY) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>"> Technology Invoices  </span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>

        <a href="<?= l('dashboard/profile/listing/') . JWT::encode(ROLE_3, CI_ENCRYPTION_SECRET) ?>">
            <img src="<?= g('dashboard_images_root') ?>mn3.png" alt="" />
            <span><?= __('Entrepreneur Listing') ?></span>
        </a>

        <a href="<?= l('dashboard/profile/listing/') . JWT::encode(ROLE_1, CI_ENCRYPTION_SECRET) ?>">
            <img src="<?= g('dashboard_images_root') ?>mn3.png" alt="" />
            <span><?= __('Customer Listing') ?></span>
        </a>

        <?php if($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/calendar') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                <i class="customFaIcon fa fa-calendar"></i>
                <span><?= __('Calendar') ?></span>
            </a>
        <?php endif; ?>

        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('announcement/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
            <i class="customFaIcon fa fa-bullhorn"></i>
            <span><?= __('AzAverze Announcements') ?></span>
        </a>

        <?php if($this->model_signup->hasPremiumPermission()): ?>
            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <i class="customFaIcon fa fa-product-hunt"></i>
                        <span class="title"> Products </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/save/' . CREATE . '/' . PRODUCT_REFERENCE_PRODUCT) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Add product </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All products </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/listing/' . PRODUCT_REFERENCE_PRODUCT . '/' . JWT::encode($this->userid)) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My posted products </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/orders') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "See who ordered my posted products" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Ordered my products </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>

        <ul class="main-navigation-menu">
            <li>
                <a href="javascript:;" class="side-caret-anchor">
                    <i class="customFaIcon fa fa-wrench"></i>
                    <span class="title"> Services </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/save/' . CREATE . '/' . PRODUCT_REFERENCE_SERVICE) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> Add service </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/listing/' . PRODUCT_REFERENCE_SERVICE) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> All services  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/listing/' . PRODUCT_REFERENCE_SERVICE . '/' . JWT::encode($this->userid)) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> My posted services  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/request/' . PRODUCT_REFERENCE_SERVICE . '/1/' . PER_PAGE . '/' . JWT::encode($this->userid)) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> My requested services  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/request/' . PRODUCT_REFERENCE_SERVICE) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> My received requests  </span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/orders/' . PRODUCT_REFERENCE_SERVICE) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "See who ordered my services" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                            <span class="title"> Ordered my services </span>
                        </a>
                    </li>

                </ul>
            </li>
        </ul>

        <?php if($this->model_signup->hasPremiumPermission()): ?>
            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <i class="customFaIcon fa fa-industry"></i>
                        <span class="title"> Technologies </span>
                        <i class="fa-solid fa-angle-down side-caret"></i>
                        <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/save/' . CREATE . '/' . PRODUCT_REFERENCE_TECHNOLOGY) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Add technology </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/listing/' . PRODUCT_REFERENCE_TECHNOLOGY) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All technologies  </span>
                                <!--Listed-->
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/request/' . PRODUCT_REFERENCE_TECHNOLOGY) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Requested technologies  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/product/orders/' . PRODUCT_REFERENCE_TECHNOLOGY) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "See who ordered my posted technologies" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Ordered my technologies </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/order/listing/' . PRODUCT_REFERENCE_TECHNOLOGY_LISTING) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "See my posted technologies subscriptions" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My subscriptions </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>
    
        <?php //if ($this->model_signup->hasPremiumPermission()) : ?>
        <?php if($this->model_signup->hasPremiumPermission()): ?>
            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <img src="<?= g('dashboard_images_root') ?>mn6.png" alt="" />
                        <span class="title"> Jobs </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/job/post') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Add job </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/job/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All jobs  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/job/posted') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My posted jobs  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/job/listing/1' . '/' . PER_PAGE . '/0/1') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My applied jobs  </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        <?php endif; ?>

        <?php if ($this->model_signup->hasPremiumPermission()) : ?>

            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <i class="customFaIcon fa fa-comments"></i>
                        <span class="title"> Endorsements </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/endorsement/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "My sent ednorsements" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My Endorsements  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/endorsement/listing/1/' . PER_PAGE . '/' . JWT::encode($this->userid) . '/1') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "My received ednorsements" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Others Endorsements </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <i class="customFaIcon fa fa-video-camera"></i>
                        <span class="title"> Testimonials </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/account/testimonial/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "My sent testimonials" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My Testimonials  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/account/testimonial/listing/1/' . PER_PAGE . '/' . JWT::encode($this->userid) . '/1') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "My received testimonials" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Others Testimonials </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <img src="<?= g('dashboard_images_root') ?>mn6.png" alt="" />
                        <span class="title"> News </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/news/post') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Post news  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/news/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All news </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/news/listing/1/6/' . $this->userid) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My news  </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <img src="<?= g('dashboard_images_root') ?>mn6.png" alt="" />
                        <span class="title"> Blogs </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/blog/post') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Post blog  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/blog/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All blogs </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/blog/listing/1/6/' . $this->userid) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My blogs  </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="main-navigation-menu">
                <li>
                    <a href="javascript:;" class="side-caret-anchor">
                        <img src="<?= g('dashboard_images_root') ?>mn6.png" alt="" />
                        <span class="title"> Stories </span> <i class="fa-solid fa-angle-down side-caret"></i> <span class="selected"></span>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/story/post') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> Post Story  </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/story/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> All Stories </span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/story/listing/1/6/' . $this->userid) : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->model_signup->hasPremiumPermission()) ? "" : ERROR_MESSAGE_SUBSCRIPTION; ?>">
                                <span class="title"> My Stories  </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        <?php endif; ?>

        <?php if($this->model_signup->hasPremiumPermission()): ?>
            <a href="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? l('dashboard/webinar/listing') : 'javascript:;'); ?>" data-toggle="tooltip" data-bs-placement="right" title="<?= ($this->userid > 0 && ($this->model_signup->hasPremiumPermission()) ? '' : 'This functionality is only available for ' . RAW_ROLE_3 . ' users'); ?>">
                <i class="customFaIcon fa fa-desktop"></i><?= __('My webinars') ?>
            </a>
        <?php endif; ?>

        <?php //if ($this->model_signup->hasPremiumPermission()) : ?>
        <!-- Temporary disabled, will be functional in future -->
        <!-- <a href="<? //= l('dashboard/home/apply-bidding') ?>"><img src="<? //= g('dashboard_images_root') ?>mn9.png" alt=""><? //= __('Apply bidding') ?></a> -->
        <?php //endif; ?>

        <a href="<?= l('logout') ?>"><i class="customFaIcon fa fa-sign-out"></i><?= __('Logout') ?></a>

    </div>
</div>

<script>
    var $windowWidth;
    var $windowHeight;
    var $pageArea;
    var isMobile = false;
    $(function() {
        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            isMobile = true;
        }
        runElementsPosition();
        runNavigationToggler();
    });
    $('.main-navigation-menu li.active').addClass('open');
    $('.main-navigation-menu > li a').on('click', function() {
        if ($(this).parent().children('ul').hasClass('sub-menu') && ((!$('body').hasClass('navigation-small') || $windowWidth < 767) || !$(this).parent().parent().hasClass('main-navigation-menu'))) {
            if (!$(this).parent().hasClass('open')) {
                $(this).parent().addClass('open');
                $(this).parent().parent().children('li.open').not($(this).parent()).not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200);
                $(this).parent().children('ul').slideDown(200, function() {
                    runContainerHeight();
                });
            } else {
                if (!$(this).parent().hasClass('active')) {
                    $(this).parent().parent().children('li.open').not($('.main-navigation-menu > li.active')).removeClass('open').children('ul').slideUp(200, function() {
                        runContainerHeight();
                    });
                } else {
                    $(this).parent().parent().children('li.open').removeClass('open').children('ul').slideUp(200, function() {
                        runContainerHeight();
                    });
                }
            }
        }
    });

    //function to adapt the Main Content height to the Main Navigation height
    var runContainerHeight = function() {
        mainContainer = $('.main-content > .container');
        mainNavigation = $('.main-navigation');
        if ($pageArea < 760) {
            $pageArea = 760;
        }
        if (mainContainer.outerHeight() < mainNavigation.outerHeight() && mainNavigation.outerHeight() > $pageArea) {
            mainContainer.css('min-height', mainNavigation.outerHeight());
        } else {
            mainContainer.css('min-height', $pageArea);
        }
        if ($windowWidth < 768) {
            mainNavigation.css('min-height', $windowHeight - $('body > .navbar').outerHeight());
        }
        //$("#page-sidebar .sidebar-wrapper").css('height', $windowHeight - $('body > .navbar').outerHeight()).scrollTop(0).perfectScrollbar('update');
    };

    //function to adjust the template elements based on the window size
    var runElementsPosition = function() {
        $windowWidth = $(window).width();
        $windowHeight = $(window).height();
        $pageArea = $windowHeight - $('body > .navbar').outerHeight() - $('body > .footer').outerHeight();
        if (!isMobile) {
            $('.sidebar-search input').removeAttr('style').removeClass('open');
        }
        runContainerHeight();

    };

    //function to reduce the size of the Main Menu
    var runNavigationToggler = function() {
        $('.navigation-toggler').on('click', function() {
            if (!$('body').hasClass('navigation-small')) {
                $('body').addClass('navigation-small');
            } else {
                $('body').removeClass('navigation-small');
            };
        });
    };

    $(document).ready(function(){
        // $('.side-caret').on('click', function(e){
        //     e.preventDefault()
        //     $(this).toggleClass('fa-angle-down fa-angle-left')
        // })
        $('.side-caret-anchor').on('click', function(e){
            e.preventDefault()
            $(this).find('.side-caret').toggleClass('fa-angle-down fa-angle-left')
        })

        $('.quickbkRequest').on('click', function(e) {
            e.preventDefault()
            swal({
                title: "<?= __('Confirm') ?>",
                text: "Send quickbooks account creation request to administrator?",
                icon: "warning",
                className: "text-center",
                buttons: ["<?= __('No, Cancel') ?>", "<?= __('Yes') ?>"],
            }).
            then((isConfirm) => {
                if (isConfirm) {
                    let data = {};
                    let url = base_url + 'dashboard/quickbook/accountRequest';
                    new Promise((resolve, reject) => {
                        jQuery.ajax({
                            url: url,
                            type: "POST",
                            data: data,
                            dataType: 'json',
                            async: true,
                            success: function(response) {
                                resolve(response)
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                            },
                            beforeSend: function() {
                                showLoader()
                            },
                            complete: function() {
                                hideLoader()
                            }
                        });
                    }).then(
                        function(response) {
                            if (response.status) {
                                swal("Success", response.txt, "success");
                            } else {
                                swal("Error", response.txt, "error");
                            }
                        }
                    )
                } else {}
            });
        })
    })
</script>