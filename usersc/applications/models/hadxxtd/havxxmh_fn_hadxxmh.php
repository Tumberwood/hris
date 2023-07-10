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

    if($_POST['id_havxxmh'] != '' && $_POST['id_havxxmh'] > 0){
        $qs_havxxmh = $db
            ->query('select', 'havxxmh')
            ->get([
                'havxxmh.id_hadxxmh as id_hadxxmh'
            ])
            ->join('hadxxmh','hadxxmh.id = havxxmh.id_hadxxmh','LEFT')
            ->where('havxxmh.id', $_POST['id_havxxmh'] )
            ->exec();
        $rs_havxxmh = $qs_havxxmh->fetch();
        $data = array(
            'rs_havxxmh' => $rs_havxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>