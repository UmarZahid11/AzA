<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <a href="javascript:;" class="btn btn-custom">Add Board</a>
    </div>
    <img src="https://www.vectorlogo.zone/logos/monday/monday-icon.svg" style="width:20px;" />
    <h4>Monday</h4>
    <hr />

    <div class="row">
        <?php if(isset($boards) && $boards) : ?>
            <?php foreach($boards as $board) : ?>
                <?php if(isset($board['boards']) && !empty($board['boards'])) : ?>
                    <?php foreach($board['boards'] as $boardData) : ?>
                        <div class="col-md-3">
                            <div class="card">
                                <img 
                                    src="https://cdn.monday.com/images/quick_search_recent_board2.svg"
                                    class="card-img-top" 
                                    src="..." 
                                    alt="Card image cap"
                                    style="width:100%"
                                />
                                <div class="card-body">
                                    <h5 
                                        class="card-title"
                                        title="<?= $boardData['name'] ?>"
                                        data-toggle="tooltip"
                                    >
                                        <?= strip_string($boardData['name'], 18); ?>
                                    </h5>
                                    <a href="<?= l('dashboard/monday/board/' . $boardData['id']) ?>" class="btn btn-primary">View detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>