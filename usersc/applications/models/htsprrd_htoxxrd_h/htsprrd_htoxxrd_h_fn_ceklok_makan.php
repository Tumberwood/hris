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
	
	$qs_ceklok_makan = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh)
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					b.kode,
					b.nama,
					a.nama mesin,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.jam,
					a.keterangan
				FROM htsprtd a
				LEFT JOIN hemxxmh b on b.kode_finger = a.kode
				WHERE a.tanggal BETWEEN :start_date AND :end_date 
					AND a.nama IN ("MAKAN", "MAKAN MANUAL")
					AND b.id = :id_hemxxmh
	');
	$rs_ceklok_makan = $qs_ceklok_makan->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_ceklok_makan" => $rs_ceklok_makan,
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>