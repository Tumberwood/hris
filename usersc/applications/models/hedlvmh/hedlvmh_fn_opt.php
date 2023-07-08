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

    if($_GET['id_hedlvmh_old'] > 0){
        $id_hedlvmh_old = $_GET['id_hedlvmh_old'];
    }else{
        $id_hedlvmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hedlvmh_old > 0){
        $qs_hedlvmh_self = $db
            ->query('select', 'hedlvmh')
            ->get([
                'hedlvmh.id as id',
                'hedlvmh.nama as text'
            ])
            ->where('hedlvmh.id', $id_hedlvmh_old )
            ->exec();
        $rs_hedlvmh_self = $qs_hedlvmh_self->fetchAll();
    }else{
        $rs_hedlvmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hedlvmh_all = $db
        ->query('select', 'hedlvmh')
        ->get([
            'hedlvmh.id as id',
            'hedlvmh.nama as text'
        ])
        ->where('hedlvmh.is_active',1)
        ->where('hedlvmh.id', $id_hedlvmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hedlvmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hedlvmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hedlvmh.nama')
        ->limit(10)
        ->offset($offset)
        ->exec();
    $rs_hedlvmh_all = $qs_hedlvmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hedlvmh_self) > 0){
        $rs_opt = array_merge($rs_hedlvmh_self, $rs_hedlvmh_all);
    }else{
        $rs_opt = $rs_hedlvmh_all;
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