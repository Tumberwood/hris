<?php
	/**
     * Digunakan untuk load notification list dari table notifications
     */
    
    require_once( "../../users/init.php" );
	require_once( "../../usersc/lib/DataTables.php" );
    include_once( "../../usersc/vendor/autoload.php");
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
	
	$id_notifications     = substr($_POST['id_notifications'],6);

    try{
        $db->transaction();

        $qu_notifications = $db
            ->raw()
            ->bind(':id_notifications', $id_notifications)
            ->exec('
                UPDATE notifications
                SET is_read = 1
                WHERE 
                    id = :id_notifications
            ');
        $db->commit();

        $data = array(
            'message' => 'Update Read berhasil'
        );

    }catch(PDOException $e){
		// rollback on error
		$db->rollback();

        $data = array(
            'message' => 'Update Read Gagal'
        );
	}

    

    // tampilkan results
    require_once( "fn_ajax_results.php" );
?>