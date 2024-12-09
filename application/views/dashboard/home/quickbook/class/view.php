<div class="dashboard-content">
    <a href="<?= l('dashboard/home/quickbook-save/'.$entity.'/' . $class->Id) ?>" target="_blank" class="btn btn-custom float-right" ><i class="fa fa-edit text-white"></i>&nbsp;<?= __('Edit') . ' ' . ucfirst($entity) ?></a>
    <i class="fa-regular fa-book"></i>
    <h4><?= ucfirst($entity) . ' ' . __('Details') ?></h4>
    <hr />
    <div>
        <div class="row">
            <div class="col-md-6">
                <ul>
                    <li><b>Id:</b> <?= $class->Id ?></li>
                    <li><b>Name:</b> <?= $class->FullyQualifiedName ?></li>
                </ul>
            </div>
            <div class="col-md-6">
                <ul>
                    <li><b>Create Time:</b> <?= $class->MetaData->CreateTime ?></li>
                    <li><b>Last Updated Time:</b> <?= $class->MetaData->LastUpdatedTime ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>