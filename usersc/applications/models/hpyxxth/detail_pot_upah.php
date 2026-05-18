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

	$id_transaksi_d = $_POST['id_transaksi_d'];
	
	$qs_periode = $db
		->raw()
		->bind(':id_transaksi_d', $id_transaksi_d)
		->exec('SELECT
					a.id_hemxxmh,
					b.tanggal_awal,
					b.tanggal_akhir
				FROM hpyemtd a
				JOIN hpyxxth b on b.id = a.id_hpyxxth
				WHERE a.id = :id_transaksi_d
	');
	$rs_periode = $qs_periode->fetch();

	if ($rs_periode) {
		$id_hemxxmh = $rs_periode['id_hemxxmh'];
		$tanggal_awal = $rs_periode['tanggal_awal'];
		$tanggal_akhir = $rs_periode['tanggal_akhir'];
	}
	
	$qs_potongan_upah = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':tanggal_awal', $tanggal_awal)
		->bind(':tanggal_akhir', $tanggal_akhir)
		->exec('SELECT
					a.id,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.st_jadwal,
					a.status_presensi_in,
					a.status_presensi_out,
					a.is_pot_upah
				FROM htsprrd a
				WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
				AND a.id_hemxxmh = :id_hemxxmh
				AND a.is_pot_upah = 1
				;
	');
	$rs_potongan_upah = $qs_potongan_upah->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_potongan_upah" => $rs_potongan_upah,
	];
	
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>