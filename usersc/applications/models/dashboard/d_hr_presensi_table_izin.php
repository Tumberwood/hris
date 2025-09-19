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

	$start_date = $_POST['start_date'];
	$end_date   = $_POST['end_date'];
	$dept   = $_POST['dept'];
	$izin   = $_POST['izin'];

    $qs_hemxxmh = $db
    ->raw()
    ->bind(':dept', $dept)
    ->bind(':izin', $izin)
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' SELECT
                a.id,
                date_format(a.tanggal, "%d %b %Y") tanggal,
                a.kode,
                peg.nama,
                dep.nama departemen,
                izin.nama AS jenis,
                a.jam_awal,
                a.jam_akhir,
                a.keterangan
            FROM htlxxrh AS a
            LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
            LEFT JOIN htpxxmh AS izin ON izin.id = a.id_htlxxmh
            INNER JOIN hemjbmh AS jb on jb.id_hemxxmh = peg.id
            LEFT JOIN hodxxmh AS dep on dep.id = jb.id_hodxxmh
            WHERE a.jenis = 2 
                AND a.is_active = 1 
                AND a.tanggal BETWEEN :start_date AND :end_date and dep.nama = :dept AND izin.nama = :izin
            ORDER BY izin.id ASC
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
        'start_date' => $start_date,
        'end_date' => $end_date
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

