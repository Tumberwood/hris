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
        ->exec('SELECT
                    a.id_hemxxmh,
                    peg.kode,
                    peg.nama,
                    COALESCE(cb.c_cb, 0) AS c_cb,
                    IFNULL(c_rd, 0) AS c_rd,
                    ifnull(a.saldo,0) AS saldo,
                    SUM(
                        CASE
                            WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0) )
                            ELSE 0
                        END
                    ) AS sisa_saldo
                FROM htlxxrh AS a
                -- employee
                LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = peg.id
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
                
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        COUNT(a.id) AS c_rd
                    FROM htsprrd AS a
                    WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND a.status_presensi_in = "AL"
                    GROUP BY id_hemxxmh
                ) AS rd ON rd.id_hemxxmh = a.id_hemxxmh

                WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND peg.id = :id_hemxxmh AND a.nama = "saldo"
                GROUP BY a.id_hemxxmh 

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