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
                    1,
                    20,
                    hemxxmh.id,
                    "'.$rs_hthhdth["kode"].'",
                    "'.$rs_hthhdth["tanggal"].'",
                    "'.$rs_hthhdth["nama"].'",
                    1,
                    "HL",
                    "HL",
                    1,
                    null,
                    null
                FROM
                    hemxxmh
                WHERE 
                    hemxxmh.is_active = :is_active;
            ');
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