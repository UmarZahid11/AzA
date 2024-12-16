<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Payment' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="member-ship-sec">

    <div class="container">
        <?php switch($status):
            case 'success':
                echo '<label class="text-success">Subscription Successful!</label>';
                break;

            case 'failed':
                echo '<label class="text-danger">Oops! An error occurred while processing payment!</label> <br />';
                echo '<a href="' . l('membership') . '">Go to subscription page.</a>';
                break;
        endswitch; ?>
    </div>

</section>