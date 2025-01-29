<div class="dashboard-content posted-theme">
    <!--<div class="float-right d-flex">-->
    <!--    <a href="javascript:;" class="btn btn-custom">Add Board</a>-->
    <!--</div>-->
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4>
        <a href="<?= l('dashboard/monday/boards') ?>">
            <?= $boardDetail['data']['boards'][0]['name']; ?> <i class="fa fa-arrow-right"></i>
        </a>
        <a href="<?= l('dashboard/monday/groups/' . $boardDetail['data']['boards'][0]['id']); ?>">
            <?= $groupDetail['title']; ?>
        </a>
    </h4>
    <hr />

    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive"> 
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <?php if(isset($boardColumns) && isset($boardColumns['data']['boards'][0]['columns'])) : ?>
                            <tr>
                                <?php foreach($boardColumns['data']['boards'][0]['columns'] as $column) : ?>
                                    <?php if($column['title'] != 'Subitems') : ?>
                                        <th data-id="<?= $column['id'] ?>"><?= $column['title'] ?></th>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php if(isset($items) && !empty($items)) : ?>
                            <?php foreach($items as $item) : ?>
                                <?php if($item['group']['id'] == $group_id) : ?>
                                    <tr>
                                        <td><?= $item['name'] ?></td>
                                        <?php foreach($item['column_values'] as $item_column_values) : ?>
                                            <?php if($item_column_values['id'] != 'subitems_mkmgcb1b') : ?>
                                                <td><?= ($item_column_values['text']); ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if(isset($items) && $items) : ?>
                    <div class="d-flex justify-content-center">
                        <?php if($cursor) : ?>
                            <a href="<?= l('dashboard/monday/items/' . $board_id . '/' . $group_id . '/' . $limit . '/' . $cursor) ?>">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>