<?php
    require_once($abs_us_root.$us_url_root.'users/includes/template/header1_must_include.php'); 
    require_once($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/assets/fonts/glyphicons.php');
    require_once($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/template_css_load.php');
    require_once($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'/template_css_datatables_load.php');
?>

<?php
    if(file_exists($abs_us_root.$us_url_root.'usersc/templates/'.$settings->template.'.css')){?> <link href="<?=$us_url_root?>usersc/templates/<?=$settings->template?>.css" rel="stylesheet"> <?php } 
?>

</head>
<body class="canvas-menu">
<?php require_once($abs_us_root.$us_url_root.'users/includes/template/header3_must_include.php'); ?>