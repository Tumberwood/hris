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

    $state          = $_POST['state'];
    
    try{
        $db->transaction();

        $qs_htlgnth = $db
            ->query('select', 'htlgnth' )
            ->get([
                'htlgnth.tanggal as tanggal',
                'htlgnth.kode as kode',
                'htlgnth.nama as nama'
            ] )
            ->where('htlgnth.id', $_POST['id_transaksi_h'] )
            ->exec();
        $rs_htlgnth = $qs_htlgnth->fetch();

        if ($state == 1) {
            
            // BEGIN non aktif
            $qd_terpilih = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 0)
                ->where('tanggal', $rs_htlgnth["tanggal"])
            ->exec();
    
            $tanggal_jam_off = $rs_htlgnth["tanggal"] . " 00:00:00";
    
            $qu_htssctd = $db
            ->raw()
            ->bind(':keterangan', $rs_htlgnth["nama"])
            ->bind(':tanggal', $rs_htlgnth["tanggal"])
            ->exec('UPDATE htssctd AS a
                LEFT JOIN hemjbmh AS jb on jb.id_hemxxmh = a.id_hemxxmh
                SET
                    a.is_active = 0,
                    a.keterangan = CONCAT("Cuti Bersama - ", :keterangan)
                WHERE 
                    a.is_active = 1
                AND a.tanggal = :tanggal
                AND NOT (
                    id_hetxxmh IN (99, 48) 
                    OR id_heyxxmd = 1 
                    OR (id_heyxxmd = 2 AND id_hesxxmh = 2) 
                    OR (id_heyxxmd = 3 AND id_hesxxmh = 2)
                    OR id_htsxxmh = 1
                );
            ');

            // Begin insert pengaju
            $qr_tanggal = $db
                ->raw()
                ->bind(':tanggal', $rs_htlgnth["tanggal"])
                ->bind(':nama', $rs_htlgnth["nama"])
                ->bind(':tanggal_jam_off', $tanggal_jam_off)
                ->exec('INSERT INTO htssctd
                    (
                        keterangan,
                        tanggal,
                        id_hemxxmh,
                        id_htsxxmh,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_akhir_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_out,

                        tanggaljam_awal_t1,
                        tanggaljam_awal,
                        tanggaljam_awal_t2,
                        tanggaljam_akhir_t1,
                        tanggaljam_akhir,
                        tanggaljam_akhir_t2,
                        tanggaljam_awal_istirahat,
                        tanggaljam_akhir_istirahat
                    )
                    SELECT DISTINCT
                        CONCAT("Cuti Bersama - ", :nama),
                        :tanggal,
                        htssctd.id_hemxxmh,
                        1,
                        "00:00:00",
                        "00:00:00",
                        "00:00:00",
                        "00:00:00",
                        0,
                        0,
                        0,
                        0,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off,
                        :tanggal_jam_off
                       
                    FROM htssctd
                    LEFT JOIN hemjbmh AS jb on jb.id_hemxxmh = htssctd.id_hemxxmh
                    WHERE 
                        tanggal = :tanggal
                    AND keterangan = CONCAT("Cuti Bersama - ", :nama) 
                ');
            // END insert pengaju

            // Update Untuk mendapatkan flag
            $qu_tanggal = $db
                ->raw()
                ->bind(':tanggal', $rs_htlgnth["tanggal"])
                ->exec('UPDATE htssctd AS jad
                        LEFT JOIN 
                        (
                            SELECT
                                a.id_hemxxmh,
                                "sisa saldo cuti",
                                :tanggal,
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
                            
                            WHERE YEAR(a.tanggal) = YEAR(:tanggal) AND jb.is_checkclock = 1 AND a.nama = "saldo"
                            GROUP BY a.id_hemxxmh
                        ) AS sal ON sal.id_hemxxmh = jad.id_hemxxmh
                        LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = jad.id_hemxxmh
                        SET 
                            jad.is_pot_hk = 
                                case 
                                    when ifnull(sisa_saldo,0) = 0 AND jad.id_htsxxmh <> 1 AND id_hetxxmh NOT IN (99, 48) then 1
                                    when ifnull(sisa_saldo,0) = 0 AND jad.keterangan LIKE "%cuti bersama%" then 1
                                ELSE 0
                                END ,
                            jad.is_pot_cuti = if(IFNULL(sisa_saldo,0) > 0 AND jad.keterangan LIKE "%cuti bersama%", 1, 0)
                        WHERE jad.tanggal = :tanggal
                        AND jad.is_active = 1;
                ');
            //End flag is_pot_hk dan is_pot_cuti
            
            //INI TETAP DIINSERT
            $qr_htsprrd = $db
                ->raw()
                ->bind(':tanggal', $rs_htlgnth["tanggal"])
                ->exec('
                    INSERT INTO htlxxrh
                        (
                            id_transaksi,
                            id_htlgrmh,
                            id_htlxxmh,
                            id_hemxxmh,
                            kode,
                            tanggal,
                            keterangan,
                            jenis,
                            htlxxmh_kode,
                            htlgrmh_kode,
                            jumlah,
                            jam_awal,
                            jam_akhir
                        )
                    SELECT
                        ' . $_POST['id_transaksi_h'] . ',
                        2,
                        2,
                        hem.id,
                        "'.$rs_htlgnth["kode"].'",
                        "'.$rs_htlgnth["tanggal"].'",
                        "'.$rs_htlgnth["nama"].'",
                        1,
                        "CB",
                        "CB",
                        1,
                        null,
                        null
                        FROM
                            htssctd AS a
                        LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = hem.id
                        WHERE 
                            a.is_active = 1
                        AND a.tanggal = :tanggal
                        AND a.is_pot_cuti = 1
                ');
            // BEGIN non aktif

        } else if($state == 2) {
            $qd_htl = $db
                ->query('delete', 'htlxxrh')
                ->where('tanggal', $rs_htlgnth["tanggal"])
                ->where('kode', $rs_htlgnth["kode"])
                ->where('keterangan', $rs_htlgnth["nama"])
            ->exec();

            $qd_pengganti = $db
                ->raw()
                ->bind(':tanggal', $rs_htlgnth["tanggal"])
                ->bind(':nama', $rs_htlgnth["nama"])
                ->exec(' DELETE FROM htssctd
                        WHERE is_active = 1
                            AND tanggal = :tanggal
                            AND keterangan = CONCAT("Cuti Bersama - ", :nama)
                            ;
                    ');
            
            $qu_htssctd = $db
                ->query('update', 'htssctd')
                ->set('is_active', 1)
                ->set('keterangan', "")
                ->where('is_active',0)
                ->where('tanggal', $rs_htlgnth["tanggal"])
            ->exec();
        }

        $db->commit();
        $data = array(
            'message'=> 'Data Berhasil Di Insert' , 
            'type_message'=>'success' )
        ;
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Data Gagal Di Insert', 
            'type_message'=>'danger' 
        );
    }
   
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>