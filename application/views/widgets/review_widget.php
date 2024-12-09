<style>
    .star-cb-group {
        /* remove inline-block whitespace */
        font-size: 0;
        /* flip the order so we can use the + and ~ combinators */
        unicode-bidi: bidi-override;
        direction: rtl;
        /* the hidden clearer */
    }

    .star-cb-group * {
        font-size: 1.5rem !important;
    }

    .star-cb-group>input {
        display: none;
    }

    .star-cb-group>input+label {
        /* only enough room for the star */
        display: inline-block;
        overflow: hidden;
        text-indent: 9999px;
        width: 1em;
        white-space: nowrap;
        cursor: pointer;
    }

    .star-cb-group>input+label:before {
        display: inline-block;
        text-indent: -9999px;
        content: "☆";
        color: #888;
    }

    .star-cb-group>input:checked~label:before,
    .star-cb-group>input+label:hover~label:before,
    .star-cb-group>input+label:hover:before {
        content: "★";
        color: #8204aa;
        text-shadow: 0 0 1px #333;
    }

    .star-cb-group>.star-cb-clear+label {
        text-indent: -9999px;
        width: .5em;
        margin-left: -.5em;
    }

    .star-cb-group>.star-cb-clear+label:before {
        width: .5em;
    }

    .star-cb-group:hover>input+label:before {
        content: "☆";
        color: #888;
        text-shadow: none;
    }

    .star-cb-group:hover>input+label:hover~label:before,
    .star-cb-group:hover>input+label:hover:before {
        content: "★";
        color: #8204aa;
        text-shadow: 0 0 1px #333;
    }


    .rating-box {
        border: solid 1px #c1c1c1;
        margin: 0 auto;
        position: relative;
    }

    fieldset {
        border: none;
        padding: 5px 0px;
    }

    .rating-form input.text-field {
        font-size: 0.75rem;
    }

    .error {
        color: red;
        font-size: 12px;
    }

    /** rating summary */
    .reviews-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 0.3);
        padding: 0 0 20px 0;
    }

    .review {
        border: 1px solid transparent;
        border-radius: 5px;
        color: #777;
        display: flex;
        font-size: 12px;
        align-items: center;
        padding: 10px;
        margin: 5px 0;
    }

    .progress {
        background-color: rgba(100, 100, 100, 0.2);
        border-radius: 5px;
        position: relative;
        margin: 0 10px;
        height: 10px;
        width: 200px;
    }

    .progress-done {
        background: linear-gradient(to left, #8204aa, rgb(232 151 255));
        box-shadow: 0 3px 3px -5px rgb(242, 112, 156), 0 2px 5px rgb(242, 112, 156);
        border-radius: 5px;
        height: 10px;
        width: 0;
        transition: width 1s ease 0.3s;
    }

    .percent {
        color: #333;
    }

    /** rating summary */

    .profile-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
        margin-right: 10px;
    }

    .name-user {
        display: flex;
        flex-direction: column;
    }

    .name-user span {
        color: #979797;
        font-size: 0.8rem;
    }

    .profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .profile {
        display: flex;
        align-items: center;
    }
</style>
<hr />


