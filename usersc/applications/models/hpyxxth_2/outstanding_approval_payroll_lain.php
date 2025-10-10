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
	
	$qs_payroll_lain = $db
		->raw()
		->bind(':id_hpyxxth_2', $id_hpyxxth_2)
		->exec('SELECT
					a.*,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					if(a.is_approve = 1, "Approved", "Draft") status,
					c.nama hemxxmh_data,
					d.nama hpcxxmh_data
				FROM hpy_piutang_d a	
				LEFT JOIN hpyxxth_2 b ON b.id = :id_hpyxxth_2
				LEFT JOIN hemxxmh c on c.id = a.id_hemxxmh
				LEFT JOIN hpcxxmh d on d.id = a.id_hpcxxmh
				WHERE a.tanggal BETWEEN b.tanggal_awal AND b.tanggal_akhir AND a.is_approve = 0
	');
	$rs_payroll_lain = $qs_payroll_lain->fetchAll();

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"rs_payroll_lain" => $rs_payroll_lain,
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>