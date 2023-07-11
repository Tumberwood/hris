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

    if($_POST['id_hemxxmh'] != '' && $_POST['id_hemxxmh'] > 0){
        $qs_hemxxmh = $db
            ->query('select', 'hemxxmh')
            ->get([
                'hemjbmh.id_hovxxmh as id_hovxxmh_awal',
                'hemjbmh.id_hodxxmh as id_hodxxmh_awal',
                'hemjbmh.id_hosxxmh as id_hosxxmh_awal',
                'hemjbmh.id_hevxxmh as id_hevxxmh_awal',
                'hemjbmh.id_hetxxmh as id_hetxxmh_awal',

                'hovxxmh.nama as hovxxmh_awal_nama',
                'hodxxmh.nama as hodxxmh_awal_nama',
                'hosxxmh.nama as hosxxmh_awal_nama',
                'hevxxmh.nama as hevxxmh_awal_nama',
                'hetxxmh.nama as hetxxmh_awal_nama',
            ])
            ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
            ->join('hovxxmh','hovxxmh.id = hemjbmh.id_hovxxmh','LEFT')
            ->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT')
            ->join('hosxxmh','hosxxmh.id = hemjbmh.id_hosxxmh','LEFT')
            ->join('hevxxmh','hevxxmh.id = hemjbmh.id_hevxxmh','LEFT')
            ->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
            ->where('hemxxmh.id', $_POST['id_hemxxmh'] )
            ->exec();
        $rs_hemxxmh = $qs_hemxxmh->fetch();
        $data = array(
            'rs_hemxxmh' => $rs_hemxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>