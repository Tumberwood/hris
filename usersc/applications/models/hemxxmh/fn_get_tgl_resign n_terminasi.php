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

    $qs_tgl_akhir = $db
    ->raw()
    ->bind(':id_hemxxmh', $_POST['id_hemxxmh'])
    ->exec(' SELECT
                a.id_hemxxmh,
                a.tanggal_akhir
            FROM hemjbrd a
            WHERE a.id_harxxmh IN (3,4) AND a.id_hemxxmh = :id_hemxxmh
                            '
        );
    $rs_tgl_akhir = $qs_tgl_akhir->fetch();

    $tgl_akhir = null;
    if (!empty($rs_tgl_akhir)) {
        $tgl_akhir = $rs_tgl_akhir['tanggal_akhir'];
    }

    $data = array(
        'tgl_akhir' => $tgl_akhir
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>