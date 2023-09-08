<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$start_date   = $_POST['start_date'];
	$approve_presensi   = $_POST['approve_presensi'];

    try {
        $db->transaction();

        if ($approve_presensi == 1) {
            $sql_update = $editor->db()
            ->query('update', 'htsprrd')
            ->set('is_approve', 1)
            ->set('approved_by', $_SESSION['user'])
            ->set('approved_on', date("Y-m-d H:i:s"))
            ->where('tanggal', $start_date)
            ->exec();

        $editor->db()
            ->query('insert', 'activity_log_ml')
            ->set('kode', 'APPROVE')
            ->set('nama', 'htsprrd')
            ->set('keterangan', 'Approve RAB')
            ->set('created_by', $_SESSION['user'])
            ->set('username', $_SESSION['username'])
            ->set('start_on', date("Y-m-d H:i:s"))
            ->set('finish_on', date("Y-m-d H:i:s"))
            ->set('durasi_detik', 0)
            ->exec();

        } else {
            $sql_update = $editor->db()
                ->query('update', 'htsprrd')
                ->set('is_approve', 0)
                ->set('approved_by', $_SESSION['user'])
                ->set('approved_on', date("Y-m-d H:i:s"))
                ->where('tanggal', $start_date)
                ->exec();

            $editor->db()
                ->query('insert', 'activity_log_ml')
                ->set('kode', 'APPROVE')
                ->set('nama', 'htsprrd')
                ->set('keterangan', 'Cancel Approve RAB')
                ->set('created_by', $_SESSION['user'])
                ->set('username', $_SESSION['username'])
                ->set('start_on', date("Y-m-d H:i:s"))
                ->set('finish_on', date("Y-m-d H:i:s"))
                ->set('durasi_detik', 0)
                ->exec();

        }
        $db->commit();
        echo json_encode(array('message' => 'Transaksi Berhasil Diproses', 'type_message' => 'success'));
    } catch (PDOException $e) {
        // rollback on error
        $db->rollback();
        echo json_encode(array('message' => 'Transaksi Gagal Diproses! ' . $e->getMessage(), 'type_message' => 'danger'));
    }
    
	
?>