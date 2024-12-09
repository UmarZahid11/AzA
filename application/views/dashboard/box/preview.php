<div class="dashboard-content posted-theme">

    <div class="float-right" style="display: -webkit-inline-box;">
        <a data-fancybox data-animation-duration="700" data-src="#createFolderModal" href="javascript:;" class="btn btn-outline-custom">Create folder</a>&nbsp;
        <form id="boxFileUpload" action="javascript:;" enctype="multipart/form-data">
            <input type="hidden" name="folder_id" value="<?= isset($folder_id) && $folder_id ? $folder_id : '0' ?>" />
            <label class="btn btn-outline-custom">
                <?= __('Upload file') ?>
                <input type="file" name="file" class="d-none" />
            </label>
        </form>
    </div>

    <i class="fa fa-box"></i>
    <h4>Box</h4>

    <hr />

    <?php if (isset($folder_information) && property_exists($folder_information, 'path_collection')) : ?>
        <?php if (property_exists($folder_information->path_collection, 'entries')) : ?>
            <?php foreach ($folder_information->path_collection->entries as $key => $value) : ?>
                <small><a href="<?= l('dashboard/box/index/0/' . $value->id) ?>"><?= $value->name . '&nbsp;' . (array_key_last($folder_information->path_collection->entries) == $key ? '<i class="fa fa-caret-right"></i>' : '<i class="fa fa-caret-right"></i>') ?></a></small>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($folder_information) && property_exists($folder_information, 'name')) : ?>
        <small><a href="<?= l('dashboard/box/index/0/' . $folder_information->id) ?>"><?= $folder_information->name . '&nbsp;' . '<i class="fa fa-caret-right"></i>'; ?></a></small>
        <?php if($file_information && property_exists($file_information, 'name')): ?>
            <small><?= $file_information->name; ?></small>
        <?php endif; ?>
        <hr />
    <?php endif; ?>

    <?php if ($file_information && property_exists($file_information, 'expiring_embed_link')) : ?>
        <iframe class="w-100 h-100" src="<?= $file_information->expiring_embed_link->url ?>"></iframe>
    <?php endif; ?>

</div>