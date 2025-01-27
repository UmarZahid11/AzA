<div class="dashboard-content posted-theme">
    <!--<div class="float-right d-flex">-->
    <!--    <a href="javascript:;" class="btn btn-custom">Add Board</a>-->
    <!--</div>-->
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4><?= $boardDetail['data']['boards'][0]['name'] ?></h4>
    <hr />

    <div class="row">
        <?php if(isset($boardGroups) && isset($boardGroups['data']['boards']) && !empty($boardGroups['data']['boards'])) : ?>
            <?php foreach($boardGroups['data']['boards'] as $groups) : ?>
                <?php foreach($groups['groups'] as $group) : ?>
                    <div class="col-md-12">
                        <div class="card-body">
                            <h5 
                                class="card-title"
                            >
                                <?= ($group['title']); ?>
                            </h5>
                            <a href="<?= l('dashboard/monday/group/' . $boardDetail['data']['boards'][0]['id'] . '/' . $group['id']) ?>" class="btn btn-primary">View detail</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>