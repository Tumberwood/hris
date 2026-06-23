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

    $id = $_POST['id'];
    if($id != '' && $id > 0){
        $qs_hemxxmh = $db
            ->query('select', 'periode_payroll' )
            ->get([
                'DATE_FORMAT(tanggal_awal, "%d %b %Y") as tanggal_awal',
                'DATE_FORMAT(tanggal_akhir, "%d %b %Y") as tanggal_akhir'
            ] )
            ->where('id', $id )
            ->exec();
        $rs_hemxxmh = $qs_hemxxmh->fetch();

        $data = array(
            'rs_hemxxmh' => $rs_hemxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>