<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $kode_finger = $_POST['kode_finger'];
    $tanggal_ymd = $_POST['tanggal_ymd'];
    $jam = $_POST['jam'];

    $qs_c_peg = $db
        ->raw()
        ->bind(':kode_finger', $kode_finger)
        ->bind(':tanggal', $tanggal_ymd)
        ->bind(':jam', $jam)
        ->exec(' SELECT
                    COUNT(id) AS c_peg,
                    TIME_FORMAT(DATE_ADD(a.jam, INTERVAL 1 HOUR), "%H:%i") AS range_awal,
                    TIME_FORMAT(DATE_ADD(a.jam, INTERVAL -1 HOUR), "%H:%i") AS range_akhir,
                    CASE
                        WHEN TIME_FORMAT(:jam, "%H:%i") BETWEEN TIME_FORMAT(DATE_ADD(a.jam, INTERVAL -1 HOUR), "%H:%i") AND TIME_FORMAT(DATE_ADD(a.jam, INTERVAL 1 HOUR), "%H:%i") THEN "invalid"
                        ELSE "valid"
                    END AS status
                FROM htsprtd AS a
                WHERE a.tanggal = :tanggal 
                    AND a.kode = :kode_finger
                    AND a.is_active = 1
                    AND (nama = "Makan" OR nama = "Makan Manual");
    
                '
                );
    $rs_c_peg = $qs_c_peg->fetch();

    $data = array(
        'peg_makan'=>$rs_c_peg
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>