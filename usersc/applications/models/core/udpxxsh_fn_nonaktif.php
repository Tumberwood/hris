<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

    use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$id_users   = $_POST['id_users'];
	$id_udpxxsh = $_POST['id_udpxxsh'];

    $editor = Editor::inst( $db, '' );

    try{
        $db->transaction();
	
        $sql_update = $editor->db()
            ->query('update', 'users_extend')
            ->set('is_hakakses', 0 )
            ->where('id',$id_users)
            ->exec();
        
        // Hapus semua detail terkait id_users yang dihapus
        // Jika ada detail hak akses lain, silakan ditambahkan sesuai yang sudah ada
        // udpbrsd
        $sql_delete_udpbrsd = $editor->db()
            ->query('delete', 'udpbrsd')
            ->where('id_udpxxsh',$id_udpxxsh)
            ->exec();
        
        // ucudasd
        $sql_delete_ucudasd = $editor->db()
            ->query('delete', 'ucudasd')
            ->where('id_udpxxsh',$id_udpxxsh)
            ->exec();
        
        $db->commit();
        echo json_encode(array('message'=>'Non Aktif User Berhasil', 'type_message'=>'success' ));

    }catch(PDOException $e){
		$db->rollback();
		echo json_encode(array('message'=>'Non Aktif User Gagal', 'type_message'=>'danger' ));
	}
	
		
	
		
	
	
	
?>