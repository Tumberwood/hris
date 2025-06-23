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

	$id_hemxxmh = $_POST['id_hemxxmh'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$qs_lembur_presensi = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.id_hemxxmh,
					b.kode,
					b.nama,
					a.durasi_lembur_total_jam,
					a.pot_jam_final,
					a.durasi_lembur_final,
					a.lembur15,
					a.lembur2,
					a.lembur3	
				FROM htsprrd a
				LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date 
				AND a.id_hemxxmh = :id_hemxxmh
				AND a.durasi_lembur_total_jam > 0
				ORDER BY a.tanggal
	');
	$rs_lembur_presensi = $qs_lembur_presensi->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_lembur_presensi" => $rs_lembur_presensi  
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>