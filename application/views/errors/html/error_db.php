<html>
<head>
    <link href='//fonts.googleapis.com/css?family=Lato:100' rel='stylesheet' type='text/css'>

    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            /*font-family: 'Lato';*/
            font-family: 'Calibri';
        }

        .container {
            text-align: center;
            /*display: table-cell;
            vertical-align: middle;*/
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 72px;
            margin-bottom: 0;
            /*color: red;*/
        }
        h6{
            margin: 0;
        }
        p{
            font-size: 20px;
            /*color:#8b0000;*/

        }
    </style>
    <meta charset="utf-8">
    <title>Database Error</title>
</head>
<body>
<div class="container">
    <div class="content title">
        <h6><?php echo $heading; ?></h6>
        <?php echo $message; ?>
    </div>
</div>
</body>
</html>