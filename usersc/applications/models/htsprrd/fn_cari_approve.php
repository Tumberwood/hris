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
        ->where('tanggal', $_POST['start_date'] )
        ->where('is_approve', 1 )
        ->exec();
    $rs_htsprrd = $qs_htsprrd->fetch();

    $qs_htsprrd_total = $db
        ->query('select', 'htsprrd')
        ->get([
            'count(id) as total'
        ])
        ->where('tanggal', $_POST['start_date'] )
        ->exec();
    $rs_htsprrd_total = $qs_htsprrd_total->fetch();

    //belum flag is_payroll

    $data = array(
        'rs_htsprrd' => $rs_htsprrd,
        'rs_htsprrd_total' => $rs_htsprrd_total
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>