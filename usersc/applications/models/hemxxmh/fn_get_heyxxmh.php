<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $qs_heyxxmd = $db
        ->query('select', 'heyxxmd')
        ->get([
            'id_heyxxmh'
        ])
        ->where('id', $_POST['id_heyxxmd'] )
        ->exec();
    $rs_heyxxmd = $qs_heyxxmd->fetch();
    $data = array(
        'rs_heyxxmd' => $rs_heyxxmd
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>