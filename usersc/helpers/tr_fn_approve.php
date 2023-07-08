<?php
	/*
        220827 - 2.0.0
            Mengubah query dengan menggunakan class bawaan dari datatables
		
		Kegunaan:
            - Mengubah is_approve transaksi menjadi 1 (menandakan data sudah di approve)
            - Insert log ke table activity_log_ml

        Parameter:
            - $table_name
		    - $id_transaksi
	*/
	
	include_once( "../../users/init.php" );
	include_once( "../../usersc/lib/DataTables.php" );
    require_once( "datatables_fn_debug.php" );

	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php
	
	$table_name     = $_POST['table_name'];
	$id_transaksi   = $_POST['id_transaksi'];

    if($_POST['state'] == 1){
        $status_approval = 'Approve ';
    }elseif($_POST['state'] == 2){
        $status_approval = 'Cancel Approve ';
    }elseif($_POST['state'] == -9){
        $status_approval = 'Void ';
    }else{
        $status_approval = 'Invalid ';
    }

    $notes          = $status_approval . $table_name . ' , id = '. $id_transaksi;

    try{
        $db->transaction();

        $qu_table = $db
            ->query('update', $table_name)
            ->set('is_approve', $_POST['state'])
            ->set('approved_by', $_SESSION['user'])
            ->set('approved_on', date("Y-m-d H:i:s") )
            ->where('id', $id_transaksi )
            ->exec();

        $qi_log = $db
            ->query('insert', 'activity_log_ml')
            ->set('id_transaksi',$id_transaksi)
            ->set('kode','APPROVE')
            ->set('nama', $table_name )
            ->set('keterangan', $notes )
            ->set('created_by',$_SESSION['user'])
            ->set('username',$_SESSION['username'])
            ->set('start_on', date("Y-m-d H:i:s"))
            ->set('finish_on', date("Y-m-d H:i:s"))
            ->set('durasi_detik', 0)
            ->exec();

        $db->commit();
        
        $message = 'Proses '.$status_approval.'Berhasil';
        $type_message = 'success';

    }catch(PDOException $e){
		// rollback on error
		$db->rollback();

        $message = 'Proses '.$status_approval.'Gagal';
        $type_message = 'danger';

	}

    $data = array(
		'message'      => $message,
		'type_message' => $type_message
	);

    // tampilkan results
    require_once( "fn_ajax_results.php" );
	
?>