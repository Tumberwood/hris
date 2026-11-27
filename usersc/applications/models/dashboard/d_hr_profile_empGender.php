<?php 
    /**
     * Digunakan untuk populate options data branch / cabang
     * Table terkait    : gbrxxmh
     * Parameter        : 
     *  - id_gbrxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    // $tanggal_akhir = $_POST['tanggal_akhir'];
    $tanggal_akhir = new Carbon();
    ;
    
    $qs_hemxxmh = $db
        ->raw()
        ->bind(':tanggal_akhir', $tanggal_akhir->format('Y-m-d'))
        ->exec("
            SELECT
                hemxxmh.gender AS hemxxmh_gender,
                COUNT(hemxxmh.id) AS c_gender
            FROM hemxxmh
            LEFT JOIN hemjbmh
                ON hemjbmh.id_hemxxmh = hemxxmh.id
            WHERE 1
                AND hemjbmh.is_harian_lepas = 0
                AND hemjbmh.is_non_karyawan = 0 
                AND hemxxmh.gender <> ''
                AND (
                    hemjbmh.tanggal_keluar IS NULL
                    OR hemjbmh.tanggal_keluar >= :tanggal_akhir
                )
            GROUP BY hemxxmh.gender
            ORDER BY hemxxmh.gender
        ");

    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'hemxxmh_gender' => $rs_hemxxmh
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>