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

    try{
        $db->transaction();

        $qs_notification_interval_setting = $db
            ->raw()    
            ->exec('
                SELECT
                    is_notification,
                    notification_interval_ms
                FROM ggsxxsh
            ');
        $rs_notification_interval_setting = $qs_notification_interval_setting->fetch();

    }catch(PDOException $e){
		// rollback on error
		$db->rollback();

	}

    $data = array(
		'rs_notification_interval_setting' => $rs_notification_interval_setting
	);

    // tampilkan results
    require_once( "fn_ajax_results.php" );
?>