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

    $qs_holiday = $db
        ->query('select', 'hthhdth')
        ->get([
            'id'
        ])
        ->where('tanggal', $tanggal )
        ->where('is_active', 1 )
        ->exec();
    $rs_holiday = $qs_holiday->fetch();

    $qs_cuti_bersama = $db
        ->query('select', 'htlgnth')
        ->get([
            'id'
        ])
        ->where('tanggal', $tanggal )
        ->where('is_active', 1 )
        ->exec();
    $rs_cuti_bersama = $qs_cuti_bersama->fetch();

    $qs_minggu = $db
        ->raw()
        ->bind(':tanggal', $tanggal)
        ->exec('SELECT
                    DAYNAME(:tanggal) hari
    ');
    $rs_minggu = $qs_minggu->fetch();
    if ($rs_minggu) {
        $hari = $rs_minggu['hari'];
    } else {
        $hari = '';
    }
    

    if (!empty($rs_holiday) || !empty($rs_cuti_bersama) || $hari == 'Sunday' ) {
        $is_holiday = 1;
    } else {
        $is_holiday = 0;
    }
    
    $data = array(
        'is_holiday' => $is_holiday
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>