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

	$tanggal = $_POST['tanggal'];
	$id_hemxxmh = $_POST['id_hemxxmh'];
	$id_users = $_SESSION['user'];

	$qi_cek_anomali_istirahat = $db
		->query('insert', 'cek_anomali_istirahat')
		->set('id_hemxxmh',$id_hemxxmh)
		->set('tanggal',$tanggal)
		->set('created_by',$id_users)
		->exec();
	
	$data = [
		"message" => "Data Berhasil diubah!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>