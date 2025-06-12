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
	
	$qs_pot_makan = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					b.kode,
					a.id_hemxxmh,
					b.nama,
					a.st_jadwal jadwal,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					is_makan
				FROM htsprrd a
				LEFT JOIN hemxxmh b on b.id = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date 
					AND a.is_makan > 0
					AND b.id = :id_hemxxmh
	');
	$rs_pot_makan = $qs_pot_makan->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_pot_makan" => $rs_pot_makan  
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>