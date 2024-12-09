<div class="dashboard-content">
    <div style="float:right;">
        <a href="<?= l('dashboard/home/compose') ?>" class="mail-box-btn"><i class="fa-regular fa-pen-to-square"></i> <?= __('Compose') ?></a>
        <a href="<?= l('dashboard/home/inbox') ?>" class="mail-box-btn"><i class="fa-regular fa-envelope"></i> <?= __('inbox') ?></a>
    </div>
    <i class="fa-regular fa-paper-plane"></i>
    <h4><?= __('Sent') ?></h4>
    <hr />
    <?php $this->load->view('widgets/chat/email'); ?>

</div>