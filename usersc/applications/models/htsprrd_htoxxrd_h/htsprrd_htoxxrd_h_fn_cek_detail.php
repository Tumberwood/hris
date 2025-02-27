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
	
	$qs_detail_upload = $db
		->raw()
		->bind(':id_htsprrd_htoxxrd_h', $id_htsprrd_htoxxrd_h)
		->exec('SELECT
					COUNT(a.id) c_id
				FROM htsprrd_htoxxrd_d a
				WHERE a.id_htsprrd_htoxxrd_h = :id_htsprrd_htoxxrd_h
	');
	$dataRows = $qs_detail_upload->fetch();
	$c_id = $dataRows['c_id'];

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"c_id" => $c_id  
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>