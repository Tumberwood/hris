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

    if($_POST['id_htpxxmh'] > 0){
        $qs_htpxxmh = $db
            ->query('select', 'htpxxmh')
            ->get([
                'htpxxmh.jenis_jam as jenis_jam'
            ])
            ->where('id', $_POST['id_htpxxmh'] )
            ->exec();
        $rs_htpxxmh = $qs_htpxxmh->fetch();
        $data = array(
            'rs_htpxxmh' => $rs_htpxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>