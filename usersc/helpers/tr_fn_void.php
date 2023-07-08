<?php
	/*
        220906 - 2.0.1
            Fix void_by & void_on, sebelumya masih approved_by & approved_on

        220827 - 2.0.0
            Mengubah query dengan menggunakan class bawaan dari datatables
		
		Kegunaan:
            - Mengubah is_approve transaksi menjadi -9 (menandakan data sudah di void)
            - Insert log ke table activity_log_ml

        Parameter:
            - $table_name
		    - $id_transaksi
	*/
	
	include_once( "../../users/init.php" );
	include_once( "../../usersc/lib/DataTables.php" );
    use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$table_name   = $_POST['table_name'];
	$id_transaksi     = $_POST['id_transaksi'];
	$notes        = 'Void ' . $table_name . ' , id = '. $id_transaksi;

    try{
        $db->transaction();

        $sql_update = $editor->db()
            ->query('update', $table_name)
            ->set('is_approve', -9)
            ->set('void_by', $_SESSION['user'])
            ->set('void_on', date("Y-m-d H:i:s") )
            ->where('id', $id_transaksi )
            ->exec();

        $editor->db()
            ->query('insert', 'activity_log_ml')
            ->set('id_transaksi',$id_transaksi)
            ->set('kode','VOID')
            ->set('nama', $table_name )
            ->set('keterangan', $notes )
            ->set('created_by',$_SESSION['user'])
            ->set('username',$_SESSION['username'])
            ->set('start_on', date("Y-m-d H:i:s"))
            ->set('finish_on', date("Y-m-d H:i:s"))
            ->set('durasi_detik', 0)
            ->exec();

        $db->commit();
        echo json_encode(array('message'=>'Transaksi Berhasil Dibatalkan', 'type_message'=>'success' ));
    }catch(PDOException $e){
		// rollback on error
		$db->rollback();
		echo json_encode(array('message'=>'Transaksi Gagal Dibatalkan', 'type_message'=>'danger' ));
	}
	
?>