<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : hevxxmh
     * Parameter        : 
     *  - id_hevxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_hevxxmh_old'] > 0){
        $id_hevxxmh_old = $_GET['id_hevxxmh_old'];
    }else{
        $id_hevxxmh_old = 0;
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hevxxmh_old > 0){
        $qs_hevxxmh_self = $db
            ->query('select', 'hevxxmh')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_hevxxmh_old )
            ->exec();
        $rs_hevxxmh_self = $qs_hevxxmh_self->fetchAll();
    }else{
        $rs_hevxxmh_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_hevxxmh_all = $db
        ->query('select', 'hevxxmh')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_hevxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hevxxmh_all = $qs_hevxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_hevxxmh_self) > 0){
        $rs_opt = array_merge($rs_hevxxmh_self, $rs_hevxxmh_all);
    }else{
        $rs_opt = $rs_hevxxmh_all;
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