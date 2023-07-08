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
        
        $qs_htscctd = $db
            ->query('select', 'htscctd' )
            ->get([
                'htscctd.id_hemxxmh_pengaju as id_hemxxmh_pengaju',
                'htscctd.id_hemxxmh_pengganti as id_hemxxmh_pengganti',
                'htscctd.id_htsxxmh_pengaju as id_htsxxmh_pengaju',
                'htscctd.id_htsxxmh_pengganti as id_htsxxmh_pengganti',
                'htscctd.kode as kode',
                'htscctd.tanggal as tanggal',
                'htscctd.keterangan as keterangan'
            ] )
            ->where('htscctd.id', $id_transaksi_h )
            ->exec();

        $rs_htscctd = $qs_htscctd->fetch();
        $keterangan = $rs_htscctd['keterangan'];
        // BEGIN non aktif
        // harusnya bisa pakai where or, tapi belum berhasil
        $qu_htssctd_pengaju = $db
            ->query('update', 'htssctd')
            ->set('is_active',0)
            ->set('keterangan', $keterangan)
            ->where('tanggal', $rs_htscctd['tanggal'])
            ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengaju'])
            ->exec();

        $qu_htssctd_pengganti = $db
            ->query('update', 'htssctd')
            ->set('is_active',0)
            ->set('keterangan', $keterangan)
            ->where('tanggal', $rs_htscctd['tanggal'])
            ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengganti'])
            ->exec();
        // END non aktif

        // BEGIN insert pengaju
        $qr_htssctd = $db
            ->raw()
            ->bind(':id_htsxxmh_pengaju', $rs_htscctd['id_htsxxmh_pengaju'])
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
                    '.$rs_htscctd["id_hemxxmh_pengaju"].',
                    '.$rs_htscctd["id_htsxxmh_pengaju"].',
                    "'.$keterangan.'",
                    "'.$rs_htscctd["tanggal"].'",
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
        // END insert pengaju

        // BEGIN insert pengganti
        $qr_htssctd = $db
            ->raw()
            ->bind(':id_htsxxmh_pengganti', $rs_htscctd['id_htsxxmh_pengganti'])
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
                    '.$rs_htscctd["id_hemxxmh_pengganti"].',
                    '.$rs_htscctd["id_htsxxmh_pengganti"].',
                    "'.$keterangan.'",
                    "'.$rs_htscctd["tanggal"].'",
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
                    id = :id_htsxxmh_pengganti
            ');
        // END insert pengganti
    
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