<div class="block">
    <?php if($this->model_signup->hasPremiumPermission()): ?>
        <div class="block-header">
            <div class="title">
                <h2 class="comment-heading">Comments</h2>
                <div class="tag"><?= isset($comment) ? count($comment) : 0 ?></div>
            </div>
            <div class="group-radio">
                <span class="button-radio">
                    <input id="latest" name="latest" type="radio" checked>
                    <label for="latest">Latest</label>
                </span>
            </div>
        </div>
        <div class="writing">
            <form action="javascript:;" id="commentForm" method="POST" novalidate>
                <input type="hidden" name="_token" value="<?= $this->csrf_token; ?>" />
                <input type="hidden" name="comment[comment_userid]" value="<?= $this->userid ?>" />
                <input type="hidden" name="comment[comment_reference_type]" value="<?= isset($type) && $type ? $type : ''; ?>" />
                <input type="hidden" name="comment[comment_reference_id]" value="<?= isset($reference_id) && $reference_id ? $reference_id : 0;  ?>" />
                <input type="hidden" name="comment[comment_parent_id]" value="0" />

                <textarea class="form-control textarea" name="comment[comment_text]" maxlength="3000" required></textarea>
                <!-- autofocus -->
                <small class="invalid-feedback">Add something to send.</small>
                <div class="footer">
                    <div class="text-format">
                    </div>
                    <div class="group-button">
                        <button type="submit" class="btn btn-custom" id="sendBtn">Send</button>
                    </div>
                </div>
            </form>
            <small class="reply-text d-none"></small>&nbsp;<a class="remove-reply-text d-none" href="javascript:;"><i class="fa fa-close"></i></a>
        </div>
    <?php endif; ?>

    <div class="comment-section">
        <?php if (isset($comment) && count($comment) > 0) : ?>
            <?php foreach ($comment as $key => $value) : ?>

                <!-- ==== MAIN COMMENT START ==== -->

                <div class="comment">
                    <div class="user-banner">
                        <div class="user">
                            <div class="avatar">
                                <?php if (isset($value['signup_logo_image']) && $value['signup_logo_image']) : ?>
                                    <img src="<?= get_user_image($value['signup_logo_image_path'], $value['signup_logo_image']) ?>" class="lazy" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                <?php else : ?>
                                    <img src="<?= g('images_root') . 'user.png' ?>" class="lazy" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                <?php endif; ?>
                                <span class="stat <?= $value['signup_info_isonline'] ? 'green' : 'grey' ?>"></span>
                            </div>
                            <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                                <h5 class="comment-name m-0"><?= $this->model_signup->signupName($value, false); ?></h5>
                            <?php else : ?>
                                <h5 class="comment-name m-0"><?= $this->model_signup->signupName($value, true); ?></h5>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="content">
                        <p><?= $value['comment_text']; ?></p>
                    </div>

                    <!-- ==== REACTION COUNTER START ==== -->
                    <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                        <div id="reactionCounter-<?= $value['comment_id'] ?>">
                            <?php $reactionCount = $this->model_comment_reaction->get_comment_reactions($value['comment_id']); ?>
                            <a data-fancybox data-animation-duration="700" data-src="#animatedModal" href="javascript:;" class="reactionCounterNumber" data-reference_id="<?= $value['comment_reference_id'] ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_type="<?= $type; ?>">
                                <?php if (!empty($reactionCount) && $reactionCount['reaction_count'] > 0) : ?>
                                    <?php $limiter = 0; ?>
                                    <?php asort($reactionCount['top_reactions']); ?>
                                    <?php foreach ($reactionCount['top_reactions'] as $key_ => $value_) {
                                        if ($limiter <= 3) {
                                            $limiter++;
                                            echo '<img class="lazy emoji-img" src="' . g('site_global_emojis_root') . $key_ . '.png' . '" />';
                                        }
                                    }
                                    ?>
                                    <small>&nbsp;<?= thousandsCurrencyFormat($reactionCount['reaction_count']); ?></small>
                                <?php endif; ?>
                            </a>
                        </div>

                    <?php endif; ?>
                    <!-- ==== REACTION COUNTER END ==== -->

                    <!-- ==== MAIN COMMENT REACTION AREA START ==== -->
                    <div class="footer">
                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                            <div class="reactions">
                                <div class="box">
                                    <label for="like" class="label-reactions reactionid-<?= $value['comment_id'] ?>">
                                        <?php $myReaction = $this->model_comment_reaction->get_comment_reaction($value['comment_id'], $this->userid); ?>
                                        <?php if (!empty($myReaction)) : ?>
                                            <?= '<img class="lazy emoji-img" data-reaction="' . $myReaction['comment_reaction_text'] . '" src="' . g('site_global_emojis_root') . $myReaction['comment_reaction_text'] . '.png' . '" />' ?>
                                        <?php else : ?>
                                            <?= ucfirst(__(REACTION_LIKE)) ?>
                                        <?php endif; ?>
                                    </label>
                                    <div class="react-mn-dv">
                                        <button class="reaction reaction-like" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_LIKE) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_LIKE) ?></span>
                                        </button>
                                        <button class="reaction reaction-love" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_LOVE) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_LOVE) ?></span>
                                        </button>
                                        <button class="reaction reaction-haha" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_HAHA) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_HAHA) ?></span>
                                        </button>
                                        <button class="reaction reaction-wow" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_WOW) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_WOW) ?></span>
                                        </button>
                                        <button class="reaction reaction-sad" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_SAD) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_SAD) ?></span>
                                        </button>
                                        <button class="reaction reaction-angry" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_ANGRY) ?>" data-comment_id="<?= $value['comment_id'] ?>" data-reference_id="<?= $value['comment_reference_id'] ?>">
                                            <span class="legend-reaction"><?= __(REACTION_ANGRY) ?></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        <?php endif; ?>

                        <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                            <div class="divider"></div>
                            <a href="javascript:;" class="replyBtn" data-id="<?= $value['comment_id'] ?>" data-name="<?= $this->model_signup->signupName($value, false); ?>">Reply</a>
                        <?php endif; ?>

                        <div class="divider"></div>
                        <span class="is-mute"><?= timeago($value['comment_createdon']) ?></span>

                    </div>
                    <!-- ==== MAIN COMMENT REACTION AREA END ==== -->

                    <?php foreach ($this->model_comment->get_comment_replies($value['comment_reference_id'], $value['comment_id']) as $key_r => $value_r) : ?>

                        <!-- ==== REPLIES TO COMMENTS START ==== -->

                        <div class="reply comment">
                            <div class="user-banner">
                                <div class="user">
                                    <div class="avatar">
                                        <?php if (isset($value_r['signup_logo_image']) && $value_r['signup_logo_image']) : ?>
                                            <img src="<?= get_user_image($value_r['signup_logo_image_path'], $value_r['signup_logo_image']) ?>" class="lazy" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                        <?php else : ?>
                                            <img src="<?= g('images_root') . 'user.png' ?>" class="lazy" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                        <?php endif; ?>
                                        <span class="stat <?= $value_r['signup_info_isonline'] ? 'green' : 'grey' ?>"></span>
                                    </div>
                                    <?php if ($this->userid > 0 && ($this->model_signup->hasPremiumPermission())) : ?>
                                        <h5 class="comment-name"><?= $this->model_signup->signupName($value_r, false); ?></h5>
                                    <?php else : ?>
                                        <h5 class="comment-name"><?= $this->model_signup->signupName($value_r, true); ?></h5>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="content">
                                <p><?= $value_r['comment_text']; ?></p>
                            </div>

                            <!-- ==== REPLIES REACTION COUNTER START ==== -->
                            <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                <div class="repliesCounter" id="reactionCounter-<?= $value_r['comment_id'] ?>">
                                    <?php $reactionCount = $this->model_comment_reaction->get_comment_reactions($value_r['comment_id']); ?>
                                    <a data-fancybox data-animation-duration="700" data-src="#animatedModal" href="javascript:;" class="reactionCounterNumber" data-reference_id="<?= $value_r['comment_reference_id'] ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_type="<?= $type; ?>">
                                        <?php if (!empty($reactionCount) && $reactionCount['reaction_count'] > 0) : ?>
                                            <?php $limiter = 0; ?>
                                            <?php asort($reactionCount['top_reactions']); ?>
                                            <?php foreach ($reactionCount['top_reactions'] as $key_ => $value_) {
                                                if ($limiter <= 3) {
                                                    $limiter++;
                                                    echo '<img class="lazy emoji-img" src="' . g('site_global_emojis_root') . $key_ . '.png' . '" />';
                                                }
                                            }
                                            ?>
                                            <small>&nbsp;<?= thousandsCurrencyFormat($reactionCount['reaction_count']); ?></small>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <!-- ==== REPLIES REACTION COUNTER END ==== -->

                            <!-- ==== REPLIES REACTION AREA START ==== -->
                            <div class="footer">
                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>

                                    <div class="reactions">
                                        <div class="box">
                                            <label for="like" class="label-reactions reactionid-<?= $value_r['comment_id'] ?>">
                                                <?php $myReaction = $this->model_comment_reaction->get_comment_reaction($value_r['comment_id'], $this->userid); ?>
                                                <?php if (!empty($myReaction)) : ?>
                                                    <?= '<img class="lazy emoji-img" data-reaction="' . $myReaction['comment_reaction_text'] . '" src="' . g('site_global_emojis_root') . $myReaction['comment_reaction_text'] . '.png' . '" />' ?>
                                                <?php else : ?>
                                                    <?= ucfirst(__(REACTION_LIKE)) ?>
                                                <?php endif; ?>
                                            </label>
                                            <div class="react-mn-dv">
                                                <button class="reaction reaction-like" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_LIKE) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_LIKE) ?></span>
                                                </button>
                                                <button class="reaction reaction-love" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_LOVE) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_LOVE) ?></span>
                                                </button>
                                                <button class="reaction reaction-haha" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_HAHA) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_HAHA) ?></span>
                                                </button>
                                                <button class="reaction reaction-wow" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_WOW) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_WOW) ?></span>
                                                </button>
                                                <button class="reaction reaction-sad" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_SAD) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_SAD) ?></span>
                                                </button>
                                                <button class="reaction reaction-angry" data-type="<?= $type ?>" data-reaction="<?= __(REACTION_ANGRY) ?>" data-comment_id="<?= $value_r['comment_id'] ?>" data-reference_id="<?= $value_r['comment_reference_id'] ?>">
                                                    <span class="legend-reaction"><?= __(REACTION_ANGRY) ?></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->model_signup->hasPremiumPermission()) : ?>
                                    <div class="divider"></div>
                                    <a href="javascript:;" class="replyBtn" data-id="<?= $value['comment_id'] ?>" data-name="<?= $this->model_signup->signupName($value_r, false); ?>">Reply</a>
                                <?php endif; ?>

                                <div class="divider"></div>
                                <span class="is-mute"><?= timeago($value_r['comment_createdon']) ?></span>
                            </div>
                            <!-- ==== REPLIES REACTION AREA END ==== -->

                        </div>

                        <!-- REPLIES TO COMMENTS END -->

                    <?php endforeach; ?>

                </div>

                <!-- ==== MAIN COMMENT END ==== -->

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="grid">
  <div style="display: none;" id="animatedModal" class="animated-modal">
        <input type="hidden" name="modal_reference_id" />
        <input type="hidden" name="modal_comment_id" />
        <input type="hidden" name="type" value="<?= isset($type) && $type ? $type : ''; ?>" />

        <div class="">
            <!-- modal-dialog -->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <div class="who_react_modal">

                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_LIKE ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_LIKE ?>" src="<?= g('site_global_emojis_root') . REACTION_LIKE . '.png' ?>" />
                                </div>
                            </span>
                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_LOVE ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_LOVE ?>" src="<?= g('site_global_emojis_root') . REACTION_LOVE . '.png' ?>" />
                                </div>
                            </span>
                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_HAHA ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_HAHA ?>" src="<?= g('site_global_emojis_root') . REACTION_HAHA . '.png' ?>" />
                                </div>
                            </span>
                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_WOW ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_WOW ?>" src="<?= g('site_global_emojis_root') . REACTION_WOW . '.png' ?>" />
                                </div>
                            </span>
                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_SAD ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_SAD ?>" src="<?= g('site_global_emojis_root') . REACTION_SAD . '.png' ?>" />
                                </div>
                            </span>
                            <span class="pointer loadReactedUsers" data-reaction="<?= REACTION_ANGRY ?>" data-reference_type="<?= $type; ?>">
                                <div class="inline_act_emoji">
                                    <img class="lazy emoji-img" data-reaction="<?= REACTION_ANGRY ?>" src="<?= g('site_global_emojis_root') . REACTION_ANGRY . '.png' ?>" />
                                </div>
                            </span>
                        </div>
                    </h4>
                </div>
                <!-- set data -->
                <div class="modal-body">
                </div>
                <!-- set data -->
            </div>
        </div>
    </div>
