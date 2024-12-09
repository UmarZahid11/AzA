<script>
    $(document).ready(function(){
        if(<?= isset($url) && $url ? 1 : 0 ?>) {
            showLoader();
            setTimeout(function(){
                window.location.href = '<?= $url ?>';
            }, 1000)
        }
    })
</script>