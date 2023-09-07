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

    $id_hemxxmh = $_POST['id_hemxxmh'];
    $tanggal = $_POST['tanggal'];

    $qs_jam_istirahat = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->bind(':tanggal', $tanggal)
        ->exec(' SELECT
                    TIME_FORMAT(DATE_ADD(a.jam_awal_istirahat, INTERVAL 1 HOUR), "%H:%i") AS jam,
                    a.jam_awal_istirahat,
                    a.jam_akhir_istirahat
                FROM htssctd AS a
                WHERE a.tanggal = :tanggal AND a.id_hemxxmh = :id_hemxxmh;
                '
                );
    $rs_jam_istirahat = $qs_jam_istirahat->fetch();

    $data = array(
        'jam_istirahat'=>$rs_jam_istirahat
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>