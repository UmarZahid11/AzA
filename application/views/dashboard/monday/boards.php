<div class="dashboard-content posted-theme">
    <div class="float-right d-flex">
        <button data-fancybox data-animation-duration="700" data-src="#addBoardModal" href="javascript:;" class="btn btn-outline-custom" data-toggle="tooltip" title="" data-bs-placement="top">Add Board</button>
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
                                    <a href="<?= l('dashboard/monday/groups/' . $boardData['id']) ?>" class="btn btn-primary">View detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<div class="grid">
    <div style="display: none;" id="addBoardModal" class="animated-modal">
        <form class="boardForm" id="boardForm" action="javascript:;" novalidate>
            <div class="form-group">
                <label>Description <span class="text-danger">*</span></label>
                <textarea class="form-control" name="product_request[product_request_description]" maxlength="1000" required></textarea>
            </div>
            <div class="form-group">
                <label>Attachment</label>
                <input type="file" class="form-control font-12" name="product_request_attachment" />
            </div>
            <div class="form-group mt-2">
                <button type="submit" class="btn btn-custom" id="requestFormBtn">Send</button>
            </div>
        </form>
    </div>
</div>