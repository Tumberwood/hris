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

    if($_POST['id_hetxxmh'] != '' && $_POST['id_hetxxmh'] > 0){
        $id_hetxxmh = $_POST['id_hetxxmh'];
        $qs_hemxxmh = $db
            ->raw()
            ->bind(':id_hetxxmh', $id_hetxxmh)
            ->exec('SELECT
                        id_hevgrmh,
                        b.nama as hevgrmh_akhir_nama
                    FROM hetxxmh a
                    LEFT JOIN hevgrmh b on b.id = a.id_hevgrmh
                    WHERE a.id = :id_hetxxmh
        ');
        $rs_hemxxmh = $qs_hemxxmh->fetch();
        $data = array(
            'rs_hemxxmh' => $rs_hemxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>