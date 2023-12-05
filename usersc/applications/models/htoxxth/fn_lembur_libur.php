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

    $tanggal_select = new Carbon($_POST['tanggal']); //gunakan carbon untuk ambil data tanggal
    $tanggal = $tanggal_select->format('Y-m-d'); //format jadi 2023-09-12

    $qs_jadwal = $db
        ->query('select', 'htssctd')
        ->get([
            'id_htsxxmh'
        ])
        ->where('id_hemxxmh', $_POST['id_hemxxmh'] )
        ->where('tanggal', $tanggal )
        ->where('is_active', 1 )
        ->exec();
    $rs_jadwal = $qs_jadwal->fetch();

    $qs_holiday = $db
        ->query('select', 'hthhdth')
        ->get([
            'id'
        ])
        ->where('tanggal', $tanggal )
        ->where('is_active', 1 )
        ->exec();
    $rs_holiday = $qs_holiday->fetch();

    $jadwal = $rs_jadwal['id_htsxxmh'];

    if (!empty($rs_holiday)) {
        $is_holiday = 1;
    } else {
        $is_holiday = 0;
    }
    
    $data = array(
        'jadwal' => $jadwal,
        'is_holiday' => $is_holiday
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>