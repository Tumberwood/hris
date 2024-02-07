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

        $qd_pengganti = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->bind(':id_hemxxmh', $id_hemxxmh)
            ->exec(' DELETE FROM htssctd
                    WHERE 
                        id_hemxxmh IN (
                            SELECT
                                id_hemxxmh
                            FROM hemjbmh
                            WHERE id_hetxxmh NOT IN (99, 48)
                        )
                        AND is_active = 1
                        AND id_hemxxmh = :id_hemxxmh
                        AND tanggal = :tanggal
                        ;
                ');

        $qu_htssctd = $db
            ->query('update', 'htssctd')
            ->set('is_active', 1)
            ->set('keterangan', "Hapus Cuti & Public Holiday Dashboard Report Presensi")
            ->where('is_active',0)
            ->where('tanggal', $tanggal)
            ->where('id_hemxxmh', $id_hemxxmh)
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