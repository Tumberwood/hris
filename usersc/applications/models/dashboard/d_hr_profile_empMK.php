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
            'TIMESTAMPDIFF(YEAR, hemjbmh.tanggal_masuk, CURDATE()) as masakerja',
            'COUNT(*) as c_masakerja'
        ] )
        ->where( function ( $r ) {
            $r
                ->where( 'hemjbmh.tanggal_keluar', '0000-00-00', '<>')
                ->or_where( 'hemjbmh.tanggal_keluar', NULL, '<>');
        } )
        ->group_by('masakerja')
        ->order('masakerja')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'Masa Kerja';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	
	foreach ($rs_hemxxmh as $key => $row) {
		$category['data'][] = $row['masakerja'];
		$series1['data'][] = $row['c_masakerja'];
	}
	
	$results = array();
	array_push($results,$category);
	array_push($results,$series1);

    $data = array(
        'd_hr_profile_empMK' => $results
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

