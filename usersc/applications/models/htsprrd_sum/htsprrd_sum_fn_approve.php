<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    if($_POST['state'] == 1){
        $message        = 'Finalisasi Data Berhasil';
        $type_message   = 'success';
    }elseif($_POST['state'] == 2){
        $message        = 'Finalisasi Data Gagal';
        $type_message   = 'success';
    }

    try{
        $db->transaction();
    
        $qu_htsprrd = $db
            ->query('update', 'htsprrd')
            ->set('is_approve', $_POST['state'])
            ->set('approved_by', $_SESSION['user'])
            ->where('tanggal', $_POST['start_date'], '>=')
            ->where('tanggal', $_POST['end_date'], '<=')
            ->exec();
        $db->commit();

    }catch(PDOException $e){
        $db->rollback();
        $message        = $e;
        $type_message   = 'danger';
    }

    $data = array(
        'message'=> $message,
        'type_message'=> $type_message
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>