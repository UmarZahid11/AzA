<div class="dashboard-content posted-theme">

    <div class="container text-center">
        <?php switch($status):
            case 'success':
                echo '<label class="text-success">Payment successful!</label><br />';
                echo '<a href="' . l('dashboard/coaching') . '">Go to coaching</a>';
                break;
            default:
                echo 'Unknow error occurred';
                break;
        endswitch; ?>
    </div>

</div>