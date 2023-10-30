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

    
    $id_htsptth_v3 = $_POST['id_htsptth_v3'];
    $shift = $_POST['shift'];
    
    $qs_jam = $db
        ->raw()
        ->bind(':id_htsptth_v3', $id_htsptth_v3)
        ->bind(':shift', $shift)
        ->exec('SELECT 
                    CONCAT(TIME_FORMAT(c.jam_awal, "%H:%i"), " - ", TIME_FORMAT(c.jam_akhir, "%H:%i")) AS jam,
                    c.id as id
                FROM htsptth_v3 AS a
                LEFT JOIN htspttd_v3 AS b ON b.id_htsptth_v3 = a.id
                LEFT JOIN htsxxmh AS c ON c.id = b.id_htsxxmh
                WHERE a.id = :id_htsptth_v3 AND b.shift = :shift; 

                '
                );
    $rs_jam = $qs_jam->fetch();

    $data = array(
        'rs_jam' => $rs_jam
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>