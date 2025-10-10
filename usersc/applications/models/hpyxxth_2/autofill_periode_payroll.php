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

	$id_periode_payroll = $_POST['id_periode_payroll'];
	
	$qs_report = $db
		->raw()
		->bind(':id_periode_payroll', $id_periode_payroll)
		->exec('SELECT
					DATE_FORMAT(a.tanggal_awal, "%d %b %Y") tanggal_awal,
					DATE_FORMAT(a.tanggal_akhir, "%d %b %Y") tanggal_akhir
				FROM periode_payroll a
				WHERE a.id = :id_periode_payroll
	');
	$rs_report = $qs_report->fetch();

	$data = [
		"message" => "Update berhasil!",
		"type_message" => "success",
		"tanggal_awal" => $rs_report['tanggal_awal'],
		"tanggal_akhir" => $rs_report['tanggal_akhir'],
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>