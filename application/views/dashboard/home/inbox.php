<div class="dashboard-content">
    <div style="float:right;">
        <a href="<?= l('dashboard/home/compose') ?>" class="mail-box-btn"><i class="fa-regular fa-pen-to-square"></i> <?= __('Compose') ?></a>
        <a href="<?= l('dashboard/home/sent') ?>" class="mail-box-btn"><i class="fa-regular fa-paper-plane"></i> <?= __('Sent') ?></a>
    </div>
    <i class="fa-regular fa-arrow-down-to-bracket"></i>
    <h4><?= __('Inbox') ?></h4>
    <hr />
    <?php $this->load->view('widgets/chat/email'); ?>
</div>