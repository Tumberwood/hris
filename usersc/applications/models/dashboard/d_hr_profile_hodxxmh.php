<?php 
    /**
     * Digunakan 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $qs_hemxxmh = $db
    ->raw()
    ->exec('SELECT
                c.nama AS nama,
                SUM(CASE WHEN b.id_heyxxmh = 1 THEN 1 ELSE 0 END) AS c_pmi,
                SUM(CASE WHEN b.id_heyxxmh = 2 THEN 1 ELSE 0 END) AS c_os,
                COUNT(a.id) AS c_total
            FROM hemxxmh a
            LEFT JOIN hemjbmh b ON b.id_hemxxmh = a.id
            LEFT JOIN hodxxmh c ON c.id = b.id_hodxxmh
            WHERE (b.tanggal_keluar IS NULL OR b.tanggal_keluar >= CURDATE())
            AND b.is_harian_lepas = 0
            GROUP BY c.nama
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

