<?php
$logo = $this->model_logo->find_one(
    array('where' => array('logo_status' => 1))
);
?>

<html>

<body>
    <div align="center">
        <div style="max-width: 680px; min-width: 500px; border: 2px solid #e3e3e3; border-radius:5px; margin-top: 20px">
            <div style="background: #f3f3f3">
                <img src="<?= get_image($logo['logo_image_path'], $logo['logo_image']) ?>" width="250" alt="<?= g('site_name') ?>" border="0" style='width: 25%; padding: 15px; margin: 0px auto;' />
            </div>
            <div style="background-color: #fbfcfd; border-top: thick double #cccccc; text-align: left;">
                <div style="margin: 30px;">
                    <p>
                        <?php
                        if (isset($msg)) {
                            echo $msg;
                        } else {
                        ?>
                            Dear {CANDIDATE_NAME},<br> <br>
                            Welcome to <?= g('site_name') ?>!<br> <br>
                            {YOUR MESSAGE HERE}<br><br>
                        <?php
                        }
                        ?>
                    </p>


                    <? if (isset($form_input) && is_array($form_input)) { ?>
                        <table style="text-align: left;">
                            <? if ($title) { ?>
                                <tr>
                                    <td colspan="2" <?= isset($body_att1) ? $body_att1 : '' ?>>
                                        <h3 style="color: rgb(25, 98, 203); font-size: 25px; padding: 0px; margin: 0px;">
                                            <?= $title ?>:
                                        </h3>
                                    </td>
                                </tr>
                            <? } else {
                            ?>
                                <tr>
                                    <td colspan="2" <?= isset($body_att1) ? $body_att1 : '' ?>>
                                        <h3 style="color: rgb(25, 98, 203); font-size: 25px; padding: 0px; margin: 0px;">
                                            User Input:
                                        </h3>
                                    </td>
                                </tr>
                            <? } ?>
                            <?php

                            foreach ($form_input as $key => $value) { ?>
                                <tr>
                                    <th><?= ucfirst(str_replace("_", " ", $key)) ?></th>
                                    <td> :
                                        <?
                                        if (is_array($value)) {
                                            foreach ($value as $sub_value) {
                                                echo $sub_value . "<br/>";
                                            }
                                        } else
                                            echo $value;
                                        ?>
                                    </td>
                                </tr>
                            <? } ?>
                        </table>
                        <br> <br>
                    <? } ?>

                    Please contact <?= g('site_name') ?> if you have any questions.<br><br>

                    <div style="text-align: Right;">
                        With warm regards,<br>
                        <?= g('site_name') ?> Team
                    </div>
                </div>
            </div>
        </div>
    </div>
    <center><?= date('Y') ?> © <?= g('site_name') ?>. All Rights Reserved.</center>
</body>

</html>