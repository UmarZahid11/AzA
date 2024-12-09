
<div class="dashboard-content">
    <div class="mt-4">
        <section class="member-ship-sec">

            <div class="container">
                <?php switch($status):
                    case 'success':
                        echo '<label class="text-success">' . $message . '!</label>';
                        break;

                    case 'failed':
                        echo '<label class="text-danger">Oops! An error occurred while processing your request!</label>';
                        break;
                endswitch; ?>
            </div>

        </section>
    </div>
</div>