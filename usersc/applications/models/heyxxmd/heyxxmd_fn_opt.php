<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : heyxxmd
     * Parameter        : 
     *  - id_heyxxmd_old       : data existing (untuk keperluan edit), dipakai di query self
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = false;
    // END definisi variable untuk fn_ajax_results.php

    // BEGIN select2 pagination preparation
    $page        = $_GET['page'];
    $resultCount = 10;
    $offset      = ($page - 1) * $resultCount;
    // END select2 pagination preparation

    if($_GET['id_heyxxmd_old'] > 0){
        $id_heyxxmd_old = $_GET['id_heyxxmd_old'];
    }else{
        $id_heyxxmd_old = 0;
    }

    //jika dibutuhkan validasi sesuai sesi
    if (isset($_GET['is_validate_session'])) {
        $user = $_SESSION['user'];
        if ($user > 100) {
            $w_id_heyxxmd_session = '(' . $_SESSION['str_arr_ha_heyxxmd'] . ')';
            $s_id_heyxxmd_session = 'IN';
        } else {
            $w_id_heyxxmd_session = '(-1)';
            $s_id_heyxxmd_session = 'NOT IN';
        }
    } else {
        $w_id_heyxxmd_session = '(-1)';
        $s_id_heyxxmd_session = 'NOT IN';
    }
    
    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_heyxxmd_old > 0){
        $qs_heyxxmd_self = $db
            ->query('select', 'heyxxmd')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_heyxxmd_old )
            ->exec();
        $rs_heyxxmd_self = $qs_heyxxmd_self->fetchAll();
    }else{
        $rs_heyxxmd_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_heyxxmd_all = $db
        ->query('select', 'heyxxmd')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $w_id_heyxxmd_session, $s_id_heyxxmd_session, false)
        ->where('id', $id_heyxxmd_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_heyxxmd_all = $qs_heyxxmd_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_heyxxmd_self) > 0){
        $rs_opt = array_merge($rs_heyxxmd_self, $rs_heyxxmd_all);
    }else{
        $rs_opt = $rs_heyxxmd_all;
    }
    $c_rs_opt = count($rs_opt);
    // END menggabungkan options

    // BEGIN untuk keperluan paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END untuk keperluan paginasi select2
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>