<div class="dashboard-content posted-theme">
    <!--<div class="float-right d-flex">-->
    <!--    <a href="javascript:;" class="btn btn-custom">Add Board</a>-->
    <!--</div>-->
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <a href="<?= l('dashboard/monday/boards') ?>">
        <h4><?= $boardDetail['data']['boards'][0]['name']; ?> <i class="fa fa-arrow-right"></i></h4>
    </a>
    <a href="<?= l('dashboard/monday/groups/' . $boardDetail['data']['boards'][0]['id']); ?>">
        <h4><?= $groupDetail['title']; ?></h4>
    </a>
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
                        <?php if(isset($boardItems) && isset($boardItems['data']['boards']) && !empty($boardItems['data']['boards'])) : ?>
                            <?php foreach($boardItems['data']['boards'] as $items) : ?>
                                <?php foreach($items['items_page']['items'] as $item) : ?>
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
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>