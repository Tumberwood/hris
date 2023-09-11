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
    $id_transaksi_h = $_POST['id_transaksi_h'];

    try{
        $db->transaction();
        
        //add by ferry, tambahkan state, karena sebelum ditambahkan if state == 1 meskipun cancel approve tapi tetap menginsert ke database
        if($state == 1) {
            $qs_htssctd_tukarhari = $db
                ->query('select', 'htssctd_tukarhari' )
                ->get([
                    'tanggal_terpilih',
                    'keterangan',
                    'tanggal_pengganti'
                ] )
                ->where('id', $id_transaksi_h )
                ->exec();

            $rs_htssctd_tukarhari = $qs_htssctd_tukarhari->fetch();
            $keterangan = $rs_htssctd_tukarhari['keterangan'];
            $tanggal_terpilih = $rs_htssctd_tukarhari['tanggal_terpilih'];
            $tanggal_pengganti = $rs_htssctd_tukarhari['tanggal_pengganti'];

            // $qs_htssctd_tukarhari_pegawai = $db
            //     ->query('select', 'htssctd_tukarhari_pegawai' )
            //     ->get(['id_hemxxmh'] )
            //     ->where('id_htssctd_tukarhari', $id_transaksi_h )
            //     ->exec();
            // $rs_htssctd_tukarhari_pegawai = $qs_htssctd_tukarhari_pegawai->fetchAll();

            // $pegawai_pengganti = array();
            // foreach ($rs_htssctd_tukarhari_pegawai as $key => $peg) {
            //     $pegawai_pengganti[] = $peg['id_hemxxmh'];
            // }
            // $w_id_hemxxmh = '(' . implode(',', $pegawai_pengganti) . ')';
            // $s_id_hemxxmh = 'IN';


            // BEGIN non aktif

            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('is_active',0)
                ->set('keterangan', $keterangan)
                ->where('tanggal', $tanggal_terpilih)
                ->exec();
            // END non aktif
                
            // Begin insert pengaju
            $qr_tanggal_terpilih = $db
                ->raw()
                ->bind(':tanggal_terpilih', $tanggal_terpilih)
                ->bind(':tanggal_pengganti', $tanggal_pengganti)
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
                        "'.$keterangan.'",
                        :tanggal_terpilih,
                        htssctd.id_hemxxmh,
                        htssctd.id_htsxxmh,
                        htssctd.jam_awal,
                        htssctd.jam_akhir,
                        htssctd.jam_awal_istirahat,
                        htssctd.jam_akhir_istirahat,
                        htssctd.menit_toleransi_awal_in,
                        htssctd.menit_toleransi_akhir_in,
                        htssctd.menit_toleransi_awal_out,
                        htssctd.menit_toleransi_akhir_out,
                        CONCAT(:tanggal_terpilih, " ", TIME(DATE_SUB(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal_terpilih, " ", htssctd.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY)
                                ELSE :tanggal_terpilih
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        CONCAT(:tanggal_terpilih, " ", TIME(DATE_SUB(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_awal_out MINUTE))) AS tanggaljam_akhir_t1,
                        CONCAT(:tanggal_terpilih, " ", htssctd.jam_akhir) AS tanggaljam_akhir,
                        
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY)
                                ELSE :tanggal_terpilih
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CONCAT(:tanggal_terpilih, " ", htssctd.jam_awal_istirahat) AS tanggaljam_awal_istirahat,
                        CONCAT(:tanggal_terpilih, " ", htssctd.jam_akhir_istirahat) AS tanggaljam_akhir_istirahat
                    FROM htssctd
                    WHERE 
                        tanggal = :tanggal_pengganti
                ');
            // END insert pengaju

            // Select Master shift OFF
            $qs_shift_off = $db
                ->query('select', 'htsxxmh' )
                ->get([ 'jam_awal',
                        'jam_akhir',
                        'jam_awal_istirahat',
                        'jam_akhir_istirahat',
                        'menit_toleransi_awal_in',
                        'menit_toleransi_akhir_in',
                        'menit_toleransi_awal_out',
                        'menit_toleransi_akhir_out'
                        ] )
                ->where('id', 1 )
                ->exec();
            $rs_shift_off = $qs_shift_off->fetch();

            $tanggal_jam_off = $tanggal_pengganti . " 00:00:00";

            //TANGGAL PENGGANTI Jadi OFF
            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('id_htsxxmh', 1)
                ->set($rs_shift_off)
                ->set('tanggaljam_awal_t1', $tanggal_jam_off)
                ->set('tanggaljam_awal', $tanggal_jam_off)
                ->set('tanggaljam_awal_t2', $tanggal_jam_off)
                ->set('tanggaljam_akhir_t1', $tanggal_jam_off)
                ->set('tanggaljam_akhir', $tanggal_jam_off)
                ->set('tanggaljam_akhir_t2', $tanggal_jam_off)
                ->set('tanggaljam_awal_istirahat', $tanggal_jam_off)
                ->set('tanggaljam_akhir_istirahat', $tanggal_jam_off)
                ->set('keterangan', $keterangan)
                ->where('tanggal', $tanggal_pengganti)
                // ->where('id_hemxxmh', $w_id_hemxxmh, $s_id_hemxxmh, false)
                ->exec();
        }
        
    
        $db->commit();
        $data = array(
            'message'=> 'Perubahan Jadwal Berhasil Dibuat' , 
            'type_message'=>'success' 
        );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Perubahan Jadwal Gagal Dibuat!', 
            'qr_htssctd_pengaju'=>'danger' 
        );
    }    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>