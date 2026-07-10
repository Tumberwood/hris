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
	
	$qu_htsprrd_htoxxrd_d = $db
		->raw()
		->bind(':id_htsprrd_htoxxrd_h', $id_htsprrd_htoxxrd_h)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':is_sesuai', $is_sesuai)
		->bind(':id_users', $id_users)
		->exec('UPDATE htsprrd_htoxxrd_d d
				JOIN htsprrd_htoxxrd_h h ON h.id = d.id_htsprrd_htoxxrd_h
				LEFT JOIN htsprrd pr ON pr.tanggal = h.tanggal 
					AND pr.id_hemxxmh = d.id_hemxxmh
				SET
					d.sesuai_on = now(),
					d.sesuai_by = :id_users,
					d.is_sesuai = :is_sesuai,
					d.lembur15 = pr.lembur15,
					d.lembur2 = pr.lembur2,
					d.lembur3 = pr.lembur3,
					d.lembur4 = pr.lembur4,
					d.is_makan = pr.is_makan

				WHERE id_htsprrd_htoxxrd_h = :id_htsprrd_htoxxrd_h
				AND id_hemxxmh = :id_hemxxmh
	');
	
	$data = [
		"message" => "Data Berhasil diubah!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>