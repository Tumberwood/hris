<?php 
    require_once '../../../../users/init.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>INSPINIA | 500 Error</title>

    <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/animate.css" rel="stylesheet">
    <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/style.css" rel="stylesheet">
    <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/custom.css" rel="stylesheet">
    <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/css/font-awesome.min.css" rel="stylesheet">

</head>

<body class="gray-bg">


    <div class="middle-box text-center animated fadeInDown">
        <h1>Oops</h1>
        <h3 class="font-bold">No Data Akses</h3>

        <div class="error-desc">
            User Anda sudah aktif, tetapi belum memiliki akses data.<br>Silakan hubungi administrator untuk meminta hak akses data.
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/popper.min.js"></script>
    <script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/bootstrap.min.js"></script>

</body>

</html>
