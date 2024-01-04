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

    $tanggal = new Carbon($_POST['tanggal']);
    $id_hemxxmh = $_POST['id_hemxxmh'];
    
    $qs_saldo = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->bind(':tanggal', $tanggal)
        ->exec(' SELECT
                    SUM(
                        CASE
                            WHEN a.saldo > 0 THEN a.saldo - COALESCE(cb.c_cb, 0)
                            ELSE 0
                        END
                    ) AS sisa_saldo
                FROM htlxxrh AS a
                -- Izin yang memotong Cuti
                LEFT JOIN (
                    SELECT
                        rh.id_hemxxmh,
                        COUNT(rh.id) AS c_cb
                    FROM htlxxrh AS rh
                    LEFT JOIN htlxxmh AS mh ON mh.id = rh.id_htlxxmh
                    WHERE YEAR(rh.tanggal) = YEAR(:tanggal) AND rh.jenis = 1 AND mh.is_potongcuti = 1
                    GROUP BY rh.id_hemxxmh
                ) AS cb ON cb.id_hemxxmh = a.id_hemxxmh

                WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND a.id_hemxxmh = :id_hemxxmh
                GROUP BY a.id_hemxxmh;

                '
                );
    $rs_saldo = $qs_saldo->fetch();

    $data = array(
        'rs_saldo' => $rs_saldo
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>