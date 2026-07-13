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

	$id_htsprrd_htoxxrd_h = $_POST['id_htsprrd_htoxxrd_h'];
	$id_hemxxmh = $_POST['id_hemxxmh'];
	$is_sesuai = $_POST['is_sesuai'];
	$id_users = $_SESSION['user'];

	$qs_htsprrd_htoxxrd_h = $db
		->query('select', 'htsprrd_htoxxrd_h' )
		->get([
			'tanggal_awal',
			'tanggal_akhir',
		] )
		->where('id', $id_htsprrd_htoxxrd_h )
		->exec();
	$rs_htsprrd_htoxxrd_h = $qs_htsprrd_htoxxrd_h->fetch();
	$tanggal_awal = $rs_htsprrd_htoxxrd_h['tanggal_awal'];
	$tanggal_akhir = $rs_htsprrd_htoxxrd_h['tanggal_akhir'];
	
	$qu_htsprrd_htoxxrd_d = $db
		->raw()
		->bind(':id_htsprrd_htoxxrd_h', $id_htsprrd_htoxxrd_h)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':is_sesuai', $is_sesuai)
		->bind(':id_users', $id_users)
		->bind(':tanggal_awal', $tanggal_awal)
		->bind(':tanggal_akhir', $tanggal_akhir)
		->exec('UPDATE htsprrd_htoxxrd_d d
				LEFT JOIN (
					SELECT
						a.id_hemxxmh,
						TRIM(b.kode) AS kode,
						b.nama,
						SUM(IFNULL(lembur15,0)) AS sum_lembur15,
						SUM(IFNULL(lembur2,0)) AS sum_lembur2,
						SUM(IFNULL(lembur3,0)) AS sum_lembur3,
						SUM(IFNULL(lembur4,0)) AS sum_lembur4,
						SUM(IFNULL(a.is_makan,0)) AS sum_makan
					FROM htsprrd a
					LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
					WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
					AND a.is_active = 1
					AND a.id_hemxxmh = :id_hemxxmh
				) pr ON pr.id_hemxxmh = d.id_hemxxmh
				SET
					d.sesuai_on = now(),
					d.sesuai_by = :id_users,
					d.is_sesuai = :is_sesuai,
					d.lembur15 = sum_lembur15,
					d.lembur2 = sum_lembur2,
					d.lembur3 = sum_lembur3,
					d.lembur4 = sum_lembur4,
					d.makan = sum_makan

				WHERE id_htsprrd_htoxxrd_h = :id_htsprrd_htoxxrd_h
				AND d.id_hemxxmh = :id_hemxxmh
	');
	
	$data = [
		"message" => "Data Berhasil diubah!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>