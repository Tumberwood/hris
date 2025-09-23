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
	
	$qs_table_sdl = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.id_hemxxmh,
					TRIM(b.kode) AS kode,
					b.nama,
					a.status_presensi_in,
					a.status_presensi_out,
					a.st_clock_in,
					a.st_clock_out,
					a.pot_hk,
					a.htlxxrh_kode
				FROM htsprrd a
				LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date
				AND a.is_active = 1 AND a.id_hemxxmh = :id_hemxxmh
				AND status_presensi_in IN ("SDL", "S3")
				ORDER BY a.id_hemxxmh, a.tanggal;
	
	');
	$rs_table_sdl = $qs_table_sdl->fetchAll();
	
	$qs_absen_sdl = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.id_hemxxmh,
					TRIM(b.kode) AS kode,
					b.nama,
					a.kode kode_absen,
					a.keterangan
				FROM htlxxrh a
				LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date
				AND a.is_active = 1 AND a.id_hemxxmh = :id_hemxxmh
				AND a.id_id_htlxxmh = 18 -- SDL , S3
				ORDER BY a.id_hemxxmh, a.tanggal;
	
	');
	$rs_absen_sdl = $qs_absen_sdl->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_table_sdl" => $rs_table_sdl,
		"rs_absen_sdl" => $rs_absen_sdl,
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>