<?php if ($this->model_signup->hasPremiumPermission()) : ?>

    <div class="rating-box card">

        <?php if (isset($user['signup_id']) && $this->userid != $user['signup_id']) : ?>

            <p>Rate <?= isset($review_type) && $review_type ? 'this ' . ($review_type == 'signup' ? 'profile' : $review_type) : '' ?></p>

            <?php if (!$this->model_review->review_exists($user['signup_id'], $this->userid)) : ?>

                <form class="rating-form" novalidate>
                    <input type="hidden" name="review[review_type]" value="<?= REVIEW_TYPE_SIGNUP; ?>" />
                    <input type="hidden" name="review[review_email]" value="<?= isset($this->user_data) && isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : ''; ?>" />
                    <input type="hidden" name="review[review_name]" value="<?= $this->model_signup->profileName(isset($this->user_data) ? $this->user_data : array(), FALSE); ?>" />
                    <input type="hidden" name="review[review_reference_id]" value="<?= isset($user) && isset($user['signup_id']) ? $user['signup_id'] : ''; ?>" />
                    <input type="hidden" name="review[review_reviewer_id]" value="<?= isset($this->user_data) && isset($this->user_data['signup_id']) ? $this->user_data['signup_id'] : ''; ?>" />
                    <fieldset>
                        <span class="star-cb-group">
                            <input type="radio" id="rating-5" name="review[review_rating]" value="5" /><label for="rating-5">5</label>
                            <input type="radio" id="rating-4" name="review[review_rating]" value="4" /><label for="rating-4">4</label>
                            <input type="radio" id="rating-3" name="review[review_rating]" value="3" /><label for="rating-3">3</label>
                            <input type="radio" id="rating-2" name="review[review_rating]" value="2" /><label for="rating-2">2</label>
                            <input type="radio" id="rating-1" name="review[review_rating]" value="1" /><label for="rating-1">1</label>
                            <input type="radio" id="rating-0" name="review[review_rating]" value="0" checked="checked" class="star-cb-clear" /><label for="rating-0">0</label>
                        </span>
                    </fieldset>
                    <fieldset>
                        <textarea name="review[review_description]" id="review" maxlength="100" class="form-control" placeholder="Write your review here" required></textarea>
                    </fieldset>
                    <span class="error"></span>
                    <fieldset>
                        <button type="submit" class="btn btn-custom" id="review_submit">Submit</button>
                    </fieldset>

                </form>
            <?php else: ?>
                <?php
                    $user_review = $this->model_review->user_review($user['signup_id'], $this->userid);
                ?>
                <form class="rating-form" novalidate>

                    <input type="hidden" name="review_id" value="<?= $user_review['review_id']; ?>" />
                    <input type="hidden" name="review[review_type]" value="<?= REVIEW_TYPE_SIGNUP; ?>" />
                    <input type="hidden" name="review[review_email]" value="<?= isset($this->user_data) && isset($this->user_data['signup_email']) ? $this->user_data['signup_email'] : ''; ?>" />
                    <input type="hidden" name="review[review_name]" value="<?= $this->model_signup->profileName(isset($this->user_data) ? $this->user_data : array(), FALSE); ?>" />
                    <input type="hidden" name="review[review_reference_id]" value="<?= isset($user) && isset($user['signup_id']) ? $user['signup_id'] : ''; ?>" />
                    <input type="hidden" name="review[review_reviewer_id]" value="<?= isset($this->user_data) && isset($this->user_data['signup_id']) ? $this->user_data['signup_id'] : ''; ?>" />

                    <div class="testimonial-box">
                        <div class="box-top">
                            <div class="profile">
                                <div class="profile-img">
                                    <img src="<?php echo $this->model_signup->profileImage($user_review); ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                </div>
                                <div class="name-user">
                                    <quote><?= $user_review['review_name'] ?></quote>
                                    <span><?= $user_review['review_email'] ?></span>
                                </div>
                            </div>
                            <div class="reviews">
                                <fieldset>
                                    <span class="star-cb-group">
                                        <input type="radio" id="rating-5" name="review[review_rating]" value="5" <?= $user_review['review_rating'] == 5 ? 'checked' : '' ?> /><label for="rating-5">5</label>
                                        <input type="radio" id="rating-4" name="review[review_rating]" value="4" <?= $user_review['review_rating'] == 4 ? 'checked' : '' ?> /><label for="rating-4">4</label>
                                        <input type="radio" id="rating-3" name="review[review_rating]" value="3" <?= $user_review['review_rating'] == 3 ? 'checked' : '' ?> /><label for="rating-3">3</label>
                                        <input type="radio" id="rating-2" name="review[review_rating]" value="2" <?= $user_review['review_rating'] == 2 ? 'checked' : '' ?> /><label for="rating-2">2</label>
                                        <input type="radio" id="rating-1" name="review[review_rating]" value="1" <?= $user_review['review_rating'] == 1 ? 'checked' : '' ?> /><label for="rating-1">1</label>
                                        <input type="radio" id="rating-0" name="review[review_rating]" value="0" class="star-cb-clear" /><label for="rating-0">0</label>
                                    </span>
                                </fieldset>
                            </div>
                        </div>
                        <div class="client-comment">
                            <textarea name="review[review_description]" id="review" maxlength="100" class="form-control" placeholder="Write your review here" required><?= $user_review['review_description']; ?></textarea>
                        </div>
                    </div>
                    <fieldset>
                        <button type="submit" class="btn btn-custom" id="review_submit">Submit</button>
                    </fieldset>

                </form>
            <?php endif; ?>

            <hr />

            <div class="reviews-container">
                <p>Rating and reviews</p>
                <?php for ($i = 5; $i >= 1; $i--) : ?>
                    <div class="review">
                        <span class="icon-container"><?= $i; ?> <i class="fa fa-star"></i></span>
                        <div class="progress">
                            <div class="progress-done" data-done="<?= $this->model_review->reviewPercentage($user['signup_id'], REVIEW_TYPE_SIGNUP, $i); ?>"></div>
                        </div>
                        <span class="percent"><?= $this->model_review->reviewPercentage($user['signup_id'], REVIEW_TYPE_SIGNUP, $i); ?>%</span>
                    </div>
                <?php endfor; ?>
            </div>

        <?php endif; ?>

        <?php if (isset($review) && is_array($review) && count($review) > 0) : ?>
            <div class="testimonial-box-container mt-3">
                <?php foreach ($review as $key => $value) : ?>

                    <div class="testimonial-box">
                        <div class="box-top">
                            <div class="profile">
                                <div class="profile-img">
                                    <img src="<?php echo $this->model_signup->profileImage($value); ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'user.png' ?>';" />
                                </div>
                                <div class="name-user">
                                    <quote><?= $value['review_name'] ?></quote>
                                    <span><?= $value['review_email'] ?></span>
                                </div>
                            </div>
                            <div class="reviews">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <?php if ($i <= (int) $value['review_rating']) : ?>
                                        <i class="fa fa-star"></i>
                                    <?php else : ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="client-comment">
                            <p><?= $value['review_description']; ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?= __('No reviews available.') ?>
        <?php endif; ?>
    </div>

