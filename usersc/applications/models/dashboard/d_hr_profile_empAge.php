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
        ->query('select', 'hemxxmh' )
        ->get([
            'TIMESTAMPDIFF(YEAR, hemxxmh.tanggal_lahir, CURDATE()) as hemxxmh_age',
            'COUNT(*) as c_age'
        ] )
        ->where('is_active',1)
        ->group_by('hemxxmh_age')
        ->order('hemxxmh_age')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'Usia';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	
	foreach ($rs_hemxxmh as $key => $row) {
		$category['data'][] = $row['hemxxmh_age'];
		$series1['data'][] = $row['c_age'];
	}
	
	$results = array();
	array_push($results,$category);
	array_push($results,$series1);

    $data = array(
        'results_emp_age' => $results
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

