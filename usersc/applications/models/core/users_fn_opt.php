<?php 
    /**
     * Digunakan untuk populate options data users
     * Table terkait    : users
     * Parameter        : 
     *  - id_users_old       : data existing (untuk keperluan edit), dipakai di query self 
     */    
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    // BEGIN select2 pagination preparation
    $page        = $_GET['page'];
    $resultCount = 10;
    $offset      = ($page - 1) * $resultCount;
    // END select2 pagination preparation

    if($_GET['id_users_old'] > 0){
        $id_users_old = $_GET['id_users_old'];
    }else{
        $id_users_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_users_old > 0){
        $qs_users_self = $db
            ->query('select', 'users')
            ->get([
                'id as id',
                'username as text'
            ])
            ->where('id', $id_users_old )
            ->exec();
        $rs_users_self = $qs_users_self->fetchAll();
    }else{
        $rs_users_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_users_all = $db
        ->query('select', 'users')
        ->get([
            'id as id',
            'username as text'
        ])
        ->where('active',1)
        ->where('id', $id_users_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r->where('username', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_users_all  = $qs_users_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_users_self) > 0){
        $rs_opt = array_merge($rs_users_self, $rs_users_all);
    }else{
        $rs_opt = $rs_users_all;
    }
    $c_rs_opt = count($rs_opt);
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END finalisasi paginasi select2
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>