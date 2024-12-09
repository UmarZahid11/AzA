<div class="dashboard-content">
    <div style="float:right;">
        <a href="<?= l('dashboard/home/inbox') ?>" class="mail-box-btn"><i class="fa-regular fa-envelope"></i> <?= __('inbox') ?></a>
        <a href="<?= l('dashboard/home/sent') ?>" class="mail-box-btn"><i class="fa-regular fa-paper-plane"></i> <?= __('Sent') ?></a>
    </div>
    <i class="fa-regular fa-envelope"></i>
    <h4><?= __('New Message') ?></h4>
    <hr>
    <div class="compose-mail-box">
        <form action="javascript:;" method="POST" class="messageForm">
            <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
            <div class="cc-boxxx">
                <h5><?= __('Cc') ?></h5>
                <div class="cc-flex-added"></div>
            </div>
            <div class="bcc-boxxx">
                <h5><?= __('Bcc') ?></h5>
                <div class="cc-flex-added"></div>
            </div>
            <div class="cc-bcc">
                <input type="hidden" name="chat_signupid1" value="<?= isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : '' ?>" />
                <input type="text" name="chat_signupid2" class="form-control" id="email" placeholder="<?= __('To') ?>" />
                <div>
                    <button class="cc-btn"><?= __('Cc') ?></button>
                    <button class="bcc-btn"><?= __('Bcc') ?></button>
                    <div class="add-addtion">
                        <input type="text">
                        <span>Add</span>
                    </div>
                </div>
            </div>
            <input type="text" name="chat[chat_subject]" placeholder="<?= __('Subject') ?>">
            <textarea id="message" name="message" contenteditable="true" style="height: 400px;"><?= __('Start writing here') ?></textarea>
            <div class="btns-wrapp-cpm mt-5">
                <button class="btn btn-custom" id="messageFormBtn"><?= __('Send') ?></button>
            </div>
        </form>
    </div>
</div>

<script>
    // email autocomplete
    $(function() {
        $("#email").autocomplete({
            // appendTo: $("#email"),
            source: base_url + 'dashboard/custom/emailDrop',
            select: function(event, ui) {
                event.preventDefault();
                $("#email").val(ui.item.id);
            }
        });
    });

    $(document).ready(function() {
        var messageEditor;

        // initiate Ckeditor
        // ckeditor for message
        ClassicEditor
            .create(document.querySelector('#message'))
            .then(editor => {
                messageEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });


        $('.messageForm').on('submit', function() {
            var messageFormBtn = '#messageFormBtn'
            var data = $(this).serialize();
            var url = base_url + 'dashboard/custom/sendMessage'

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
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $(messageFormBtn).attr('disabled', true)
                        $(messageFormBtn).html('Sending ...')
                    },
                    complete: function() {
                        $(messageFormBtn).attr('disabled', false)
                        $(messageFormBtn).html('Send')
                    }
                })
    		}).then(
    		    function(response) {
                    if (response.status) {
                        AdminToastr.success(response.txt)
                        $('.messageForm').each(function() {
                            this.reset();
                        });
                        messageEditor.setData("")
                        if(response.notify) {
                            // $(".notification-wrap").load(location.href+" .notification-wrap>*","");
                        }
                    } else {
                        AdminToastr.error(response.txt)
                    }
    		    }
		    )
        })

    })
</script>