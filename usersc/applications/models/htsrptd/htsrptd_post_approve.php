<?php 
/**
 * Digunaakan untuk melakukan non aktif jadwal karyawan pengaju dan digantikan oleh karyawan pengganti
 * 
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
    $id_transaksi_h = $_POST['id_transaksi_h'];

    try{
        $db->transaction();

        if($state == 1) {
            $qs_htsrptd = $db
                ->query('select', 'htsrptd' )
                ->get([
                    'htsrptd.id_hemxxmh_pengaju as id_hemxxmh_pengaju',
                    'htsrptd.id_hemxxmh_pengganti as id_hemxxmh_pengganti',
                    'htsrptd.id_htsxxmh_pengaju as id_htsxxmh_pengaju',
                    'htsrptd.id_htsxxmh_pengganti as id_htsxxmh_pengganti',
                    'htsrptd.kode as kode',
                    'htsrptd.tanggal as tanggal',
                    'htsrptd.keterangan as keterangan'
                ] )
                ->where('htsrptd.id', $id_transaksi_h )
                ->exec();

            $rs_htsrptd = $qs_htsrptd->fetch();
            $keterangan = $rs_htsrptd['keterangan'];

            // BEGIN non aktif pengaju
            // harusnya bisa pakai where or, tapi belum berhasil diganti off
            $qu_htssctd_pengaju = $db
                ->query('update', 'htssctd')
                ->set('id_htsxxmh', 1)
                ->set('keterangan', $keterangan)
                ->where('tanggal', $rs_htsrptd['tanggal'])
                ->where('id_hemxxmh', $rs_htsrptd['id_hemxxmh_pengaju'])
                ->exec();
            // END non aktif pengaju

            // BEGIN insert pengganti tambahan
            $qr_htssctd = $db
                ->raw()
                ->bind(':id_htsxxmh_pengaju', $rs_htsrptd['id_htsxxmh_pengaju'])
                ->bind(':tanggal_pengganti', $rs_htsrptd["tanggal"])
                ->exec('
                    INSERT INTO htssctd
                    (
                        id_hemxxmh,
                        id_htsxxmh,
                        keterangan,
                        tanggal,
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
                        '.$rs_htsrptd["id_hemxxmh_pengganti"].',
                        '.$rs_htsrptd["id_htsxxmh_pengaju"].',
                        "'.$keterangan.'",
                        "'.$rs_htsrptd["tanggal"].'",
                        htsxxmh.jam_awal,
                        htsxxmh.jam_akhir,
                        htsxxmh.jam_awal_istirahat,
                        htsxxmh.jam_akhir_istirahat,
                        htsxxmh.menit_toleransi_awal_in,
                        htsxxmh.menit_toleransi_akhir_in,
                        htsxxmh.menit_toleransi_awal_out,
                        htsxxmh.menit_toleransi_akhir_out,
                        CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE))) AS tanggaljam_akhir_t1,
                        CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir) AS tanggaljam_akhir,
                        
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal_istirahat) AS tanggaljam_awal_istirahat,
                        CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir_istirahat) AS tanggaljam_akhir_istirahat
                    FROM htsxxmh
                    WHERE 
                        htsxxmh.id = :id_htsxxmh_pengaju
                ');
            // END insert pengganti tambahan
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
            'type_message'=>'danger' 
        );
    }    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>