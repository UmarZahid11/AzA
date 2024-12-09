<div class="dashboard-content">

<?php

if(isset($merchant_session) && $merchant_session && isset($merchant_session->url) && $merchant_session->url != NULL) {
    redirect($merchant_session->url);      
} else {
    echo '<label class="text-danger">Oops! An error occurred while processing your request!</label><br/>';
    if($error) {
        echo 'Message: '. $errorMessage;
    }
}
?>

</div>