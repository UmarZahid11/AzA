<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">
                <div class="banner-cont inner-banner-text wow fadeInLeft">
                    <h1>
                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?>
                    </h1>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

    </div>

</section>

<style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
            font-family: 'palatinolinotype';
            line-height: 1.4;
            font-size: 14px;
        }
        th {
            background-color: #8204aa;
            color: white;
            font-size: 14px;
            padding: 12px;
        }
        .functionality {
                color: #8204aa;
                font-family: 'palatinolinotype';
                font-size: 16px;
                font-weight: 700;
        }
        tr:nth-last-child(2) td {
            font-size: 17px;
            font-weight: 700;
            color: #8204aa;
            font-family: 'palatinolinotype';
        }
        tr:last-child td {
            border:none;
        }
        a.table_btn {
            display: inline-block;
            background: red;
            margin-top: 10px;
            padding: 0;
            width: 80%;
            color: #fff;
            height: 40px;
            background: #8204aa;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .check {
            color: green;
            font-weight: bold;
        }
        .cross {
            color: red;
            font-weight: bold;
        }
        .note {
            margin-top: 20px;
            font-size: 10px;
        }
        .note p {
            margin: 5px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 10px;
        }
        .pricing_new{
            padding:80px 0;
        }
    </style>


<section class="pricing_new">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="pricing_new_table">
                    <div class="table-responsive">
                        <table>
                            <tbody><tr>
                                <th>Category</th>
                                <th class="functionality">Functionality</th>
                                <th>Entrepreneur</th>
                                <th>Customer</th>
                                <th>Founder</th>
                                <th>Incubator</th>
                            </tr>
                            <tr>
                                <td>Free Gift #1</td>
                                <td class="functionality"></td>
                                <td>14 Day Free Trial (Cancel any time before trial ends)</td>
                                <td>20% Discount Valid for 1st Purchase</td>
                                <td>10x 30 min 1:1 Entrepreneur Coaching Worth $4000.00</td>
                                <td>Weekly 30 min Entrepreneur Coaching (For Duration of Incubator)</td>
                            </tr>
                            <tr>
                                <td>Promotion #1</td>
                                <td class="functionality"></td>
                                <td>75% Discount On Standard Price</td>
                                <td></td>
                                <td>$83.33/month for 5 years; (50% Discount On Standard Price; 5 years payment paid up front on sign-up)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Promotion #2</td>
                                <td class="functionality"></td>
                                <td>4x 30 min 1:1 Entrepreneur Coaching Worth $2000.00</td>
                                <td></td>
                                <td>$99.99/month after 5 years (75% Discount On Standard Price)</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Promotion #3</td>
                                <td class="functionality"></td>
                                <td class="cross">X</td>
                                <td class="cross">X</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Business Applications</td>
                                <td class="functionality">Quickbooks</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">MONDAY</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">BOX</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Placid</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td>Marketing</td>
                                <td class="functionality">Webinar Hosting</td>
                                <td class="check">✓</td>
                                <td class="cross">X (Only Able to Join Webinar)</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Video Upload</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">List to Sell Services</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td>E-Commerce</td>
                                <td class="functionality">Purchase Services</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Entrepreneur Calendar</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td>Social Media</td>
                                <td class="functionality">AzAverze Community</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Networking (Connect/Follow/Like/Endorse/ Reviews/Testimonials/Blog/News/Announcements/Comments)</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Platform Communications (Message/Chat/Mail/Comments)</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality">Alerts/Notifications (On Platform/To Sign-Up E-Mails)</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td>Innovator</td>
                                <td class="functionality">Business Growth &amp; Entrepreneur Development &amp; Coaching</td>
                                <td class="cross">X</td>
                                <td class="cross">X</td>
                                <td class="cross">X</td>
                                <td class="check">✓</td>
                            </tr>
                            <tr>
                                <td>Price</td>
                                <td class="functionality"></td>
                                <td>$99.99/Month</td>
                                <td>Free</td>
                                <td>$5,000.00</td>
                                <td><a href="https://azaverze.com/stagging/contact">Contact AzAverze &nbsp;&nbsp;<i class="fa-solid fa-arrow-right-long"></i></a></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="functionality"></td>
                                <td><a class="table_btn" href="https://azaverze.com/stagging/signup">Sign-Up</a></td>
                                <td></td>
                                <td><a class="table_btn" href="">BUY</a></td>
                                <td></td>
                            </tr>
                        </tbody></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>