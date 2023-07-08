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
                    20,
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