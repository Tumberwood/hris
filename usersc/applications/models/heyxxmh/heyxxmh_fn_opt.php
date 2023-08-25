<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : heyxxmh
     * Parameter        : 
     *  - id_heyxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_heyxxmh_old'] > 0){
        $id_heyxxmh_old = $_GET['id_heyxxmh_old'];
    }else{
        $id_heyxxmh_old = 0;
    }

    //jika dibutuhkan validasi sesuai sesi
    if (isset($_GET['is_validate_session'])) {
        $user = $_SESSION['user'];
        if ($user > 100) {
            $w_id_heyxxmh_session = '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')';
            $s_id_heyxxmh_session = 'IN';
        } else {
            $w_id_heyxxmh_session = '(-1)';
            $s_id_heyxxmh_session = 'NOT IN';
        }
    } else {
        $w_id_heyxxmh_session = '(-1)';
        $s_id_heyxxmh_session = 'NOT IN';
    }
    
    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_heyxxmh_old > 0){
        $qs_heyxxmh_self = $db
            ->query('select', 'heyxxmh')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_heyxxmh_old )
            ->exec();
        $rs_heyxxmh_self = $qs_heyxxmh_self->fetchAll();
    }else{
        $rs_heyxxmh_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_heyxxmh_all = $db
        ->query('select', 'heyxxmh')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $w_id_heyxxmh_session, $s_id_heyxxmh_session, false)
        ->where('id', $id_heyxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_heyxxmh_all = $qs_heyxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_heyxxmh_self) > 0){
        $rs_opt = array_merge($rs_heyxxmh_self, $rs_heyxxmh_all);
    }else{
        $rs_opt = $rs_heyxxmh_all;
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