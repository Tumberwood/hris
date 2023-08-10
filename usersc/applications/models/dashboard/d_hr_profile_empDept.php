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
        ->query('select', 'v_emp_dept' )
        ->get([
            'v_emp_dept.hodxxmh_nama as hodxxmh_nama',
            'SUM(if(v_emp_dept.hemxxmh_gender="Laki-laki",v_emp_dept.c_gender_dept,0)) as c_age_laki',
            'SUM(if(v_emp_dept.hemxxmh_gender="Perempuan",v_emp_dept.c_gender_dept,0)) as c_age_perempuan'
        ] )
        ->group_by('v_emp_dept.hodxxmh_nama')
        ->order('v_emp_dept.hodxxmh_nama')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'Usia';
	
	$series1 = array();
	$series1['name'] =  'Laki-laki';

    $series2 = array();
	$series2['name'] =  'Perempuan';
	
	foreach ($rs_hemxxmh as $key => $row) {
		$category['data'][] = $row['hodxxmh_nama'];
		$series1['data'][] = $row['c_age_laki'];
		$series2['data'][] = $row['c_age_perempuan'];
	}
	
	$results = array();
	array_push($results,$category);
	array_push($results,$series1);
	array_push($results,$series2);

    $data = array(
        'results_empdept' => $results
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>