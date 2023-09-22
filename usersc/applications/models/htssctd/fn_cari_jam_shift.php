<?php
	/**
     * Digunakan untuk mengambil data dan kalkulasi total penawaran
	 */

    require_once( "../../../../users/init.php" );
    require_once( "../../../../usersc/lib/DataTables.php" );
    require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
 
    use
        DataTables\Editor,
        DataTables\Editor\Query,
        DataTables\Editor\Result;
	
    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	$tanggal = $_POST['tanggal'];
    $id_htsxxmh = $_POST['id_htsxxmh'];

	$qs_htsxxmh = $db
    ->raw()
    ->bind(':id_htsxxmh', $id_htsxxmh)
    ->bind(':tanggal', $tanggal)
    ->exec('SELECT
                concat(:tanggal, " ", a.jam_awal) AS tanggal_jam_awal,
                CASE
                    WHEN a.kode like "malam%" AND a.jam_akhir <= "12:00:00"
                    THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", a.jam_akhir)
                    ELSE CONCAT(:tanggal, " ", a.jam_akhir)
                END AS tanggal_jam_akhir,
                CASE
                    WHEN a.kode like "malam%" AND a.jam_awal_istirahat <= "12:00:00"
                    THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", a.jam_awal_istirahat)
                    ELSE CONCAT(:tanggal, " ", a.jam_awal_istirahat)
                END AS tanggaljam_awal_istirahat,
                CASE
                    WHEN a.kode like "malam%" AND a.jam_akhir_istirahat <= "12:00:00"
                    THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", a.jam_akhir_istirahat)
                    ELSE CONCAT(:tanggal, " ", a.jam_akhir_istirahat)
                END AS tanggaljam_akhir_istirahat
            FROM htsxxmh AS a
            WHERE a.id = :id_htsxxmh
            '
            );
    $rs_htsxxmh = $qs_htsxxmh->fetch();

	$data = array(
        'rs_htsxxmh'=>$rs_htsxxmh
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>