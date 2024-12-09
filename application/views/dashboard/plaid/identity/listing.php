<div class="dashboard-content">
    <i class="fa-regular fa-lock"></i>
    <h4><?= __('View') . ' ' . ucfirst($entity) ?></h4>
    <hr />
    <table class="style-1">
        <thead>
            <tr>
                <th class="col-2"><?= __('Id') ?></th>
                <th class="col-3"><?= __('Info') ?></th>
                <th class="col-2"><?= __('Balances') ?></th>
                <th><?= __('Owners') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php if (property_exists($entityArray, 'accounts')) : ?>
                <?php if(count($entityArray->accounts) > 0): ?>
                    <?php foreach ($entityArray->accounts as $key => $value) : ?>
                        <tr>
                            <td>
                                <ul>
                                    <li><?= $value->account_id ?></li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li><b>Name:</b> <?= $value->name ?></li>
                                    <li><b>Official name:</b> <?= $value->official_name ?></li>
                                    <li><b>Mask:</b> <?= $value->mask ?></li>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <?php if(property_exists($value, 'balances')): ?>
                                        <li>- <b>Available:</b> <?= $value->balances->iso_currency_code . ' ' . $value->balances->available ?></li>
                                        <li>- <b>Current:</b> <?= $value->balances->iso_currency_code . ' ' . $value->balances->current ?></li>
                                        <li>- <b>Limit:</b> <?= $value->balances->limit ? ($value->balances->iso_currency_code . ' ' . $value->balances->limit) : NA ?></li>
                                    <?php endif; ?>
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <?php if(property_exists($value, 'owners')): ?>
                                            <?php foreach($value->owners as $owners_key => $owners_value): ?>
                                                <?php if(property_exists($owners_value, 'names')): ?>
                                                    <strong>Names:</strong>
                                                    <?php foreach($owners_value->names as $names_key => $names_value): ?>
                                                        <ul>
                                                            <li>- <?= $names_value ?></li>
                                                        </ul>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if(property_exists($owners_value, 'addresses')): ?>
                                                    <?php foreach($owners_value->addresses as $addresses_key => $addresses_value): ?>
                                                        <ul>
                                                            <strong>Address <?= $addresses_key+1 ?></strong>
                                                            <li>- <b>Street:</b> <?= $addresses_value->data->street ?></li>
                                                            <li>- <b>City:</b> <?= $addresses_value->data->city ?></li>
                                                            <li>- <b>Region:</b> <?= $addresses_value->data->region ?></li>
                                                            <li>- <b>Postal code:</b> <?= $addresses_value->data->postal_code ?></li>
                                                            <li>- <b>Country:</b> <?= $addresses_value->data->country ?></li>
                                                        </ul>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if(property_exists($owners_value, 'emails')): ?>
                                                    <strong>Emails:</strong>
                                                    <?php foreach($owners_value->emails as $emails_key => $emails_value): ?>
                                                        <ul>
                                                            <li>- <?= $emails_value->data ?></li>
                                                        </ul>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                                <?php if(property_exists($owners_value, 'phone_numbers')): ?>
                                                    <strong>Phone Numbers:</strong>
                                                    <?php foreach($owners_value->phone_numbers as $phone_numbers_key => $phone_numbers_value): ?>
                                                        <ul>
                                                            <li>- <?= $phone_numbers_value->data ?></li>
                                                        </ul>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>