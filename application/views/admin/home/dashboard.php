<? global $config; ?>

<!-- Welcome title start -->

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <div class="text-center">

        <h1 style="margin-bottom: 85px;font-size: 60px;">Welcome to <br><img
                src="<?= Links::img($logo[0]['logo_image_path'], $logo[0]['logo_image']) ?>" alt="logo" width="25%"
                style="margin-top: 20px"></h1>

    </div>

</div>

<!-- Welcome title end -->

<!-- Blocks start -->

<div class="col_3 row">

    <div class="col-md-3 widget widget1">

        <div class="r3_counter_box">

            <a href="<?= l('admin/signup') ?>"><i class="fa fa-mail-forward"></i></a>

            <div class="stats">

                <h5 data-counter="counterup" data-value="<?php echo $signup_approval; ?>" class="counterup">
                    <?php echo $signup_approval; ?></h5>

                <div class="grow2">

                    <p>Signup approvals</p>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 widget widget1">

        <div class="r3_counter_box">

            <a href="<?= l('admin/job_testimonial_request') ?>"><i class="fa fa-mail-forward"></i></a>

            <div class="stats">

                <h5 data-counter="counterup" data-value="<?php echo $testimonial_request; ?>" class="counterup">
                    <?php echo $testimonial_request; ?></h5>

                <div class="grow2">

                    <p>Job testimonial requests</p>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 widget widget1">

        <div class="r3_counter_box">

            <a href="<?= l('admin/blog') ?>"><i class="fa fa-mail-forward"></i></a>

            <div class="stats">

                <h5 data-counter="counterup" data-value="<?php echo $blog_approval_request; ?>" class="counterup">
                    <?php echo $blog_approval_request; ?></h5>

                <div class="grow2">

                    <p>Blog approval requests</p>

                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3 widget widget1">

        <div class="r3_counter_box">

            <a href="<?= l('admin/inquiry') ?>"><i class="fa fa-mail-forward"></i></a>

            <div class="stats">

                <h5 data-counter="counterup" data-value="<?php echo $inquiry_request; ?>" class="counterup">
                    <?php echo $inquiry_request; ?></h5>

                <div class="grow2">

                    <p>Inquiries requests</p>

                </div>

            </div>

        </div>

    </div>

    <div class="clearfix"> </div>
    
    <h4 style="text-align: center; margin-top: 40px;">Regional inquiry traffic</h4>

    <div id="regions_div" style="width: 600px; height: 500px; margin-left: 25%;"></div>

</div>

<!-- Blocks end -->

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    google.charts.load('current', {
        'packages': ['geochart'],
    });
    
    google.charts.setOnLoadCallback(drawRegionsMap);
    
    function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
            ['Country', 'Inquiries']<?= $inquiriesDataset ?>
        ]);
    
        var options = {
              colorAxis: {colors: ['#8204aa', '#290038']},
              defaultColor: '#D9D9D9',
        };
    
        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));
    
        chart.draw(data, options);
    }
    
</script>