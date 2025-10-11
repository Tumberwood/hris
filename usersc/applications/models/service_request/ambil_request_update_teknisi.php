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

	$id_service_request = $_POST['id_service_request'];
	
	$update = $db
		->raw()
		->bind(':id_service_request', $id_service_request)
		->bind(':id_users_teknisi', $_SESSION['user'])
		->exec('UPDATE service_request a	
				SET a.id_users_teknisi = :id_users_teknisi
				WHERE a.id = :id_service_request
	');

	$data = [
		"message" => "Update berhasil!",
		"type_message" => "success",
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>