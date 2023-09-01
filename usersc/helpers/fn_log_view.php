<?php
	/*
        230901: Initial

		Kegunaan:
            - Menampilkan Log per baris data
        Parameter:
            - $table_name
		    - $id_transaksi
	*/
    
    require_once( "../../users/init.php" );
	require_once( "../../usersc/lib/DataTables.php" );
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

    try{
        $db->transaction();

        $qs_activity_log_ml = $db
            ->query('select', 'activity_log_ml' )
            ->get([
                'activity_log_ml.kode as kode',
                'users.username as username',
                'DATE_FORMAT(activity_log_ml.created_on,"%d %b %y %T") as created_on'
            ] )
            ->join('users','users.id = activity_log_ml.created_by','LEFT' )
            ->where('activity_log_ml.nama', $table_name )
            ->where('activity_log_ml.id_transaksi', $id_transaksi )
            ->order('created_on')
            ->exec();
        $rs_activity_log_ml = $qs_activity_log_ml->fetchAll();

        $db->commit();

    }catch(PDOException $e){
		// rollback on error
		$db->rollback();

	}

    $data = array(
		'rs_activity_log_ml' => $rs_activity_log_ml
	);

    // tampilkan results
    require_once( "fn_ajax_results.php" );
?>