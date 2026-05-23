<?php 
    /**
     * Digunakan untuk INSERT data hari libur nasional hthhdth ke table htlxxrh
     * Notes:
     *  Masih kurang where karyawan yang aktif berdasarkan tanggal resign
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $tanggal_select = new Carbon($_POST['tanggal']); //gunakan carbon untuk ambil data tanggal
    $tanggal = $tanggal_select->format('Y-m-d'); //format jadi 2023-09-12

    $id_hemxxmh          = $_POST['id_hemxxmh'];

    try{
        $db->transaction();

        $qd_htl = $db
            ->query('delete', 'htlxxrh')
            ->where('tanggal', $tanggal)
            ->where('id_hemxxmh', $id_hemxxmh)
            ->where('id_htlxxmh', 2)
            ->where('jenis', 1)
        ->exec();
        
        $db->commit();
        $data = array(
            'message'=> 'Data Berhasil Diperbarui' , 
            'type_message'=>'success' )
        ;

        }catch(PDOException $e){
            // rollback on error
            $db->rollback();
            $data = array(
                'message'=>'Data Gagal Diperbarui', 
                'type_message'=>'danger' 
            );
        }
    
        // tampilkan results
        require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

    ?>