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
	
	$id_users     = $_POST['id_users'];

    try{
        $db->transaction();

        $qs_c_notifications_unread = $db
            ->raw()
            ->bind(':id_users', $id_users)
            ->bind(':is_archived', 0)
            ->bind(':is_read', 0)
            ->exec('
                SELECT
                    count(id) as c_unread
                FROM notifications
                WHERE 
                    user_id = :id_users AND
                    is_archived = :is_archived AND
                    is_read = :is_read
            ');
        $rs_c_notifications_unread = $qs_c_notifications_unread->fetch();
        $c_rs_notifications_unread = $rs_c_notifications_unread['c_unread'];

        $qs_notifications = $db
            ->raw()
            ->bind(':id_users', $id_users)
            ->bind(':is_archived', 0)
            ->bind(':is_read', 0)
            ->exec('
                SELECT
                    id as id_notifications,
                    message,
                    date_created,
                    is_read
                FROM notifications
                WHERE 
                    user_id = :id_users AND
                    is_archived = :is_archived AND
                    is_read = :is_read
                ORDER BY 
                    date_created
            ');
        $rs_notifications = $qs_notifications->fetchAll();


    }catch(PDOException $e){
		// rollback on error
		$db->rollback();

	}

    $data = array(
		'rs_notifications' => $rs_notifications,
        'c_rs_notifications_unread' => $c_rs_notifications_unread
	);

    // tampilkan results
    require_once( "fn_ajax_results.php" );
?>