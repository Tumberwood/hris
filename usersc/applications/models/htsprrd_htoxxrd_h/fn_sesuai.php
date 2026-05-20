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
	
	$qs_htsprrd_htoxxrd_d = $db
		->raw()
		->bind(':id_htsprrd_htoxxrd_h', $id_htsprrd_htoxxrd_h)
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':is_sesuai', $is_sesuai)
		->exec('UPDATE htsprrd_htoxxrd_d
				SET
					is_sesuai = :is_sesuai
				WHERE id_htsprrd_htoxxrd_h = :id_htsprrd_htoxxrd_h
				AND id_hemxxmh = :id_hemxxmh
	');
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>