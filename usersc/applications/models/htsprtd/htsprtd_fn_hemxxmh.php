<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    if($_POST['id_hemxxmh'] != ''){
        $qs_hemxxmh = $db
            ->query('select', 'hemxxmh' )
            ->get([
                'hemxxmh.kode_finger as kode_finger'
            ] )
            ->where('hemxxmh.id', $_POST['id_hemxxmh'] )
            ->exec();
        $rs_hemxxmh = $qs_hemxxmh->fetch();

        $data = array(
            'hemxxmh'=>$rs_hemxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>