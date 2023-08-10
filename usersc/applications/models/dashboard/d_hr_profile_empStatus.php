<?php 
    /**
         * Digunakan 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    // $tanggal_akhir = $_POST['tanggal_akhir'];
    $tanggal_akhir = new Carbon();

    $qs_hemxxmh = $db
        ->query('select', 'hemjbmh' )
        ->get([
            'hesxxmh.nama as hesxxmh_nama',
            'COUNT(*) as c_hesxxmh_nama'
        ] )
        ->join('hesxxmh','hesxxmh.id = hemjbmh.id_hesxxmh','LEFT' )
        ->join('hemxxmh','hemxxmh.id = hemjbmh.id_hemxxmh','LEFT' )
        ->where( 'hemxxmh.is_active', 1)
        ->group_by('hesxxmh.id')
        ->order('hesxxmh.id')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'Tipe';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	
    $results = array();

	foreach ($rs_hemxxmh as $key => $row) {
        $r[0] = $row['hesxxmh_nama'];
		$r[1] = $row['c_hesxxmh_nama'];
		array_push($results,$r);
	}

    $data = array(
        'd_hr_profile_empStatus' => $results
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

