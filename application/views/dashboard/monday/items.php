<div class="dashboard-content posted-theme">
    <!--<div class="float-right d-flex">-->
    <!--    <a href="javascript:;" class="btn btn-custom">Add Board</a>-->
    <!--</div>-->
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4><?= $boardDetail['data']['boards'][0]['name']; ?> <i class="fa fa-arrow-right"></i></h4> <br/>
    <h4><?= $groupDetail['title']; ?></h4>
    <hr />

    <div class="row">
        <div class="col-md-12">
            <div class="container table-responsive py-5"> 
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <?php if(isset($boardColumns) && isset($boardColumns['data']['boards'][0]['columns'])) : ?>
                            <tr>
                                <?php foreach($boardColumns['data']['boards'][0]['columns'] as $column) : ?>
                                    <th data-id="<?= $column['id'] ?>"><?= $column['title'] ?></th>
                                <?php endforeach; ?>
                            </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody>
                        <?php if(isset($boardItems) && isset($boardItems['data']['boards']) && !empty($boardItems['data']['boards'])) : ?>
                            <?php foreach($boardItems['data']['boards'] as $items) : ?>
                                <?php foreach($items['items_page']['items'] as $item) : ?>
                                    <tr>
                                        <?php if($item['group']['id'] == $group_id) : ?>
                                            <td><?= ($item['name']); ?></td>
                                            <td></td>
                                            <td><?= ($item['state']); ?></td>
                                            <td><?= ($item['url']); ?></td>
                                            <td></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>