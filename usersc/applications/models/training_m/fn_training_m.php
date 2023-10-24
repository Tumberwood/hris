<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php
    $id =  $_POST['id'];
    $tabel =  $_POST['tabel'];
    $is_delete =  $_POST['is_delete'];

    $q_val_edit = $db
        ->raw()
        ->bind(':id', $id)
        ->exec('SELECT
                    *
                FROM '.$tabel.'
                WHERE id = :id
                '
                );
    $r_val_edit = $q_val_edit->fetch();

    if ($is_delete == 1) {
        $q_delete = $db
            ->raw()
            ->bind(':id', $id)
            ->exec('DELETE FROM '.$tabel.'
                    WHERE id = :id
                    '
                    );
    }
    $data = array(
        'r_val_edit' => $r_val_edit
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>