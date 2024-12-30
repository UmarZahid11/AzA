<div class="dashboard-content">
    <div class="float-right d-flex">
        <span class="dropdown float-right">
            <button><i class="fa fa-bars"></i></button>
            <label>
                <input type="checkbox" />
                <ul>
                    <li>
                        <a href="<?= l('dashboard/coaching/listing') ?>" data-toggle="tooltip" title="View all coachings in list view."><i class="fa fa-eye"></i> See list view</a>
                    </li>
                </ul>
            </label>
        </span>
    </div>
    <i class="fa-regular fa-desktop"></i>
    <h4>Coaching</h4>
    <hr />

    <?php $this->load->view("widgets/coaching/index"); ?>
</div>