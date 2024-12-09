<div class="dashboard-content posted-theme">
    <i class="fa fa-box"></i>
    <h4><?= __('Escrow transactions'); ?></h4>
    <hr />

    <table class="style-1">
        <thead>
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Parties') ?></th>
                <th><?= __('Actions') ?></th>
            </tr>
        </thead>
        <?php if (isset($transactions) && count($transactions) > 0) : ?>
            <tbody>
                <?php foreach ($transactions as $key => $value) : ?>
                    <tr>
                        <td><?= $value->id ?></td>
                        <td>
                            <?php
                                foreach($value->parties as $parties_key => $parties_value) {
                                    echo $parties_value->role . ' ' . $parties_value->customer . '<br/>';
                                    echo 'Agreed: ' . $parties_value->agreed . '<br/>';
                                }
                            ?>
                        </td>
                        <td>
                            <a href="<?= l('dashboard/escrow/detail/' . $value->id) ?>" target="_blank">
                                <i class="fa fa-bars"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        <?php else : ?>
            <table>
                <small><?= __('No transactions available.') ?></small>
            </table>
        <?php endif; ?>
    </table>
</div>
