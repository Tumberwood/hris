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
        ->where('is_approve', 0 )
        ->exec();
    $rs_htsprrd = $qs_htsprrd->fetch();

    $qs_htsprrd_total = $db
        ->query('select', 'htsprrd')
        ->get([
            'count(id) as total',
            'is_payroll'
        ])
        ->where('tanggal', $_POST['start_date'] )
        ->exec();
    $rs_htsprrd_total = $qs_htsprrd_total->fetch();

	$qs_cek = $db
        ->raw()
        ->bind(':tanggal', $_POST['start_date'])
        ->exec('SELECT
                    COUNT(a.id) AS cek
                FROM htsprrd AS a
                WHERE a.tanggal = :tanggal
                    AND cek = 1
                '
                );
    $rs_cek = $qs_cek->fetch();
    
    $data = array(
        'rs_htsprrd' => $rs_htsprrd,
        'rs_cek' => $rs_cek,
        'rs_htsprrd_total' => $rs_htsprrd_total
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>