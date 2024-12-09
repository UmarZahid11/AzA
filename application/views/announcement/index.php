<style>
.announcement_short_desc
{
    border-bottom: 1px solid #f3f3f3;
    padding: 0;
    text-align: left;
    vertical-align: top;
    width: 100%;
    margin-top: 10px;
}
.announcement_comments {
    padding: 0;
    text-align: left;
    vertical-align: top;
    width: 100%;
    margin-top: 20px;
}
.announcement_author
{
    padding: 0 16px 0 8px;
    text-align: left;
}
.announcement_image
{
    padding: 0 0 0 16px;
    width: 70px;
}
.announcement_image_src
{
    border-radius: 50%;
    clear: both;
    display: block;
    float: none;
    height: 50px;
    width: 50px;
    margin: 0;
    max-width: 100%;
    outline: 0;
    text-align: center;
    text-decoration: none;
}
.announcement_anchor {
    line-height: 1.3;
    padding: 0 16px 0 8px;
    text-align: right;
    white-space: nowrap;
    vertical-align: top
}
.announcement_comment_image {
    clear: none;
    display: inline-block;
    height: 20px;
    margin: 0;
    max-width: 100%;
    opacity: .4;
    outline: 0;
    text-decoration: none;
    width: auto;
}
.announcement_comment_count {
    color: #8f8f8f;
    float: left;
    line-height: 1.3;
    margin: 0 5px 10px 5px;
    padding: 0;
    text-align: left;
    font-weight: 400;
}
.announcement_author_area
{
    padding: 0;
    position: relative;
    text-align: left;
    vertical-align: top;
    width: 100%;
}
.announcement_title {
    font-size: 18px;
    font-weight: 400;
    line-height: 1.3;
    margin: 0;
    padding: 0;
    word-wrap: normal;
}
.announcement_author_name
{
    color: inherit;
    line-height: 1.3;
    margin: 0;
    padding: 0;
    font-weight: normal;
    font-size: 16px;
}
.announcement_author_source {
    color: inherit;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.3;
    margin: 0 0 8px 0;
    padding: 0;
    word-wrap: normal;
}
.announcement_created_sec
{
    margin: 0;
    padding: 0 16px 0 0;
    text-align: right;
    vertical-align: top;
}
.announcement_created {
    color: #8f8f8f;
    line-height: 1.3;
    margin: 20px 0 0 0;
    font-weight: 400;
}
</style>
<!-- banner start -->

<!--<section class="banner inner-banner">-->

<!--    <div class="container">-->

<!--        <div class="row justify-content-center">-->

<!--            <div class="col-lg-6">-->

<!--                <div class="banner-cont inner-banner-text wow fadeInLeft">-->

<!--                    <h1>-->

<!--                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Announcement' ?>-->

<!--                    </h1>-->

<!--                </div>-->

<!--            </div>-->
<!--            <div class="col-lg-6">-->
<!--                <div class="inner-banner">-->

<!--                    <img class="lazy" data-src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />-->

<!--                </div>-->
<!--            </div>-->

<!--        </div>-->

<!--    </div>-->

    <!-- </div> -->

<!--</section>-->

<section class="prcasd-banner">
    <div class="container">
        <div class="logoas">
            <a href="<?= l('') ?>">
                <img src="<?= g('images_root') ?>logo-hopri.png" width="150" alt="" />
            </a>
        </div>
        <div class="prcahbane-wrap">
            <div class="text-center">
                <h2><?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Announcements' ?></h2>
            </div>
            
        </div>
    </div>
</section>

<section class="blog-sec">

    <div class="container">

        <?php if (isset($announcements) && count($announcements) > 0) : ?>

            <table style="background:#f3f3f3;width: 100%;">
                <tr>
                    <td style="vertical-align:top;padding:0;">
                        <table style="border-spacing:0;background:#fefefe;margin:0;padding:0;text-align:center;vertical-align:top;width:100%">
                            <tbody>
                                <?php foreach($announcements as $key => $announcement): ?>
                                    <tr>
                                        <td class="p-0">
                                            <!--   Beginning of Popular Topic   -->
                                            <table style="width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td class="announcement_created_sec">
                                                            <p class="announcement_created">
                                                                <?= date('F d, Y', strtotime($announcement['announcement_cretaedon'])) ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table style="vertical-align:top;width:100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:0 8px 8px 16px; text-align:left; width:100%;">
                                                            <h2 class="announcement_title">
                                                                <a href="<?= l('announcement/detail/' . $announcement['announcement_slug']) ?>" style="font-weight:400;">
                                                                    <span><?= $announcement['announcement_title'] ?></span>
                                                                </a>
                                                            </h2>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="announcement_author_area">
                                                <tbody>
                                                    <tr>
                                                        <td class="announcement_image">
                                                            <img src="https://via.placeholder.com/50x50/?text=Admin" class="announcement_image_src">
                                                        </td>
                                                        <td class="announcement_author">
                                                            <h6 class="announcement_author_name">
                                                                <?= RAW_ROLE_0 ?>
                                                            </h6>
                                                            <p class="announcement_author_source">
                                                                <?= $config['site_name'] ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <table class="announcement_short_desc">
                                                <tbody>
                                                    <tr>
                                                        <td style="padding:0 16px 0 16px;">
                                                            <p>
                                                                <?= $announcement['announcement_short_desc'] ?>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table class="announcement_comments">
                                                <tbody>

                                                        <td style="padding:0 8px 16px 8px;text-align:left;white-space:nowrap;vertical-align:top;width:75px">
                                                            <img src="https://forum.sublimetext.com/images/emails/comment.png" style="clear:none;display:inline-block;float:left;height:20px;margin:0;max-width:100%;opacity:.4;outline:0;text-decoration:none;width:auto">
                                                            <p style="color:#8f8f8f;float:left;line-height:1.3;margin:0 5px 10px 5px;padding:0;text-align:left;font-weight:400;">
                                                                <?= $this->model_comment->get_comment_count(REFERENCE_TYPE_ANNOUNCEMENT, $announcement['announcement_id']) ?>
                                                            </p>
                                                        </td>
                                                        <td class="announcement_anchor">
                                                            <a href="<?= l('announcement/detail/' . $announcement['announcement_slug']) ?>" class="btn btn-custom">
                                                                Read More
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div style="background-color:#f3f3f3;">
                                                <table style="padding:0;width:100%">
                                                    <tbody>
                                                        <tr>
                                                            <td height="20px" style="border-collapse:collapse!important;line-height:20px;margin:0;padding:0;"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--   End of Popular Topic   -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            No announcements yet.
        <?php endif; ?>

        <?php if (isset($announcements) && count($announcements) > 0) : ?>
            <hr />

            <div class="row">
                <div class="col-lg-12">

                    <nav aria-label="Page navigation example mt-5">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php if ($page <= 1) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page <= 1) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('announcement/listing/') . $prev;
                                                                        } ?>"><i class="far fa-chevron-left"></i></a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php if ($page == $i) {
                                                            echo 'active';
                                                        } ?>">
                                    <a class="page-link" href="<?= l('announcement/listing/') . $i; ?>"> <?= $i; ?> </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php if ($page >= $totalPages) {
                                                        echo 'disabled';
                                                    } ?>">
                                <a class="page-link icon-back" href="<?php if ($page >= $totalPages) {
                                                                            echo '#';
                                                                        } else {
                                                                            echo l('announcement/listing/') . $next;
                                                                        } ?>"><i class="far fa-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        <?php endif; ?>
    </div>
</section>