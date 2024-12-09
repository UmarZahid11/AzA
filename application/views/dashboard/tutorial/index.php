<div class="dashboard-content posted-theme">
    <i class="fa fa-film"></i>
    <h4>AzAverze Tutorials</h4>
    <hr />

    <div class="accordion" id="accordionTutorials">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseOne">
                    Quickbooks Tutorials
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                data-bs-parent="#accordionTutorials">
                <div class="accordion-body">
                    <table class="table">
                        <tbody>
    						<tr>
								<td>
                                    <a href="https://quickbooks.intuit.com/global/learn-and-support/video-tutorials/" target="_blank">Video Tutorials</a>
                                </td>
                            </tr>
    						<tr>
								<td>
                                    <a href="https://quickbooks.intuit.com/accountants/training-certification/" target="_blank">Learn QuickBooks Online</a>
                                </td>
                            </tr>
    						<tr>
								<td>
                                    <a href="https://quickbooks.intuit.com/" target="_blank">QuickBooks (intuit.com)</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Monday Tutorials
                </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                data-bs-parent="#accordionTutorials">
                <div class="accordion-body">
                    <a href="https://monday.com/helpcenter/academy" target="_blank">Academy - Help Center (monday.com)</a>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    Box Tutorials
                </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                data-bs-parent="#accordionTutorials">
                <div class="accordion-body">
                    <a href="https://learningbox.online/en/support/" target="_blank">Learning – Box Support</a>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    General Tutorials
                </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                data-bs-parent="#accordionTutorials">
                <div class="accordion-body">
                    <table class="table">
                        <?php if (isset($files) && count($files) > 0) : ?>
                            <tbody>
                                <?php foreach ($files as $key => $value) : ?>
                                    <?php if($value != "." && $value != ".."): ?>
                						<?php if(strpos($value, 'admin') !== false && $this->model_signup->hasRole(ROLE_0) || (strpos($value, 'admin') === false && !$this->model_signup->hasRole(ROLE_0))): ?>
                							<tr>
                								<td>
                									<a href="<?php echo ($value != "." && $value != "..") ? (base_url() . TUTORIAL_PATH . $value) : 'javascript:;' ?>" target="_blank"><?php echo humanize(str_replace('.mp4', '', $value)); ?></a>
                								</td>
                							</tr>
                						<?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>