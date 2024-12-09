<div class="dashboard-content">
    <div style="float:right;">
        <a href="<?= l('dashboard/home/compose') ?>" class="mail-box-btn"><i class="fa-regular fa-pen-to-square"></i> <?= __('Compose') ?></a>
        <a href="<?= l('dashboard/home/inbox') ?>" class="mail-box-btn"><i class="fa-regular fa-envelope"></i> <?= __('inbox') ?></a>
    </div>
    <i class="fa-regular fa-envelope-open"></i>
    <h4><?= $chat['chat_subject'] ?></h4>
    <hr />

    <?php if (isset($chat['message']) && count($chat['message']) > 0) : ?>
        <input type="hidden" name="chat_message_count" value="<?= $chat['count'] ?>" />
        <input type="hidden" name="chat_id" value="<?= $chat['chat_id'] ?>" />

        <?php foreach ($chat['message'] as $key => $value) : ?>

            <div class="inbox-msg-tile <?= $key == 0 ? '' : 'closed'; ?>">
                <div class="tilele-hd">
                    <div class="sndr-info" data-id="<?= $value['chat_message_id'] ?>">
                        <?php if ($value['chat_message_sender'] == $this->userid) : ?>
                            <img src="<?= get_user_image($this->user_data['signup_logo_image_path'], $this->user_data['signup_logo_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                        <?php elseif ($value['chat_message_receiver'] == $this->userid) : ?>
                            <img src="<?= get_user_image($chat['signup_logo_image_path'], $chat['signup_logo_image']) ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                        <?php else: ?>
                            <img src="<?= g('images_root') . 'user.png' ?>" alt="" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                        <?php endif; ?>
                        <div>
                            <?php if ($value['chat_message_sender'] == $this->userid) : ?>
                                <p><b><?= ucfirst($this->user_data['signup_firstname']) . ' ' . ucfirst($this->user_data['signup_lastname']) ?> </b> <span>&#60;<?= $this->user_data['signup_email'] ?>&#62;</span></p>
                                <p>
                                    <?= __('to') . ': ' . ucfirst($chat['signup_firstname']) . ' ' . ucfirst($chat['signup_lastname']) ?>
                                </p>
                            <?php elseif ($value['chat_message_receiver'] == $this->userid) : ?>
                                <p><b><?= ucfirst($chat['signup_firstname']) . ' ' . ucfirst($chat['signup_lastname']) ?> </b> <span>&#60;<?= $chat['signup_email'] ?>&#62;</span></p>
                                <p>
                                    <?= __('to') . ': ' . ucfirst($this->user_data['signup_firstname']) . ' ' . ucfirst($this->user_data['signup_lastname']) ?>
                                </p>
                            <?php else: ?>
                                <p><b><?= __("Not available") ?></b> <span>&#60;<?= __("Email unavailable") ?>&#62;</span></p>
                                <p>
                                    <?= __('to') . ': ' . ucfirst($chat['signup_firstname']) . ' ' . ucfirst($chat['signup_lastname']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mail-actions">
                        <span class="time-ml"><b><?= date("H:i", strtotime($value['chat_message_createdon'])) ?></b> <?= $value['chat_message_createdon'] && isValidDate($value['chat_message_createdon'], 'Y-m-d H:i:s') ? ('(' . timeago(date('Y-m-d H:i:s', strtotime($value['chat_message_createdon']))) . ')') : '' ?></span>
                        <!-- <a href="#" class="favorite"><i class="fa-regular fa-star"></i></a> -->
                        <a href="javascript:;" class="reply-this" data-id="<?= $value['chat_message_id'] ?>">
                            <i class="fa-solid fa-reply"></i>
                        </a>
                        <!-- <div class="drop-option-ml">
                            <a href="#"><i class="fa-solid fa-caret-down"></i></a>
                            <div>
                                <a href="#"><i class="fa-solid fa-reply-all"></i> <?= __('Reply All') ?></a>
                                <a href="#"><i class="fa-solid fa-right"></i> <?= __('Forward') ?></a>
                            </div>
                        </div> -->
                    </div>
                </div>
                <!-- <div class="alert-note">Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime mollitia, molestiae quas vel sint commodi repudiandae</div> -->
                <div class="msg-body">
                    <?php echo html_entity_decode($value['chat_message_text']); ?>

                    <?php if (isset($value['chat_message_response_id']) && $value['chat_message_response_id']) : ?>
                        <?php $chat_response_to = $this->model_chat_message->find_one_active(
                            array(
                                'where' => array(
                                    'chat_message_id' => $value['chat_message_response_id']
                                ),
                                'joins' => array(
                                    0 => array(
                                        'table' => 'chat',
                                        'joint' => 'chat.chat_id = chat_message.chat_message_chat_id',
                                        'type'  => 'both'
                                    )
                                )
                            )
                        );
                        ?>
                        <?php if (!empty($chat_response_to)) : ?>
                            <?php $sender_details = $this->model_signup->find_by_pk($chat_response_to['chat_message_sender']); ?>
                            <hr>
                            <p><b><?= ucfirst($sender_details['signup_firstname']) . ' ' . ucfirst($sender_details['signup_lastname']) ?>&nbsp;
                                    <a href="mailto:<?= $sender_details['signup_email'] ?>">&#60;<?= $sender_details['signup_email'] ?? '' ?>&#62;</a></b>:</p>
                            <div class="bdr-left">
                                <?php echo html_entity_decode($chat_response_to['chat_message_text']); ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="btm-msg">
                    <p><small>click here to
                            <a href="javascript:;" class="reply-this" data-id="<?= $value['chat_message_id'] ?>">
                                reply
                            </a>
                            <!-- or <a href="#">forward</a> -->
                        </small>
                    </p>
                    <div>
                        <a href="javascript:;" class="reply-this" data-id="<?= $value['chat_message_id'] ?>">
                            <i class="fa-solid fa-reply"></i>
                        </a>
                    </div>
                </div>
                <form class="replyform" action="javascript:;" method="POST" data-id="<?= $value['chat_message_id'] ?>">
                    <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                    <input type="hidden" class="" name="chat_message[chat_message_sender]" value="<?= $this->userid ?>" />
                    <input type="hidden" class="" name="chat_message[chat_message_chat_id]" value="<?= $chat['chat_id'] ?>" />
                    <?php if ($chat['chat_signup1'] == $this->userid) : ?>
                        <input type="hidden" class="" name="chat_message[chat_message_receiver]" value="<?= $chat['chat_signup2'] ?>" />
                    <?php elseif ($chat['chat_signup2'] == $this->userid) : ?>
                        <input type="hidden" class="" name="chat_message[chat_message_receiver]" value="<?= $chat['chat_signup1'] ?>" />
                    <?php endif; ?>
                    <input type="hidden" class="" name="chat_message[chat_message_response_id]" value="<?= $value['chat_message_id'] ?>" />
                    <input type="hidden" class="" name="chat_message[chat_message_parent]" value="<?= $value['chat_message_id'] ?>" />
                    <textarea name="chat_message[chat_message_text]" class="form-control d-none replyTextarea area-<?= $value['chat_message_id'] ?>"></textarea>
                    <button class="btn btn-custom mt-3 d-none replybutton replybutton-<?= $value['chat_message_id'] ?>"><?= __('Send') ?></button>
                </form>
            </div>
        <?php endforeach; ?>
        <!-- <div class="oldr-msgs">
            <a href="javascript:;" class="show-older">20 older messages</a>
        </div> -->
    <?php endif; ?>

</div>
<script>
    $(document).ready(function() {

        var messageEditor = [];

        $('body').on('click', '.reply-this', function() {
            var id = $(this).data('id');
            var ele = $('.area-' + id)
            var element = '.area-' + id
            if (ele.hasClass('d-none')) {
                initiateCK(element, id)
                //
                ele.removeClass('d-none')
                $('.replybutton-' + id).removeClass('d-none')
                ele.focus()
            } else {
                //
                ele.addClass('d-none')
                $('.replybutton-' + id).addClass('d-none')
                //
                destroyCk(messageEditor[id])
            }
        })

        function initiateCK(element, id) {
            ClassicEditor
                .create(document.querySelector(element))
                .then(editor => {
                    messageEditor[id] = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function destroyCk(editor) {
            editor.destroy()
                .catch(error => {
                    console.log(error);
                });
        }

        $('body').on('click', '.sndr-info', function() {
            var id = $(this).data('id')
            if ($(this).parent().parent().hasClass('closed')) {
                $(this).parent().parent().removeClass('closed');
            } else {
                $(this).parent().parent().addClass('closed');
                //
                destroyCk(messageEditor[id])
                $('.area-' + id).addClass('d-none')
                $('.replybutton-' + id).addClass('d-none')
            }
        })

        $('body').on('submit', '.replyform', function() {

            var id = $(this).data('id');
            if (messageEditor[id].getData() == '<p>&nbsp;</p>') {
                AdminToastr.error('<?= __('Cannot send an empty message') ?>')
                return false;
            } else {
                
                var data = $(this).serialize();
                var url = base_url + 'dashboard/custom/message_reply'

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
                            $('.replybutton-' + id).attr('disabled', true)
                            $('.replybutton-' + id).html('Sending ...')
                        },
                        complete: function() {
                            $('.replybutton-' + id).attr('disabled', false)
                            $('.replybutton-' + id).html('Send')
                        }
                    })
        		}).then(
        		    function(response) {
                        if (response.status) {
                            AdminToastr.success(response.txt)
                            $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                            if(response.notify) {
                                // $(".notification-wrap").load(location.href+" .notification-wrap>*","");
                            }
                        } else {
                            AdminToastr.error(response.txt)
                        }
        		    }
    		    )
            }
        })
    })

    setInterval(function() {
        var chat_count = $('input[name=chat_message_count]').val()
        var url = base_url + 'dashboard/custom/chatCountCheck';
        var data = {
            chat_id: $('input[name=chat_id]').val()
        }

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
            })
		}).then(
		    function(response) {
		        if (response.count != chat_count) {
                    $(".dashboard-content").load(location.href + " .dashboard-content>*", "");
                }
		    }
	    )
    }, 1000)
</script>