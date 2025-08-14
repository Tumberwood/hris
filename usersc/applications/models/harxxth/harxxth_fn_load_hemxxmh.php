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

    $id_harxxth = $_POST['id_harxxth'];
    if($_POST['id_hemxxmh'] != '' && $_POST['id_hemxxmh'] > 0){
        if ($id_harxxth > 0) {
            $qs_hemxxmh = $db
                ->query('select', 'harxxth')
                ->get([
                    'id_hovxxmh_awal',
                    'id_hodxxmh_awal',
                    'id_hosxxmh_awal',
                    'id_hevxxmh_awal',
                    'id_hetxxmh_awal',
                    'id_holxxmd_2_awal',
    
                    'hovxxmh.nama as hovxxmh_awal_nama',
                    'hodxxmh.nama as hodxxmh_awal_nama',
                    'hosxxmh.nama as hosxxmh_awal_nama',
                    'hevxxmh.nama as hevxxmh_awal_nama',
                    'hetxxmh.nama as hetxxmh_awal_nama',
                    'holxxmd_2.nama as holxxmd_2_awal_nama',
                ])
                ->join('hovxxmh','hovxxmh.id = harxxth.id_hovxxmh_awal','LEFT')
                ->join('hodxxmh','hodxxmh.id = harxxth.id_hodxxmh_awal','LEFT')
                ->join('hosxxmh','hosxxmh.id = harxxth.id_hosxxmh_awal','LEFT')
                ->join('hevxxmh','hevxxmh.id = harxxth.id_hevxxmh_awal','LEFT')
                ->join('hetxxmh','hetxxmh.id = harxxth.id_hetxxmh_awal','LEFT')
                ->join('holxxmd_2','holxxmd_2.id = harxxth.id_holxxmd_2_awal','LEFT')
                ->where('harxxth.id', $id_harxxth)
                ->exec();
            $rs_hemxxmh = $qs_hemxxmh->fetch();
        } else {
            $qs_hemxxmh = $db
                ->query('select', 'hemxxmh')
                ->get([
                    'hemjbmh.id_hovxxmh as id_hovxxmh_awal',
                    'hemjbmh.id_hodxxmh as id_hodxxmh_awal',
                    'hemjbmh.id_hosxxmh as id_hosxxmh_awal',
                    'hemjbmh.id_hevxxmh as id_hevxxmh_awal',
                    'hemjbmh.id_hetxxmh as id_hetxxmh_awal',
                    'hemjbmh.id_holxxmd_2 as id_holxxmd_2_awal',
    
                    'hovxxmh.nama as hovxxmh_awal_nama',
                    'hodxxmh.nama as hodxxmh_awal_nama',
                    'hosxxmh.nama as hosxxmh_awal_nama',
                    'hevxxmh.nama as hevxxmh_awal_nama',
                    'hetxxmh.nama as hetxxmh_awal_nama',
                    'holxxmd_2.nama as holxxmd_2_awal_nama',
                ])
                ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
                ->join('hovxxmh','hovxxmh.id = hemjbmh.id_hovxxmh','LEFT')
                ->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT')
                ->join('hosxxmh','hosxxmh.id = hemjbmh.id_hosxxmh','LEFT')
                ->join('hevxxmh','hevxxmh.id = hemjbmh.id_hevxxmh','LEFT')
                ->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
                ->join('holxxmd_2','holxxmd_2.id = hemjbmh.id_holxxmd_2','LEFT')
                ->where('hemxxmh.id', $_POST['id_hemxxmh'] )
                ->exec();
            $rs_hemxxmh = $qs_hemxxmh->fetch();
        }
        $data = array(
            'rs_hemxxmh' => $rs_hemxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>