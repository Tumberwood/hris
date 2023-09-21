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
    $tanggal_ymd = $_POST['tanggal_ymd'];

    $qs_c_peg = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->bind(':tanggal', $tanggal_ymd)
        ->exec(' SELECT
                    count(id) as c_peg
                FROM htsprtd AS a
                WHERE a.tanggal = :tanggal 
                AND a.id_hemxxmh = :id_hemxxmh
                AND  (nama = "Makan"
                OR nama = "Makan Manual");
                '
                );
    $rs_c_peg = $qs_c_peg->fetch();

    $data = array(
        'peg_makan'=>$rs_c_peg
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>