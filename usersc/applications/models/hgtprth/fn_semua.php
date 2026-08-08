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

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $qs_presensi = $db
        ->raw()
        ->bind(':start_date', $start_date)
        ->bind(':end_date', $end_date)
        ->exec(' SELECT
                    a.id AS id_hgtprth,
                    a.tanggal
                FROM hgtprth a
                WHERE a.tanggal BETWEEN :start_date AND :end_date
                AND a.is_active = 1
                '
                );
    $rs_presensi = $qs_presensi->fetchAll();

    $data = array(
        'rs_presensi' => $rs_presensi
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>