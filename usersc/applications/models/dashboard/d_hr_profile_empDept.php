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
            ->raw()
            ->exec('SELECT
                        c.nama hodxxmh_nama,
                        SUM(case when a.gender = "Laki-laki" then 1 ELSE 0 END) c_age_laki,
                        SUM(case when a.gender = "Perempuan" then 1 ELSE 0 END) c_age_perempuan
                    FROM hemxxmh a
                    LEFT JOIN hemjbmh b ON b.id_hemxxmh = a.id
                    LEFT JOIN hodxxmh c ON c.id = b.id_hodxxmh
                    WHERE (b.tanggal_keluar IS NULL OR b.tanggal_keluar >= CURDATE() )
                    GROUP BY b.id_hodxxmh
        ');
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