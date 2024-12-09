<div class="dashboard-content">
    <i class="fa-regular fa-paper-plane"></i>
    <h4><?= __('Chat') ?></h4>
    <hr>
    <div class="row">
        <div class="userArea col-4 py-3 px-0 shadow">
            <table>
                <tr>
                    <td>
                        <div class="form-group has-search">
                            <span class="fa fa-search form-control-feedback"></span>
                            <input type="text" class="form-control" placeholder="Search">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="userbox">
                            <img src="https://picsum.photos/50/50/?random=1" class="userAreaImg" />
                            <div>
                                <p>Maine Coon</p>
                                <p>Lorem ipsum dolor sit ...</p>
                            </div>
                        </div>
                    </td>
                </tr>

            </table>
        </div>
        <div class="chatArea col-8 px-0 shadow">
            <table>
                <tr>
                    <td>
                        <div class="name text-dark">
                            <h6 class="mb-0"><span>Maine Coon</span>
                                <!-- <i class="fas fa-volume-down"></i> -->
                            </h6>
                            <!-- <div class="ic">
                                <i class="fa fa-search"></i>
                                <i class="fas fa-phone-alt"></i>
                                <i class="fas fa-bars"></i>
                            </div> -->
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="chat">
                            <div class="other">
                                <img src="https://picsum.photos/50/50/?random=1" class="chatAreaImg" />
                                <div class="dialog">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Error, dolore?
                                    <div class="time-check">
                                        <p>12:34</p>
                                    </div>
                                </div>
                            </div>
                            <div class="self">
                                <div class="dialog">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Error, dolore?
                                    <div class="time-check float-right">
                                        <p>12:34 <span><i class="fa fa-check"></i></span></p>
                                    </div>
                                </div>
                            </div>

                            <div class="other">
                                <img src="https://picsum.photos/50/50/?random=1" class="chatAreaImg" />
                                <div class="dialog">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Error, dolore?
                                    <div class="time-check">
                                        <p>12:34</p>
                                    </div>

                                </div>
                            </div>

                            <div class="self">
                                <div class="dialog">
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Error, dolore?
                                    <div class="time-check float-right">
                                        <p>12:34 <span><i class="fa fa-check"></i></span></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="msg">
                            <div class="emoji-picker-container">
                                <input type="text" placeholder="Send message!" class="form-control" data-emojiable="true" />
                            </div>
                            <div class="ic">
                                <i class="fas fa-plus"></i>
                                <!-- <i class="fas fa-camera"></i> -->
                                <i class="far fa-image"></i>
                                <!-- <span class="float-end"><i class="far fa-smile"></i></span> -->
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <!-- <script src="table.js"></script> -->
</div>

<script>
    $(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
            emojiable_selector: '[data-emojiable=true]',
            assetsPath: base_url + 'assets/front_assets/dashboard/emoji-picker-main/lib/img',
            popupButtonClasses: 'fa fa-smile-o' // far fa-smile if you're using FontAwesome 5
        });
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
    });
</script>