<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$id_htsprrd   = $_POST['id_htsprrd'];

    try {
        $db->transaction();

        $sql_update = $editor->db()
        ->query('update', 'htsprrd')
        ->set('cek', 0)
        ->set('status_presensi_in', "AL")
        ->set('status_presensi_out', "AL")
        ->where('id', $id_htsprrd)
        ->exec();
        
        
        $qs_tgl = $db
            ->query('select', 'htsprrd' )
            ->get(['tanggal'] )
            ->where('id', $id_htsprrd )
            ->exec();
        $rs_tgl = $qs_tgl->fetch();

        $tanggal = $rs_tgl['tanggal'];

        $db->commit();
        echo json_encode(array('message' => 'Transaksi Berhasil Diproses', 'type_message' => 'success'));
    } catch (PDOException $e) {
        // rollback on error
        $db->rollback();
        echo json_encode(array('message' => 'Transaksi Gagal Diproses! ' . $e->getMessage(), 'type_message' => 'danger'));
    }
    
	
?>