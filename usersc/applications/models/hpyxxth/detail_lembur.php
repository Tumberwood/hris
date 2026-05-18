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
	
	$qs_lembur = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':tanggal_awal', $tanggal_awal)
		->bind(':tanggal_akhir', $tanggal_akhir)
		->exec('SELECT
					a.id,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.st_jadwal,
					b.kode spkl,
					c.nama jenis_lembur,
					CASE
						WHEN b.is_istirahat = 0 THEN "Tidak"
						WHEN b.is_istirahat = 1 THEN "Ya"
						WHEN b.is_istirahat = 2 THEN "TI"
						WHEN b.is_istirahat = 3 THEN "Istirahat 2x"
						ELSE ""
					END AS status_istirahat,
					b.durasi_lembur_jam durasi_spkl,
					a.pot_ti,
					a.pot_overtime,
					a.pot_hk,
					a.pot_jam,
					a.durasi_lembur_final lembur_final
				FROM htsprrd a
				JOIN htoxxrd b ON b.tanggal = a.tanggal AND b.id_hemxxmh = a.id_hemxxmh AND b.is_active = 1
				JOIN htotpmh c ON c.id = b.id_htotpmh
				WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
				AND a.id_hemxxmh = :id_hemxxmh
				AND a.is_pot_upah = 1
				;
	');
	$rs_lembur = $qs_lembur->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_lembur" => $rs_lembur,
	];
	
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>