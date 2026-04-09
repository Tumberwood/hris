<?php
    /**
     * tes case SLO/2305/0082
     */
    require_once( "../../../../users/init.php" );
    require_once( "../../../../usersc/lib/DataTables.php" );
    require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $id_transaksi_h = $_POST['id_transaksi_h'];
    $state = $_POST['state'];

    try{
        $db->transaction();
        // BEGIN ambil data periode_payroll
        $qs_periode_payroll = $db
            ->raw()
            ->bind(':id_transaksi_h', $id_transaksi_h)
            ->exec('SELECT
                        a.tanggal_awal,
                        a.tanggal_akhir
                    FROM periode_payroll a
                    WHERE a.id = :id_transaksi_h
                    '
                    );
        $rs_periode_payroll = $qs_periode_payroll->fetch();
        $tanggal_awal = $rs_periode_payroll['tanggal_awal'];
        $tanggal_akhir = $rs_periode_payroll['tanggal_akhir'];

        // END ambil data periode_payroll
        if($state == 1){
            
            $qu_htsprrd = $db
                ->raw()
                ->bind(':tanggal_awal', $tanggal_awal)
                ->bind(':tanggal_akhir', $tanggal_akhir)
                ->exec('UPDATE htsprrd a
                        SET
                            a.is_approve = 1
                        WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            ');

        }elseif($state == 2){
            $qu_htsprrd = $db
                ->raw()
                ->bind(':tanggal_awal', $tanggal_awal)
                ->bind(':tanggal_akhir', $tanggal_akhir)
                ->exec('UPDATE htsprrd a
                        SET
                            a.is_approve = 0
                        WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            ');
            
        }

        $db->commit();
        // $data = array(
        //     'message'=> 'Data Berhasil Di Insert' , 
        //     'type_message'=>'success' 
        // );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        // $data = array(
        //     'message'=>'Data Gagal Di Insert', 
        //     'type_message'=>'danger' 
        // );
    }

    // tampilkan results
    // require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
    
?>