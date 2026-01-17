<?php
	/**
	 * 230602 - 2.1.1
	 * Improve debug
	 * 
	 * 230316 - 2.0.1
	 * Improve where multiple
	 * 	$nama_field (array) : Nama field yang dicek
	 * 	$nama_field_value (array) : Value dari nama_field
	 * 
	 * 230212 - 2.0.0
	 * Digunakan untuk melakukan validasi data unik
	 * Parameter:
	 * 	$table_name: Nama tabel
	 * 	$nama_field: Nama field yang dicek
	 * 	$nama_field_value: Value dari nama_field
	 * 	$id_transaksi: id dari transaksi, dipakai jika edit. jika create maka id harus = 0
	 * 
	 * 200808
	 * Initial version
	 * 	$table_name		: nama table untuk query
	 * 	$where			: kriteria select
	 * result : 
	 * 	0 = Data sudah ada
	 * 	1 = Data baru
	 */

	include_once( "../../usersc/lib/DataTables.php" );
	include_once( "datatables_fn_debug.php" );

	// BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	$id_transaksi     = $_POST['id_transaksi'];

	$table_name       = $_POST['table_name'];
	$nama_field       = explode (",", $_POST['nama_field']);
	$nama_field_value = explode (",", $_POST['nama_field_value']);
	$c_where = count($nama_field_value);

	$where = ' WHERE is_active = 1 AND id <> ' . $id_transaksi;
	for ($x = 0; $x < $c_where; $x++) {
		$where = $where . ' AND ' . $nama_field[$x] . '=' . $nama_field_value[$x];
	}

	// build query
	$query = 'SELECT id ' . ' FROM ' . $table_name . $where;
	
	$qs = $db->sql( $query );
	$result = $qs->count();

	$data = array(
		"count" => $result
	);

	// tampilkan results
    require_once( "fn_ajax_results.php" );

?>