<style>
    hr {
        margin: 5px auto;
        width: 60%;
    }

    .changed-direction .status-circle {
        bottom: 10px;
        left: 50px;
    }

    .changed-direction-top .status-circle {
        left: -26px;
    }
</style>
<div class="dashboard-content">

    <div class="container">
        <div class="row no-gutters">
            <div class="col-md-4 border-right">
                <!-- search area -->
                <div class="search-box-chat">
                    <i class="fa-regular fa-magnifying-glass"></i>
                    <form class="searchForm" action="javascript:;">
                        <input type="text" class="form-control" name="search_term" value="<?= isset($search) ? $search : '' ?>" />
                    </form>
                    <a data-fancybox data-animation-duration="700" data-src="#chatListModal" href="javascript:;" class="btn"><i class="fa fa-plus-circle"></i></a>
                    <div class="grid">
                        <div style="display: none;" id="chatListModal" class="animated-modal">
                            <h4>My followers</h4>
                            <?php if (isset($signup_follower) && is_array($signup_follower)) : ?>
                                <?php foreach ($signup_follower as $key => $value) : ?>
                                    <a href="javascript:;" id="start_chat<?= $value['signup_id'] ?>" data-id="<?= $value['signup_id'] ?>" class="start_chat d-block">
                                        <div class="friend-drawer">
                                            <img class="chat-image" src="<?= get_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" alt="" />
                                            <div class="text">
                                                <p><?= $this->model_signup->profileName($value, FALSE) ?></p>
                                                <p><?= strip_string($value['signup_profession']) ?></p>
                                            </div>
                                        </div>
                                    </a>
                                    <hr />
                                <?php endforeach; ?>
                            <?php else : ?>
                                <small>You donot have any follower yet.</small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <!-- search area -->

                <!-- chat listing area -->
                <?php if (isset($message) && is_array($message) && count($message)) : ?>
                    <div class="chat-listing">
                        <?php foreach ($message as $key => $value) : ?>
                            <hr />
                            <a href="<?= l('dashboard/message/index/' . JWT::encode($value['chat_id']) . '/' . $page . '/' . $limit . '/' . $search) ?>">
                                <div class="friend-drawer friend-drawer--onhover icon-container changed-direction">
                                    <img class="chat-image" src="<?= get_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" alt="" />
                                    <div class="status-circle" style="<?= (isset($value['signup_info_isonline']) && $value['signup_info_isonline']) ? 'background-color: green' : ''; ?>"></div>
                                    <div class="text">
                                        <p><?= $this->model_signup->profileName($value, FALSE) ?></p>
                                        <p class="text-muted font-12">
                                            <?= strip_string($this->model_chat_message->lastMessage($value['chat_id'])) ?>
                                        </p>
                                    </div>
                                    <span class="time text-muted font-10"><?= validateDate($value['chat_updatedon'], 'Y-m-d H:i:s') ? timeago($value['chat_updatedon']) : (validateDate($value['chat_createdon'], 'Y-m-d H:i:s') ? timeago($value['chat_createdon']) : '') ?></span>
                                </div>
                            </a>
                            <hr />
                        <?php endforeach; ?>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12">

                            <nav aria-label="Page navigation example mt-5">
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?php if ($page <= 1) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page <= 1) {
                                                                                                        echo '#';
                                                                                                    } else {
                                                                                                        echo l('dashboard/message/index/') . $chat_id . '/' . $prev . '/' . $limit . '/' . $search;
                                                                                                    } ?>"><i class="far fa-chevron-left"></i></a>
                                    </li>

                                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                        <li class="page-item <?php if ($page == $i) {
                                                                    echo 'active';
                                                                } ?>">
                                            <a class="page-link" href="<?= l('dashboard/message/index/') . $chat_id . '/' . $i . '/' . $limit . '/' . $search; ?>"> <?= $i; ?> </a>
                                        </li>
                                    <?php endfor; ?>

                                    <li class="page-item <?php if ($page >= $totalPages) {
                                                                echo 'disabled';
                                                            } ?>">
                                        <a class="page-link icon-back" style="padding: 11px;" href="<?php if ($page >= $totalPages) {
                                                                                                        echo '#';
                                                                                                    } else {
                                                                                                        echo l('dashboard/message/index/') . $chat_id . '/' . $next . '/' . $limit . '/' . $search;
                                                                                                    } ?>"><i class="far fa-chevron-right"></i></a>
                                    </li>
                                </ul>
                            </nav>

                        </div>
                    </div>
                <?php else : ?>
                    <small>No chat available</small>
                <?php endif; ?>
                <!-- chat listing area -->

            </div>

            <div class="col-md-8">
                <input type="hidden" name="chat_message_count" value="<?= isset($chat_message_count) ? $chat_message_count : 0 ?>" data-id="<?= isset($chat_detail) && $chat_detail ? $chat_detail['chat_id'] : 0 ?>" />

                <?php if (isset($chat_detail) && $chat_detail) : ?>
                    <div class="friend-drawer no-gutters friend-drawer--grey">
                        <img class="chat-image" src="<?= get_image($chat_detail['signup_logo_image_path'], $chat_detail['signup_logo_image']) ?>" alt="" />
                        <div class="text icon-container changed-direction-top">
                            <a href="<?= l('dashboard/profile/detail/' . JWT::encode($chat_detail['signup_id'], CI_ENCRYPTION_SECRET) . '/' . $chat_detail['signup_type']) ?>"><p><?= $this->model_signup->profileName($chat_detail, FALSE) ?></p></a>
                            <p class="text-muted font-12"><?= strip_string($chat_detail['signup_profession']) ?></p>
                            <div class="status-circle" style="<?= (isset($chat_detail['signup_info_isonline']) && $chat_detail['signup_info_isonline']) ? 'background-color: green' : ''; ?>"></div>
                        </div>
                    </div>

                    <div class="chat-panel" id="chat-panel">
                        <?php
                        $display_date = '';
                        $previous_display_date = '';
                        ?>
                        <?php foreach ($chat_message as $key => $value) : ?>
                            <?php
                            $display_date = validateDate($value['chat_message_createdon'], 'Y-m-d H:i:s') ? date('d M, Y', strtotime($value['chat_message_createdon'])) : '';
                            if (!$previous_display_date) {
                                $previous_display_date = '';
                            }
                            ?>
                            <?php if (strtotime($previous_display_date) != strtotime($display_date)) : ?>
                                <p class="text-center font-10"><?= $display_date; ?></p>
                            <?php endif; ?>

                            <?php if ($value['chat_message_sender'] == $this->userid) : ?>
                                <div class="row no-gutters">
                                    <div class="col-md-12 colored-bubble">
                                        <div class="chat-bubble chat-bubble--right">
                                            <p><?= $value['chat_message_text'] ?></p>
                                            <span class="float-left font-10 text-dark"><?= validateDate($value['chat_message_createdon'], 'Y-m-d H:i:s') ? date('h:i a', strtotime($value['chat_message_createdon'])) : '' ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php else : ?>
                                <div class="row no-gutters">
                                    <div class="col-md-12">
                                        <div class="chat-bubble chat-bubble--left">
                                            <p><?= $value['chat_message_text'] ?></p>
                                            <span class="float-right font-10"><?= validateDate($value['chat_message_createdon'], 'Y-m-d H:i:s') ? date('h:i a', strtotime($value['chat_message_createdon'])) : '' ?></span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php $previous_display_date = $display_date; ?>
                        <?php endforeach; ?>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <form action="javascript:;" id="messageForm">
                                <input type="hidden" name="_token" />
                                <input type="hidden" name="chat[chat_reference_type]" value="<?= CHAT_REFERENCE_MESSAGE ?>" />
                                <input type="hidden" name="chat_message[chat_message_chat_id]" value="<?= isset($chat_detail['chat_id']) ? $chat_detail['chat_id'] : 0 ?>" />
                                <input type="hidden" name="chat_message[chat_message_sender]" value="<?= $this->userid ?>" />
                                <input type="hidden" name="chat_message[chat_message_receiver]" value="<?= $chat_detail['chat_signup1'] == $this->userid ? $chat_detail['chat_signup2'] : $chat_detail['chat_signup1'] ?>" />
                                <div class="chat-box-tray">
                                    <input type="text" class="form-control font-12" name="chat_message[chat_message_text]" autocomplete="off" placeholder="Type your message here..." maxlength="5000" />
                                    <button class="btn btn-custom" style="width:15%;" id="messageFormBtn">
                                        Send
                                        <!--<i class="fa fa-paper-plane"></i>-->
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="text-center mt-5">
                        <p>Select chat</p>
                        <p>OR</p>
                        <a data-fancybox data-animation-duration="700" data-src="#chatListModal" href="javascript:;" class="font-12"><i class="fa fa-circle-plus"></i> Start new</a>
                        <div class="grid">
                            <div style="display: none;" id="chatListModal" class="animated-modal">
                                <h4>My followers</h4>
                                <?php if (isset($signup_follower) && is_array($signup_follower)) : ?>
                                    <?php foreach ($signup_follower as $key => $value) : ?>
                                        <a href="javascript:;" id="start_chat<?= $value['signup_id'] ?>" data-id="<?= $value['signup_id'] ?>" class="start_chat d-block">
                                            <div class="friend-drawer">
                                                <img class="chat-image" src="<?= get_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" alt="" />
                                                <div class="text">
                                                    <p><?= $this->model_signup->profileName($value, FALSE) ?></p>
                                                    <p><?= strip_string($value['signup_profession']) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                        <hr />
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <small>You donot have any follower yet.</small>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        if ($('#chat-panel').length) {
            $("#chat-panel").scrollTop($("#chat-panel")[0].scrollHeight);
        }

        /**
         * Method messageCountRefresh
         *
         * @return void
         */
        async function messageCountRefresh() {
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
                'chat_id': $('input[name=chat_message_count]').data('id'),
                'chat_message_count': $('input[name=chat_message_count]').val()
            }
            var url = base_url + 'dashboard/message/messageCountRefresh';
			return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    }
                })
			})
        }

        async function refreshMessageResult(response) {
            if (response.status) {
                $(".dashboard-content").load(location.href + " .dashboard-content>*", function() {
                    $('[data-toggle="tooltip"]').tooltip({
                        html: true,
                    })
                    if ($('#chat-panel').length) {
                        $("#chat-panel").scrollTop($("#chat-panel")[0].scrollHeight);
                    }
                    $('input[name=chat_message_count]').val(response.chat_message_count)
                    setTimeout(() => {
                        messageCountRefresh().then(
                            function(response) {
                                refreshMessageResult(response)
                            }
                        )
                    }, 2000)
                });
            } else {
                setTimeout(() => {
                    messageCountRefresh().then(
                        function(response) {
                            refreshMessageResult(response)
                        }
                    )
                }, 2000)
            }
        }

        $(document).ready(function() {
            messageCountRefresh().then(
                function(response) {
                    refreshMessageResult(response)
                }
            )
        })

        /**
         * Method chatListingRefresh
         *
         * @return void
         */
        async function chatListingRefresh() {
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
            }
            var url = base_url + 'dashboard/message/chatListingRefresh';
			return new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    }
                })
			})
        }

        async function refreshChatList(response) {
            if (response.status) {
                $(".chat-listing").load(location.href + " .chat-listing>*", function() {
                    $('[data-toggle="tooltip"]').tooltip({
                        html: true,
                    })
                    if ($('#chat-panel').length) {
                        $("#chat-panel").scrollTop($("#chat-panel")[0].scrollHeight);
                    }
                    $('input[name="chat_message[chat_message_text]"]').focus()
                    setTimeout(() => {
                        chatListingRefresh().then(
                            function(response) {
                                refreshChatList(response)
                            }
                        )
                    }, 5000)
                });
            } else {
                setTimeout(() => {
                    chatListingRefresh().then(
                        function(response) {
                            refreshChatList(response)
                        }
                    )
                }, 5000)
            }
        }

        $(document).ready(function() {
            chatListingRefresh().then(
                function(response) {
                    refreshChatList(response)
                }
            )
        })
                
        $('body').on('click', '.start_chat', function() {
            var data = {
                '_token': $('meta[name=csrf-token]').attr("content"),
                'signup_id': $(this).data('id')
            }
            var url = base_url + 'dashboard/message/start'

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: url,
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function(response) {
                        resolve(response)
                    }
                })
			}).then(
			    function(response) {
        			if (response.status) {
                        location.href = response.redirect_url
                    } else {
                        AdminToastr.error(response.txt)
                    }
			    }
		    )
        })
        
        async function saveMessage() {
            $('input[name=_token]').val($('meta[name=csrf-token]').attr("content"))
            var data = $('#messageForm').serialize()
            var url = base_url + 'dashboard/message/save'
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
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                    beforeSend: function() {
                        $('#messageFormBtn').attr('disabled', true)
                        $('#messageFormBtn').html('Sending')
                    },
                    complete: function() {
                        $('#messageFormBtn').attr('disabled', false)
                        $('#messageFormBtn').html('Send')
                    }
                })
			})
        }

        $('body').on('submit', '#messageForm', function(e) {
            e.preventDefault()
            //
            if ($('input[name="chat_message[chat_message_text]"]').val() == '') {
                return false;
            }
            //
            saveMessage().then(
                function(response) {
                    if (response.status) {
                        $('input[name="chat_message[chat_message_text]"]').val('')
                    } else {
                        AdminToastr.error(response.txt)
                    }
                }
            )
        })

        $('body').on('submit', '.searchForm', function() {
            location.href = base_url + 'dashboard/message/index/' + '<?= $chat_id ?>' + '/' + '<?= $page ?>' + '/' + '<?= $limit ?>' + '/' + $('input[name=search_term]').val();
        })
    })
</script>