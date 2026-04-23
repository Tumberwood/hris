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

	$mk   = $_POST['mk'];

    $where = ''; 
    if (isset($_POST['id_heyxxmh']) && ($_POST['id_heyxxmh'] > 0 ) ) {
        $where .= ' AND job.id_heyxxmh =' . $_POST['id_heyxxmh']; 
    }   
    
    $qs_hemxxmh = $db
    ->raw()
    ->bind(':mk', $mk)
    ->exec('SELECT
                a.kode,
                a.nama,
                :mk AS mk,
                DATE_FORMAT(tanggal_lahir, "%d %b %Y") AS tanggal_lahir,
                dep.nama AS departemen,
                hetxxmh.nama AS jabatan,
                hosxxmh.nama AS unit_kerja
            FROM hemxxmh a
            JOIN hemjbmh job ON job.id_hemxxmh = a.id
            LEFT JOIN hodxxmh dep ON dep.id = job.id_hodxxmh
            LEFT JOIN hetxxmh ON hetxxmh.id = job.id_hetxxmh
            LEFT JOIN hosxxmh ON hosxxmh.id = job.id_hosxxmh
            WHERE 1
            AND is_harian_lepas = 0
            AND (tanggal_keluar IS NULL OR tanggal_keluar >= CURDATE() )
            AND TIMESTAMPDIFF(YEAR, tanggal_masuk, CURDATE()) = :mk
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

