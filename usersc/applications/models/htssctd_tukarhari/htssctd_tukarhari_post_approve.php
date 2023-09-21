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
        
        //add by ferry, tambahkan state, karena sebelum ditambahkan if state == 1 meskipun cancel approve tapi tetap menginsert ke database
        if($state == 1) {

            // BEGIN non aktif
            $qd_terpilih = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 0)
                ->where('tanggal', $tanggal_terpilih)
            ->exec();
            
            $qd_pengganti = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 0)
                ->where('tanggal', $tanggal_pengganti)
            ->exec();

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

                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(:tanggal_terpilih, " ", TIME(DATE_SUB(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", htssctd.jam_akhir)
                            ELSE CONCAT(:tanggal_terpilih, " ", htssctd.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY)
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
                    LEFT JOIN htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                    WHERE 
                        tanggal = :tanggal_pengganti
                        AND htssctd.is_active = 1
                ');
            // END insert pengaju

            //TANGGAL PENGGANTI Jadi tanggal pengaju
            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('is_active', 0)
                ->where('tanggal', $tanggal_pengganti)
                // ->where('id_hemxxmh', $w_id_hemxxmh, $s_id_hemxxmh, false)
                ->exec();
                
            // Begin insert pengaju
            $qr_tanggal_pengganti = $db
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
                        :tanggal_pengganti,
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
                        CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal_pengganti, " ", htssctd.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htssctd.jam_awal, INTERVAL htssctd.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
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

                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htssctd.jam_akhir)
                            ELSE CONCAT(:tanggal_pengganti, " ", htssctd.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htssctd.jam_akhir, INTERVAL htssctd.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR htsxxmh.kode like "malam%" AND htssctd.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
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
                        CONCAT(:tanggal_pengganti, " ", htssctd.jam_awal_istirahat) AS tanggaljam_awal_istirahat,
                        CONCAT(:tanggal_pengganti, " ", htssctd.jam_akhir_istirahat) AS tanggaljam_akhir_istirahat
                    FROM htssctd
                    LEFT JOIN htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                    WHERE 
                        tanggal = :tanggal_terpilih
                    AND htssctd.is_active = 0
                ');
            // END insert pengaju

            
        } else if($state == 2) {

            // BEGIN non aktif
            $qd_terpilih = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 1)
                ->where('tanggal', $tanggal_terpilih)
            ->exec();

            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('is_active', 1)
                ->where('is_active',0)
                ->where('tanggal', $tanggal_terpilih)
                ->exec();

            //TANGGAL PENGGANTI Jadi tanggal pengaju
            
            $qd_pengganti = $db
                ->query('delete', 'htssctd')
                ->where('is_active', 1)
                ->where('tanggal', $tanggal_pengganti)
            ->exec();
            
            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('is_active', 1)
                ->where('is_active',0)
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