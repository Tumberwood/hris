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

	$id_hpyxxth_2 = $_POST['id_hpyxxth_2'];
	
	$qs_report = $db
		->raw()
		->bind(':id_hpyxxth_2', $id_hpyxxth_2)
		->exec('SELECT
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					if(a.is_approve = 1, "Approved", "Draft") status
				FROM htsprrd a
				LEFT JOIN hpyxxth_2 b ON b.id = :id_hpyxxth_2
				WHERE a.tanggal BETWEEN b.tanggal_awal AND b.tanggal_akhir AND a.is_approve = 0
				GROUP BY a.tanggal
	');
	$rs_report = $qs_report->fetchAll();
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_report" => $rs_report,
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>