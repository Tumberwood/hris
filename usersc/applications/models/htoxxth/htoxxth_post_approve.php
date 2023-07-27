<?php
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

        if($state == 1){
            $qs_htoemtd = $db
                ->query('select', 'htoemtd' )
                ->get([
                    'htoemtd.id_hemxxmh as id_hemxxmh',
                    'htoxxth.id as id_htoxxth',
                    'htoemtd.id as id_htoemtd',
                    'htoemtd.id_htotpmh as id_htotpmh',
                    'htoxxth.id_heyxxmh as id_heyxxmh',
                    'htoxxth.kode as kode',
                    'htoxxth.tanggal as tanggal',
                    'htoxxth.is_approve as is_approve',
                    'htoemtd.is_istirahat as is_istirahat',
                    'htoemtd.jam_awal as jam_awal',
                    'htoemtd.jam_akhir as jam_akhir',
                    'htoemtd.durasi_lembur_jam as durasi_jam'
                ] )
                ->join('htoxxth','htoxxth.id = htoemtd.id_htoxxth','LEFT' )
                ->where('htoemtd.is_active', 1 )
                ->where('htoxxth.is_active', 1 )
                ->where('htoxxth.id', $_POST['id_transaksi_h'] )
                ->exec();
            $rs_htoemtd = $qs_htoemtd->fetchAll();

            foreach ($rs_htoemtd as $row_htoemtd) {
                $qi_htoxxrd = $db
                    ->query('insert', 'htoxxrd')
                    ->set( 'id_hemxxmh', $row_htoemtd['id_hemxxmh'] )
                    ->set( 'id_htoxxth', $row_htoemtd['id_htoxxth'] )
                    ->set( 'id_htoemtd', $row_htoemtd['id_htoemtd'] )
                    ->set( 'id_htotpmh', $row_htoemtd['id_htotpmh'] )
                    ->set( 'id_heyxxmh', $row_htoemtd['id_heyxxmh'] )
                    ->set( 'kode', $row_htoemtd['kode'] ) 
                    ->set( 'is_approve', $row_htoemtd['is_approve'] ) 
                    ->set( 'tanggal', $row_htoemtd['tanggal'] )
                    ->set( 'jam_awal', $row_htoemtd['jam_awal'] )
                    ->set( 'jam_akhir', $row_htoemtd['jam_akhir'] )
                    ->set( 'durasi_jam', $row_htoemtd['durasi_jam'] )
                    ->set( 'is_istirahat', $row_htoemtd['is_istirahat'] )
                    ->exec();
            }
        
        }elseif($state == 2){

            $qd_htoxxrd = $db
                ->query('delete', 'htoxxrd')
                ->where('id_htoxxth', $_POST['id_transaksi_h'] )
                ->exec();

        }

        $db->commit();
        $data = array(
            'message'=> 'Data Berhasil Di Insert' , 
            'type_message'=>'success' 
        );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Data Gagal Di Insert', 
            'type_message'=>'danger' 
        );
    }

    // tampilkan results
    // require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
    
?>