<?php endif; ?>

<script>
    /**
     * Method updateProgress
     *
     * @return void
     */
    function updateProgress() {
        const progressDone = document.querySelectorAll('.progress-done');

        progressDone.forEach(progress => {
            progress.style.width = progress.getAttribute('data-done') + '%';
        });
    }

    $(document).ready(function() {
        updateProgress();

        $('body').on('click', '#review_submit', function() {
            var rating = $("input[name='review[review_rating]']:checked").attr('value');
            var review = $('#review').val();

            if (rating == '0') {
                $('.error').html('Enter a valid rating.');
            } else if (review == '') {
                $('.error').html('Please enter review');
            } else if (review.length <= 2 || review.legth >= 250) {
                $('.error').html('Please enter review in less than 250 Characters');
            } else {
                $('.error').html('');

                var data = $('.rating-form').serialize();
                var url = base_url + 'dashboard/custom/save_review'

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
                            $('#review_submit').attr('disabled', true)
                            $('#review_submit').html('Submitting ...')
                        },
                        complete: function() {
                            $('#review_submit').attr('disabled', false)
                            $('#review_submit').html('Submit')
                        }
                    })
    			}).then(
    			    function(response) {
                        if (response.status) {
                            $(".rating-box").load(location.href + " .rating-box>*", function() {
                                updateProgress();
                            });
                            AdminToastr.success(response.txt)
                        } else {
                            AdminToastr.error(response.txt)
                        }
    			    }
			    )
            }
        })
    })
</script>