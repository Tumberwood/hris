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

    $qs_kmj = $db
        ->query('select', 'hemjbmh')
        ->get([
            'id_heyxxmd'
        ])
        ->where('id_hemxxmh', $_POST['id_hemxxmh_select'] )
        ->exec();
    $rs_kmj = $qs_kmj->fetch();
    
    $data = array(
        'rs_kmj' => $rs_kmj
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>