<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : holxxmd_2
     * Parameter        : 
     *  - id_holxxmd_2_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_holxxmd_2_old'] > 0){
        $id_holxxmd_2_old = $_GET['id_holxxmd_2_old'];
    }else{
        $id_holxxmd_2_old = 0;
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_holxxmd_2_old > 0){
        $qs_holxxmd_2_self = $db
            ->query('select', 'holxxmd_2')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_holxxmd_2_old )
            ->exec();
        $rs_holxxmd_2_self = $qs_holxxmd_2_self->fetchAll();
    }else{
        $rs_holxxmd_2_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_holxxmd_2_all = $db
        ->query('select', 'holxxmd_2')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_holxxmd_2_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_holxxmd_2_all = $qs_holxxmd_2_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_holxxmd_2_self) > 0){
        $rs_opt = array_merge($rs_holxxmd_2_self, $rs_holxxmd_2_all);
    }else{
        $rs_opt = $rs_holxxmd_2_all;
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