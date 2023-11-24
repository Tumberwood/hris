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

    $tanggal_select = new Carbon($_POST['tanggal']); //gunakan carbon untuk ambil data tanggal
    $tanggal = $tanggal_select->format('Y-m-d'); //format jadi 2023-09-12

    $qs_approve = $db
        ->raw()
        ->bind(':tanggal', $tanggal)
        ->exec(' SELECT
                    COUNT(id) + ifnull(c_hto,0) + ifnull(c_htl,0) + ifnull(c_htp,0) + ifnull(c_th,0) AS total
                FROM htscctd AS a
                LEFT JOIN (
                    SELECT
                        b.is_active,
                        COUNT(b.id) AS c_hto
                    FROM htoxxth AS b
                    WHERE b.is_approve <> 1 AND b.tanggal = :tanggal
                ) AS b ON b.is_active = 1
                
                LEFT JOIN (
                    SELECT
                        c.is_active,
                        COUNT(c.id) AS c_htl
                    FROM htlxxth AS c
                    WHERE c.is_approve <> 1 AND c.tanggal_awal = :tanggal
                ) AS c ON c.is_active = 1
                
                LEFT JOIN (
                    SELECT
                        d.is_active,
                        COUNT(d.id) AS c_htp
                    FROM htpxxth AS d
                    WHERE d.is_approve <> 1 AND d.tanggal = :tanggal
                ) AS d ON d.is_active = 1
                
                LEFT JOIN (
                    SELECT
                        e.is_active,
                        COUNT(e.id) AS c_th
                    FROM htssctd_tukarhari AS e
                    WHERE e.is_approve <> 1 AND e.tanggal_terpilih = :tanggal
                ) AS e ON e.is_active = 1
                
                WHERE a.is_approve <> 1 AND a.tanggal = :tanggal AND a.is_active = 1
                '
                );
    $rs_approve = $qs_approve->fetch();

    $data = array(
        'rs_approve' => $rs_approve
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>