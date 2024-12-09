<? global $config; ?>

<div class="inner-page-header">
    <h1>Inquiry <small>Record</small></h1>
</div>

<div class="row">

    <div class="col-md-12">

        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-edit"></i> Inquiry <small>Send email</small>
                </div>
            </div>

            <div class="portlet-body">
                <form id="inquiryEmailForm" action="javascript:;" method="POST" novalidate>
                    <input type="hidden" name="_token" />
                    <input type="hidden" name="inquiry_id" value="<?= isset($inquiry['inquiry_id']) ? $inquiry['inquiry_id'] : 0 ?>" />
                    <div class="form-group">
                        <label>To</label>
                        <input type="email" class="form-control" name="to" value="<?= isset($inquiry['inquiry_email']) ? $inquiry['inquiry_email'] : '' ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" class="form-control" name="subject" maxlength="250" />
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea class="form-control" name="email" required maxlength="5000"></textarea>
                    </div>
                    <button class="btn green" id="inquiryEmailBtn">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('body').on('submit', '#inquiryEmailForm', function() {
            var inquiryEmailBtn = $('#inquiryEmailBtn')

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
            var url = base_url + 'inquiry/sendEmail'
            
            return new Promise((resolve, reject) => {
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
                        inquiryEmailBtn.attr('disabled', true)
                        inquiryEmailBtn.html('Sending ...')
                    },
                    complete: function() {
                        inquiryEmailBtn.attr('disabled', false)
                        setTimeout(function() {
                            inquiryEmailBtn.html('Send')
                        }, 200)
                    }
                });
            }).then(
                function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt, 'Success');
                        $( '#inquiryEmailForm' ).each(function(){
                            this.reset();
                        });
                    } else {
                        AdminToastr.error(response.txt, 'Error');
                    }
                }
            )
        })
    })
</script>