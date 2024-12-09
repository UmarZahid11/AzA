<div class="dashboard-content">
    <i class="fa-regular fa-book"></i>
    <h4><?= (isset($title) ? ucfirst($title) : 'Create') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <?php $this->load->view('dashboard/home/quickbook/' . $entity . '/create'); ?>
</div>

<script>
    $('document').ready(function() {

        $('#quickbookSaveForm').on('submit', function() {
            showLoader()

            if (!$('#quickbookSaveForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#quickbookSaveForm').addClass('was-validated');
                $('#quickbookSaveForm').find(":invalid").first().focus();
                hideLoader()
                return false;
            } else {
                $('#quickbookSaveForm').removeClass('was-validated');
            }
            var data = $('#quickbookSaveForm').serialize();
            var url = base_url + 'quickbook/saveEntity';

            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        swal({
                            title: "Success",
                            text: res.txt,
                            icon: "success",
                        }).then(() => {
                            window.open('<?= l('dashboard/home/quickbook-view/' . $entity . '/') ?>' + res.result.Id, '_blank');
                        });
                        if ($('input[name=id]').length == '') {
                            $('#quickbookSaveForm').each(function() {
                                this.reset();
                            });
                        }
                    } else {
                        swal({
                            title: "Error",
                            text: res.txt.hasOwnProperty(0) && typeof res.txt == "object" ? res.txt[0] : (res.txt != undefined ? res.txt : '<?= __(ERROR_MESSAGE) ?>'),
                            icon: "warning"
                        }).then((isConfirm) => {
                            if (res.refresh) {
                                location.reload();
                            }
                        });
                    }
                    hideLoader()
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    hideLoader()
                    swal('Error', textStatus + ": " + jqXHR.status + " " + errorThrown, 'error')
                },
                beforeSend: function() {
                    console.log("showing loader")
                    Loader.show()
                }
            });
        })
    })
</script>