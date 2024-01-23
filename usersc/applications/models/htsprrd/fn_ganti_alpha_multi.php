<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$id_htsprrd_arr   = $_POST['id_htsprrd'];

    try {
        $db->transaction();

        foreach ($id_htsprrd_arr as $key => $id_htsprrd) {
            $sql_update = $editor->db()
                ->query('update', 'htsprrd')
                ->set('cek', 0)
                ->set('status_presensi_in', "AL")
                ->set('status_presensi_out', "AL")
                ->where('id', $id_htsprrd)
                ->exec();

            $qs_tgl = $db
                ->query('select', 'htsprrd' )
                ->get(['tanggal'] )
                ->where('id', $id_htsprrd )
                ->exec();
            $rs_tgl = $qs_tgl->fetch();
            $tanggal = $rs_tgl['tanggal'];
            
            $qi_sisa_saldo = $db
                ->raw()
                ->bind(':id_htsprrd', $id_htsprrd)
                ->bind(':tanggal', $tanggal)
                ->exec(' INSERT INTO htlxxrh (
                            id_hemxxmh,
                            nama,
                            saldo
                        )
                        SELECT
                            a.id_hemxxmh,
                            "sisa saldo cuti",
                            SUM(
                                CASE
                                    WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0) + IFNULL(c_rd,0))
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
                                a.id AS id_presensi,
                                id_hemxxmh,
                                COUNT(a.id) AS c_rd
                            FROM htsprrd AS a
                            WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND a.status_presensi_in = "AL"
                            GROUP BY id_hemxxmh
                        ) AS rd ON rd.id_hemxxmh = a.id_hemxxmh
                        
                        WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND jb.is_checkclock = 1 AND a.nama = "saldo" AND id_presensi = :id_htsprrd
                        '
            );
        }

        $db->commit();
        echo json_encode(array('message' => 'Transaksi Berhasil Diproses', 'type_message' => 'success'));
    } catch (PDOException $e) {
        // rollback on error
        $db->rollback();
        echo json_encode(array('message' => 'Transaksi Gagal Diproses! ' . $e->getMessage(), 'type_message' => 'danger'));
    }
    
	
?>