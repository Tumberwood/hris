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

    $tanggal_awal_select = new Carbon($_POST['tanggal_awal']); //gunakan carbon untuk ambil data tanggal
    $tanggal_awal = $tanggal_awal_select->format('Y-m-d'); //format jadi 2023-09-12

    $tanggal_akhir_select = new Carbon($_POST['tanggal_akhir']); //gunakan carbon untuk ambil data tanggal
    $tanggal_akhir = $tanggal_akhir_select->format('Y-m-d'); //format jadi 2023-09-12

    $id_hemxxmh = $_POST['id_hemxxmh'];
    $id_hgsptth_v3 = $_POST['id_hgsptth_v3'];
    $hari = $_POST['hari'];
    // print_r($hari);

    $qs_detail = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->bind(':hari', $hari)
        ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
        ->exec('SELECT
                    COUNT(id) AS det
                FROM hgsemtd_v3 AS a
                WHERE a.id_hemxxmh = :id_hemxxmh AND a.nama = :hari AND a.id_hgsptth_v3 = :id_hgsptth_v3
        '
    );

    $rs_detail = $qs_detail->fetch();
    $c_jadwal = 0;

    if (!empty($rs_detail) && $rs_detail['det'] > 0) {
        $dimana = '';
    
        switch ($hari) {
            case "senjum":
                $dimana = 'WHERE DAYOFWEEK(tanggal) BETWEEN 2 AND 6';
                break;
            case "minggu":
                $dimana = 'WHERE DAYOFWEEK(tanggal) = 1';
                break;
            case "sabtu":
                $dimana = 'WHERE DAYOFWEEK(tanggal) = 7'; 
                break;
        }
    
        $dimana .= ' AND a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir AND a.id_hemxxmh = :id_hemxxmh AND a.is_active = 1';
    
        $qs_jadwal = $db
            ->raw()
            ->bind(':id_hemxxmh', $id_hemxxmh)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('SELECT
                        COUNT(id) AS c_jadwal
                    FROM htssctd AS a
                    ' . $dimana);
    
        $rs_jadwal = $qs_jadwal->fetch();
        $c_jadwal = $rs_jadwal['c_jadwal'];
    }
    
    $data = array(
        'c_jadwal' => $c_jadwal
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>