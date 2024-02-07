<?php 
    /**
     * Digunakan untuk INSERT data hari libur nasional hthhdth ke table htlxxrh
     * Notes:
     *  Masih kurang where karyawan yang aktif berdasarkan tanggal resign
     */
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

        $qs_hthhdth = $db
            ->query('select', 'hthhdth' )
            ->get([
                'hthhdth.tanggal as tanggal',
                'hthhdth.kode as kode',
                'hthhdth.nama as nama'
            ] )
            ->where('hthhdth.id', $_POST['id_transaksi_h'] )
            ->exec();
        $rs_hthhdth = $qs_hthhdth->fetch();
        
        if ($state == 1) {
            // BEGIN non aktif
            $qd_terpilih = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 0)
                ->where('tanggal', $rs_hthhdth["tanggal"])
            ->exec();
    
            $tanggal_jam_off = $rs_hthhdth["tanggal"] . " 00:00:00";
    
            //Dilakukan Non Aktif jadwal selain satpam
            $qu_htssctd = $db
            ->raw()
            ->bind(':keterangan', $rs_hthhdth["nama"])
            ->bind(':tanggal', $rs_hthhdth["tanggal"])
            ->exec('UPDATE htssctd AS a
                    LEFT JOIN hemjbmh AS jb on jb.id_hemxxmh = a.id_hemxxmh
                    SET
                        a.is_active = 0,
                        a.keterangan = CONCAT("Public Holiday - ", :keterangan)
                    WHERE 
                        tanggal = :tanggal AND jb.id_hetxxmh NOT IN (99, 48)
                        AND a.is_active = 1
                        ;
            ');

            // $qu_htssctd = $db
            //     ->query('update', 'htssctd')
            //     ->set('is_active',0)
            //     ->set('keterangan', "Public Holiday - " . $rs_hthhdth["nama"])
            //     ->where('tanggal', $rs_hthhdth["tanggal"])
            //     ->where('is_active', 1)
            // ->exec();

            // Begin insert pengaju
            $qr_tanggal = $db
                ->raw()
                ->bind(':tanggal', $rs_hthhdth["tanggal"])
                ->bind(':nama', $rs_hthhdth["nama"])
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
                        CONCAT("Public Holiday - ", :nama),
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
                    AND keterangan = CONCAT("Public Holiday - ", :nama) AND jb.id_hetxxmh NOT IN (99, 48)
                ');
            // END insert pengaju

        } else if($state == 2) {
            $qd_pengganti = $db
                ->raw()
                ->bind(':tanggal', $rs_hthhdth["tanggal"])
                ->bind(':nama', $rs_hthhdth["nama"])
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
                            AND keterangan = CONCAT("Public Holiday - ", :nama)
                            ;
                    ');

            // $qd_pengganti = $db
            //     ->query('delete', 'htssctd')
            //     ->where('is_active', 1)
            //     ->where('tanggal', $rs_hthhdth["tanggal"])
            // ->exec();
            
            $qu_htssctd = $db
                ->query('update', 'htssctd')
                ->set('is_active', 1)
                ->where('is_active',0)
                ->where('tanggal', $rs_hthhdth["tanggal"])
            ->exec();
        }
                //TIDAK PERLU


                //KALAU DI APPROVE TIDAK PERLU MASUK KE ABSENSI REPORT TAPI DIUBAH JADWAL YANG TERPILIH JADI OFF
            // $qr_htsprrd = $db
            //     ->raw()
            //     ->bind(':is_active', 1)
            //     ->exec('
            //         INSERT INTO htlxxrh
            //             (
            //                 id_transaksi,
            //                 id_htlgrmh,
            //                 id_htlxxmh,
            //                 id_hemxxmh,
            //                 kode,
            //                 tanggal,
            //                 keterangan,
            //                 jenis,
            //                 htlxxmh_kode,
            //                 htlgrmh_kode,
            //                 jumlah,
            //                 jam_awal,
            //                 jam_akhir
            //             )
            //         SELECT
            //             ' . $_POST['id_transaksi_h'] . ',
            //             1,
            //             20,
            //             hemxxmh.id,
            //             "'.$rs_hthhdth["kode"].'",
            //             "'.$rs_hthhdth["tanggal"].'",
            //             "'.$rs_hthhdth["nama"].'",
            //             1,
            //             "HL",
            //             "HL",
            //             1,
            //             null,
            //             null
            //         FROM
            //             hemxxmh
            //         WHERE 
            //             hemxxmh.is_active = :is_active;
            //     ');

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