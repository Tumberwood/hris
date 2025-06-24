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

    $id_hemxxmh = $_POST['id_hemxxmh'];
    
    $qs_saldo = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->exec('SELECT
                    a.id_hemxxmh,
                    peg.kode,
                    peg.nama,
                    a.nama,
                    sum(saldo) sisa_saldo
                FROM htlxxrh a
                INNER JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                INNER JOIN hemjbmh AS jb ON jb.id_hemxxmh = peg.id
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        COUNT(a.id) AS c_rd
                    FROM htsprrd AS a
                    WHERE YEAR(a.tanggal) = YEAR(CURDATE()) AND a.status_presensi_in = "AL"
                    AND a.id_hemxxmh = :id_hemxxmh
                    GROUP BY id_hemxxmh
                ) AS rd ON rd.id_hemxxmh = a.id_hemxxmh
                WHERE YEAR(a.tanggal) = YEAR(CURDATE()) AND a.id_hemxxmh = :id_hemxxmh
                ;
                '
                );
    $rs_saldo = $qs_saldo->fetch();
    if(empty($rs_saldo)) {
        $rs_saldo['sisa_saldo'] = 0;
    }

    $data = array(
        'rs_saldo' => $rs_saldo
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>