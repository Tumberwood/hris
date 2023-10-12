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

    // BEGIN select2 pagination preparation
    $page        = $_GET['page'];
    $resultCount = 10;
    $offset      = ($page - 1) * $resultCount;
    // END select2 pagination preparation

    if($_GET['id_hpcxxmh_old'] > 0){
        $id_hpcxxmh_old = $_GET['id_hpcxxmh_old'];
    }else{
        $id_hpcxxmh_old = 0;
    }

    if(isset($_GET['is_lain'])){
        $is_lain = $_GET['is_lain'];
        $op_lain ='=';
    } else {
        $is_lain = -9;
        $op_lain = '<>';
    }
    
    if(isset($_GET['is_jenis'])){
        $is_jenis = $_GET['is_jenis'];
        $op_jenis ='=';
    } else {
        $is_jenis = -9;
        $op_jenis = '<>';
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hpcxxmh_old > 0){
        $qs_hpcxxmh_self = $db
            ->query('select', 'hpcxxmh')
            ->get([
                'hpcxxmh.id as id',
                'hpcxxmh.nama as text'
            ])
            ->where('hpcxxmh.id', $id_hpcxxmh_old )
            ->limit(1)
            ->offset($offset)
            ->exec();
        $rs_hpcxxmh_self = $qs_hpcxxmh_self->fetchAll();
    }else{
        $rs_hpcxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hpcxxmh_all = $db
        ->query('select', 'hpcxxmh')
        ->get([
            'hpcxxmh.id as id',
            'hpcxxmh.nama as text'
        ])
        ->where('hpcxxmh.is_active',1)
        ->where('hpcxxmh.id', $id_hpcxxmh_old, '<>' )
        ->where('hpcxxmh.is_lain', $is_lain, $op_lain)
        ->where('hpcxxmh.jenis', $is_jenis, $op_jenis)
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hpcxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hpcxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hpcxxmh.id')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hpcxxmh_all = $qs_hpcxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hpcxxmh_self) > 0){
        $rs_opt = array_merge($rs_hpcxxmh_self, $rs_hpcxxmh_all);
    }else{
        $rs_opt = $rs_hpcxxmh_all;
    }
    $c_rs_opt = count($rs_opt);    
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END finalisasi paginasi select2
    $data = array(
        'id_hpcxxmh_old'=> $id_hpcxxmh_old,
        'jenis'=> $_GET['is_jenis'],
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>