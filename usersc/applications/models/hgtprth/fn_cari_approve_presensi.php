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

    $qs_htsprrd = $db
        ->query('select', 'htsprrd')
        ->get([
            'count(id) as approve'
        ])
        ->where('tanggal', $_POST['tanggal'] )
        ->where('is_approve', 1 )
        ->exec();
    $rs_htsprrd = $qs_htsprrd->fetch();

    $data = array(
        'rs_htsprrd' => $rs_htsprrd
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>