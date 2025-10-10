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
	
	$update = $db
		->raw()
		->bind(':id_hpyxxth_2', $id_hpyxxth_2)
		->exec('UPDATE hpy_piutang_d a	
				LEFT JOIN hpyxxth_2 b ON b.id = :id_hpyxxth_2
				SET a.is_approve = 1
				WHERE a.tanggal BETWEEN b.tanggal_awal AND b.tanggal_akhir AND a.is_approve = 0
	');

	$data = [
		"message" => "Update berhasil!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>