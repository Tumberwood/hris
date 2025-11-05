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
            'heyxxmh.nama as heyxxmh_nama',
            'COUNT(*) as c_heyxxmh_nama'
        ] )
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        ->join('heyxxmh','heyxxmh.id = hemjbmh.id_heyxxmh','LEFT' )
        ->where( function ( $r ) use ($tanggal_akhir) {
            $r
                ->where( 'hemjbmh.tanggal_keluar', NULL)
                // ->or_where( 'hemjbmh.tanggal_keluar', '0000-00-00')
                ->or_where( 'hemjbmh.tanggal_keluar', $tanggal_akhir->format('Y-m-d') , '>=');
        } )
        ->group_by('heyxxmh.id')
        ->order('heyxxmh.id')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $category = array();
	$category['name'] = 'Tipe';
	
	$series1 = array();
	$series1['name'] =  'Karyawan';
	
    $results = array();

	foreach ($rs_hemxxmh as $key => $row) {
        $r[0] = $row['heyxxmh_nama'];
		$r[1] = $row['c_heyxxmh_nama'];
		array_push($results,$r);
	}

    $data = array(
        'd_hr_profile_empType' => $results
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

