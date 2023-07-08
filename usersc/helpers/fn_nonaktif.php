<?php
	/*
        220827 - 2.0.0
            Mengubah query dengan menggunakan class bawaan dari datatables.

		Kegunaan:
            - Mengubah is_active transaksi menjadi 0 (menandakan data tidak aktif)
            - Insert log ke table activity_log_ml
		
        Parameter:
            - $table_name
		    - $id_transaksi
	*/
    include_once( "../../users/init.php" );
	include_once( "../../usersc/lib/DataTables.php" );
    include( "datatables_fn_debug.php" );   
    use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
    
    $table_name   = $_POST['table_name'];
    $id_transaksi = $_POST['id_transaksi'];
    $state_active = $_POST['state_active'];

    if($state_active == 1){
        $is_active = 0;
        $notes     = 'Inactive ' . $table_name . ' , id = '. $id_transaksi;
        $kode      = 'INACTIVE';
        $message_success   = 'Data Berhasil Dihapus';
        $message_error   = 'Data Gagal Dihapus';
    }else{
        $is_active = 1;
        $notes     = 'Reactived ' . $table_name . ' , id = '. $id_transaksi;
        $kode      = 'REACTIVED';
        $message_success   = 'Data Berhasil Diaktifkan Kembali';
        $message_error   = 'Data Gagal Diaktifkan Kembali';
    }
    

    try{
        $db->transaction();

        $sql_update = $editor->db()
            ->query('update', $table_name)
            ->set('is_active', $is_active)
            ->where('id', $id_transaksi )
            ->exec();

        $editor->db()
            ->query('insert', 'activity_log_ml')
            ->set('id_transaksi',$id_transaksi)
            ->set('kode', $kode)
            ->set('nama', $table_name )
            ->set('keterangan', $notes )
            ->set('created_by',$_SESSION['user'])
            ->set('username',$_SESSION['username'])
            ->set('start_on', date("Y-m-d H:i:s"))
            ->set('finish_on', date("Y-m-d H:i:s"))
            ->set('durasi_detik', 0)
            ->exec();

        // commit transaction
        $db->commit();
        echo json_encode(array('message'=> $message_success  , 'type_message'=>'success' ));
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        echo json_encode(array('message'=> $message_error, 'type_message'=>'danger' ));
    }

    // print_r($debug);
?>