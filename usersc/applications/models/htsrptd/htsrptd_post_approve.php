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
        // harusnya bisa pakai where or, tapi belum berhasil
        $qu_htssctd_pengaju = $db
            ->query('update', 'htssctd')
            ->set('is_active',0)
            ->set('keterangan', $keterangan)
            ->where('tanggal', $rs_htsrptd['tanggal'])
            ->where('id_hemxxmh', $rs_htsrptd['id_hemxxmh_pengaju'])
            ->exec();
        // END non aktif pengaju

        // BEGIN insert pengganti tambahan
        $qr_htssctd = $db
            ->raw()
            ->bind(':id_htsxxmh_pengganti', $rs_htsrptd['id_htsxxmh_pengganti'])
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
                    menit_toleransi_akhir_out
                )
                SELECT
                    '.$rs_htsrptd["id_hemxxmh_pengganti"].',
                    '.$rs_htsrptd["id_htsxxmh_pengganti"].',
                    "'.$keterangan.'",
                    "'.$rs_htsrptd["tanggal"].'",
                    htsxxmh.jam_awal,
                    htsxxmh.jam_akhir,
                    htsxxmh.jam_awal_istirahat,
                    htsxxmh.jam_akhir_istirahat,
                    htsxxmh.menit_toleransi_awal_in,
                    htsxxmh.menit_toleransi_akhir_in,
                    htsxxmh.menit_toleransi_awal_out,
                    htsxxmh.menit_toleransi_akhir_out
                FROM htsxxmh
                WHERE 
                    id = :id_htsxxmh_pengaju
            ');
        // END insert pengganti tambahan
    
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