</div>

<script>
    function get_comment_reaction_users(data) {

        var url = base_url + 'dashboard/custom/get_comment_reaction_users'

        new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: 'JSON',
                async: true,
                success: function(response) {
                    resolve(response)
                },
                complete: function(xhr, txt) {
                    $('.modal-body').html('')
                },
                beforeSend: function() {
                    // $('.modal-body').addClass('encodeSVGLoader')
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(textStatus + ": " + jqXHR.status + " " + errorThrown);
                }
            });
        }).then(
            function(response) {
                if (response.status) {
                    $('input[name=modal_reference_id]').val(data.reference_id)
                    $('input[name=modal_comment_id]').val(data.comment_id)
                    var reactionData = response.data;
                    if (reactionData.length > 0) {
                        $('.modal-body').removeClass('encodeSVGLoader')
                        $('.modal-body').append(response.html)
                    } else {
                        $('.modal-body').removeClass('encodeSVGLoader')
                        $('.modal-body').append('No reactions yet');
                    }
                } else {
                    $('.modal-body').removeClass('encodeSVGLoader')
                    $('.modal-body').append('No reactions yet');
                }
            }
        )
    }

    async function submitComment(event) {
        var data = $('#commentForm').serialize();
        var url = base_url + 'dashboard/custom/send_comment'

        return new Promise((resolve, reject) => {
            jQuery.ajax({
                url: url,
                type: "POST",
                data: data,
                async: true,
                dataType: "json",
                success: function (response) {
                    resolve(response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                },
                beforeSend: function () {
                    $('#sendBtn').attr('disabled', true)
                    $('#sendBtn').html('Sending ...')
                    // $('#sendBtn').html('Sending&nbsp;<img src="<?= g('images_root') . 'tail-spin.svg' ?>" width="20" />')
                },
                complete: function() {
                    $('#sendBtn').attr('disabled', false)
                    $('#sendBtn').html('Send')
                }
            });
        });
    }

    $(document).ready(function() {
        $('body').on('submit', '#commentForm', function(event) {
            event.preventDefault();
            if (!$('#commentForm')[0].checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
                $('#commentForm').addClass('was-validated');
                $('#commentForm').find(":invalid").first().focus();
                return false;
            } else {
                $('#commentForm').removeClass('was-validated');
            }

            submitComment(event).then(
                function(response) {
                    if (response.status) {
                        $(".comment-section").load(location.href + " .comment-section>*", function(){
                            $(".writing").load(location.href + " .writing>*", "");
                        });
            
                        $('.reply-text').addClass('d-none')
                        $('.remove-reply-text').addClass('d-none')
                        $('#commentForm').each(function() {
                            this.reset();
                        });
            
                    } else {
                        swal("Error", response.txt, 'error')
                    }
                }
            );
        })

        $('body').on('click', '.replyBtn', function() {
            var reference_id = $(this).data('id')
            var name = $(this).data('name')
            $('input[name="comment[comment_parent_id]"]').val(reference_id)
            $('.reply-text').removeClass('d-none')
            $('.remove-reply-text').removeClass('d-none')

            $('.reply-text').html('Reply to ' + name)
            $('.textarea').focus();
        })

        $('body').on('click', '.remove-reply-text', function() {
            $('input[name="comment[comment_parent_id]"]').val(0)
            $('.reply-text').addClass('d-none')
            $('.remove-reply-text').addClass('d-none')
        })

        $('body').on('click', '.reaction', function() {
            var reference_id = $(this).data('reference_id');
            var comment_id = $(this).data('comment_id');
            var reaction = $(this).data('reaction');
            var type = $(this).data('type');

            if ($(".reactionid-" + comment_id).find('img').length) {
                if ($(".reactionid-" + comment_id).find('img').data('reaction') == $(this).data('reaction')) {
                    reaction = '';
                }
            }

            switch (reaction) {
                case '<?= __(REACTION_LIKE) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_LIKE ?>" src="<?= g('site_global_emojis_root') . REACTION_LIKE . '.png' ?>" />')
                    break;
                case '<?= __(REACTION_LOVE) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_LOVE ?>" src="<?= g('site_global_emojis_root') . REACTION_LOVE . '.png' ?>" />')
                    break;
                case '<?= __(REACTION_HAHA) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_HAHA ?>" src="<?= g('site_global_emojis_root') . REACTION_HAHA . '.png' ?>" />')
                    break;
                case '<?= __(REACTION_WOW) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_WOW ?>" src="<?= g('site_global_emojis_root') . REACTION_WOW . '.png' ?>" />')
                    break;
                case '<?= __(REACTION_SAD) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_SAD ?>" src="<?= g('site_global_emojis_root') . REACTION_SAD . '.png' ?>" />')
                    break;
                case '<?= __(REACTION_ANGRY) ?>':
                    $('.reactionid-' + comment_id).html('<img class="lazy emoji-img" data-reaction="<?= REACTION_ANGRY ?>" src="<?= g('site_global_emojis_root') . REACTION_ANGRY . '.png' ?>" />')
                    break;
                default:
                    $('.reactionid-' + comment_id).html('<?= ucfirst(__(REACTION_LIKE)) ?>')
            }
            var data = {
                _token: '<?= $this->csrf_token ?>',
                reference_id: reference_id,
                comment_id: comment_id,
                reaction: reaction,
                type: type
            }

            new Promise((resolve, reject) => {
                jQuery.ajax({
                    url: base_url + 'dashboard/custom/comment_reaction',
                    type: "POST",
                    data: data,
                    async: true,
                    dataType: "json",
                    success: function (response) {
                        resolve(response);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus + ": " + jqXHR.status + " " + errorThrown);
                    },
                });
            }).then(
                function(response) {
                    if (response.status) {
                        $("#reactionCounter-" + comment_id).load(location.href + " #reactionCounter-" + comment_id + ">*", "");
                    } else {
                        swal("Error", response.txt, 'error')
                    }
                }
            )
        })

        $('body').on('click', '.reactionCounterNumber', function() {
            var reference_id = $(this).data('reference_id')
            var comment_id = $(this).data('comment_id')
            var type = $(this).data('reference_type')

            var data = {
                _token: '<?= $this->csrf_token ?>',
                reference_id: reference_id,
                comment_id: comment_id,
                type: type
            }

            //
            get_comment_reaction_users(data);
        });

        $('body').on('click', '.loadReactedUsers', function() {
            var reference_id = $('input[name=modal_reference_id]').val()
            var comment_id = $('input[name=modal_comment_id]').val()
            var type = $(this).data('reference_type'); //$('input[name=modal_type]').val()
            var reaction = $(this).data('reaction')

            var data = {
                _token: '<?= $this->csrf_token ?>',
                reference_id: reference_id,
                comment_id: comment_id,
                type: type,
                reaction: reaction
            }

            //
            get_comment_reaction_users(data);
        })

        $('.fancybox-close-small').on('click', function() {
            $('input[name=modal_reference_id]').val('')
            $('input[name=modal_comment_id]').val('')
        })
    })
</script>