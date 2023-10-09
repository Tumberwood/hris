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

    
    $id_hgsptth_new = $_POST['id_hgsptth_new'];

    $qs_pola = $db
        ->raw()
        ->bind(':id_hgsptth_new', $id_hgsptth_new)
        ->exec('SELECT
                    a.senin,
                    a.selasa,
                    a.rabu,
                    a.kamis,
                    a.jumat,
                    a.sabtu,
                    a.minggu
                FROM hgsemtd_new AS a
                WHERE a.id_hgsptth_new =  :id_hgsptth_new;
    
                '
                );
    $rs_pola = $qs_pola->fetch();

    $data = array(
        'rs_pola' => $rs_pola
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>