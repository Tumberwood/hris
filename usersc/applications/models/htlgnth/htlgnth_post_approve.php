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
            //INI TETAP DIINSERT
            $qr_htsprrd = $db
                ->raw()
                ->bind(':is_active', 1)
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
                        hemxxmh.id,
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
                        hemxxmh
                    LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = hemxxmh.id
                    WHERE 
                        hemxxmh.is_active = :is_active AND jb.id_hetxxmh NOT IN (99, 48);
                ');
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
                    tanggal = :tanggal AND jb.id_hetxxmh NOT IN (99, 48)
                    AND a.is_active = 1
                    ;
            ');
            //     ->query('update', 'htssctd')
            //     ->set('is_active',0)
            //     ->set('keterangan', "Cuti Bersama - " . $rs_htlgnth["nama"])
            //     ->where('tanggal', $rs_htlgnth["tanggal"])
            //     ->where('is_active', 1)
            // ->exec();

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
                    SELECT
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
                    AND keterangan = CONCAT("Cuti Bersama - ", :nama) AND jb.id_hetxxmh NOT IN (99, 48)
                ');
            // END insert pengaju

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
                ->exec(' DELETE FROM htssctd
                        WHERE 
                            id_hemxxmh IN (
                                SELECT
                                    id_hemxxmh
                                FROM hemjbmh
                                WHERE id_hetxxmh NOT IN (99, 48)
                            )
                            AND is_active = 1
                            AND tanggal = :tanggal
                            ;
                    ');
                // ->query('delete', 'htssctd')
                // ->where('is_active', 1)
                // ->where('tanggal', $rs_htlgnth["tanggal"])
            // ->exec();
            
            $qu_htssctd = $db
                ->query('update', 'htssctd')
                ->set('is_active', 1)
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