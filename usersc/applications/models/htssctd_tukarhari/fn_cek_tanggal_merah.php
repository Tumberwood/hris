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

    $qs_libur = $db
        ->raw()
        ->exec('SELECT
                    a.tanggal
                FROM hthhdth a
                WHERE a.is_active = 1

                UNION ALL 

                SELECT
                    a.tanggal
                FROM htlgnth a
                WHERE a.is_active = 1
    ');
    $rs_libur = $qs_libur->fetchAll();
    
    $data = array(
        'rs_libur' => $rs_libur
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>