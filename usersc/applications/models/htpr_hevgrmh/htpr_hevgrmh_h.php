<?php 
    /**
     * Digunakan 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $where = ''; 

    $qs_hemxxmh = $db
    ->raw()
    ->exec('SELECT
				a.id_hevgrmh,
				a.id_heyxxmd,
				a.id_hesxxmh,
				b.nama grup,
				c.nama sub_tipe,
				d.nama status_peg
			FROM htpr_hevgrmh_mk a
			LEFT JOIN hevgrmh b ON b.id = a.id_hevgrmh
			LEFT JOIN heyxxmd c ON c.id = a.id_heyxxmd
			LEFT JOIN hesxxmh d ON d.id = a.id_hesxxmh
			WHERE a.is_active = 1
			GROUP BY a.id_hevgrmh, a.id_heyxxmd, a.id_hesxxmh
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

