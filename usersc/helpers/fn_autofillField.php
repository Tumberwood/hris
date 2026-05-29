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
	
    $tbl_name     = $_POST['tbl_name'];
    $id_transaksi = $_POST['id_transaksi'];
    $fields_name  = $_POST['fields_name'];

    try{

        $qs_autofill = $db
            ->raw()
            ->bind(':id_transaksi', $id_transaksi)
            ->exec('
                SELECT
                    '.$fields_name.'
                FROM '.$tbl_name.'
                WHERE id = :id_transaksi
            ');
        $rs_autofill = $qs_autofill->fetch();
        
        if($rs_autofill){
            $status_code  = 200;
            $message      = 'Autofill Data Berhasil';
            $type_message = 'success';
        }else{
            $status_code  = 500;
            $message      = 'Autofill Data Gagal 1';
            $type_message = 'error';
        }

    }catch(PDOException $e){
        // rollback on error
        $status_code  = 500;
        $message      = 'Autofill Data Gagal!' . $e->getMessage();
        $type_message = 'error';
    }

    $data = array(
        'status_code' => $status_code,
        'message' => $message,
        'type_message' => $type_message,
        'rs_autofill' => $rs_autofill
    );

    // tampilkan results
    require_once( "fn_ajax_results.php" );
?>