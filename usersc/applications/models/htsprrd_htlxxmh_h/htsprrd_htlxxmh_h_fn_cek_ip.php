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
	
	$qs_table_ip = $db
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
				
				/* ========== HANYA record yang dikategorikan ip_pot ========== */
				AND (
						/* 1. TL terlambat & potong HK */
						(a.status_presensi_in = "TL" AND a.st_clock_in = "LATE" AND a.pot_hk > 0)
				
						/* 2. Late tanpa kode toleransi */
						OR (a.st_clock_in = "LATE"  AND a.htlxxrh_kode = "")
				
						/* 3. Pulang Awal (PA) */
						OR ( a.status_presensi_out = "PA" OR (a.status_presensi_in <> "IP" AND a.status_presensi_out = "IP") )
				
						/* 4. Early tanpa kode toleransi */
						OR (a.st_clock_out = "EARLY" AND a.htlxxrh_kode = "")
				
						/* 5. MK (masuk/keluar) */
						OR (a.status_presensi_in = "MK" OR a.status_presensi_out = "MK")
				
						/* 6. Potong HK > 0 yang belum ter‑cover lima kondisi di atas */
						OR (
							a.pot_hk > 0
							AND NOT (
								(a.status_presensi_in = "TL" AND a.st_clock_in = "LATE")
								OR (a.st_clock_in  = "LATE"  AND a.htlxxrh_kode = "")
								OR ( a.status_presensi_out = "PA" OR (a.status_presensi_in <> "IP" AND a.status_presensi_out = "IP") )
								OR (a.st_clock_out = "EARLY" AND a.htlxxrh_kode = "")
								OR (a.status_presensi_in = "MK" OR a.status_presensi_out = "MK")
							)
						)
					)
				ORDER BY a.id_hemxxmh, a.tanggal;
	
	');
	$rs_table_ip = $qs_table_ip->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_table_ip" => $rs_table_ip  
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>