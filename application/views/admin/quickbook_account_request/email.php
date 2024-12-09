<? global $config; ?>

<div class="inner-page-header">
    <h1>Quicbook Account Request <small>Record</small></h1>
</div>

<div class="row">

    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> Quicbook Account Request <small>Send account creation email</small>
                </div>
            </div>

            <div class="portlet-body">
                <form id="quickbookRequestEmailForm" action="javascript:;" method="POST" novalidate>

                    <input type="hidden" name="_token" />
                    <input type="hidden" name="quickbook_account_request_id" value="<?= isset($quickbook_account_request['quickbook_account_request_id']) ? $quickbook_account_request['quickbook_account_request_id'] : 0 ?>" />
                    <input type="hidden" name="quickbook_account[quickbook_account_signup_id]" value="<?= isset($quickbook_account_request['quickbook_account_request_signup_id']) ? $quickbook_account_request['quickbook_account_request_signup_id'] : 0 ?>" />
                    
                    <div class="form-group">
                        <label>To</label>
                        <input type="email" class="form-control" name="to" value="<?= isset($quickbook_account_request['signup_email']) ? $quickbook_account_request['signup_email'] : '' ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control" name="subject" maxlength="250" />
                    </div>

                    <div class="form-group">
                        <label>Quickbook email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="quickbook_account[quickbook_account_email]" maxlength="255" value="<?= isset($quickbook_account['quickbook_account_email']) ? $quickbook_account['quickbook_account_email'] : '' ?>" required />
                    </div>
                    <div class="form-group">
                        <label>Quickbook password <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="quickbook_account[quickbook_account_password]" maxlength="255" value="<?= isset($quickbook_account['quickbook_account_password']) ? $quickbook_account['quickbook_account_password'] : '' ?>" required />
                    </div>

                    <!--<div class="form-group">-->
                    <!--    <label>Message <span class="text-danger">*</span></label>-->
                    <!--    <textarea class="form-control" name="email" required rows="10" maxlength="5000"></textarea>-->
                    <!--</div>-->
                    <button class="btn green" id="quickbookRequestEmailFormBtn">Send email</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('submit', '#quickbookRequestEmailForm', function() {
            var quickbookRequestEmailFormBtn = $('#quickbookRequestEmailFormBtn')

            if (!$(this)[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $(this).addClass('was-validated');
                $(this).find(":invalid").first().focus();
                return false;
            } else {
                $(this).removeClass('was-validated');
            }

            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $(this).serialize()
            var url = base_url + 'quickbook_account_request/sendEmail'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        quickbookRequestEmailFormBtn.attr('disabled', true)
                        quickbookRequestEmailFormBtn.html('Sending ...')
                    },
                    complete: function() {
                        quickbookRequestEmailFormBtn.attr('disabled', false)
                        quickbookRequestEmailFormBtn.html('Send email')
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        locatio.reload();
                        // $( '#quickbookRequestEmailForm' ).each(function() {
                        //     this.reset();
                        // });
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            )
        })
    })
</script>