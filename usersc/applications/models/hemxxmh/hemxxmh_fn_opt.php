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

    if($_GET['id_hemxxmh_old'] > 0){
        $id_hemxxmh_old = $_GET['id_hemxxmh_old'];
    }else{
        $id_hemxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hemxxmh_old > 0){
        $qs_hemxxmh_self = $db
            ->query('select', 'hemxxmh')
            ->get([
                'hemxxmh.id as id',
                'concat(hemxxmh.kode," - ",hemxxmh.nama," - ",IFNULL(hetxxmh.nama,"Jabatan Missing") ) as text'
            ])
            ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
            ->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
            ->where('hemxxmh.id', $id_hemxxmh_old )
            ->exec();
        $rs_hemxxmh_self = $qs_hemxxmh_self->fetchAll();
    }else{
        $rs_hemxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hemxxmh_all = $db
        ->query('select', 'hemxxmh')
        ->get([
            'hemxxmh.id as id',
            'concat(hemxxmh.kode," - ",hemxxmh.nama," - ",IFNULL(hetxxmh.nama,"Jabatan Missing") ) as text'
        ])
		->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
		->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
        ->where('hemxxmh.is_active',1)
        ->where('hemxxmh.id', $id_hemxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hemxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hemxxmh.nama', '%' . $q . '%', 'LIKE' )
                ->or_where('hetxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hemxxmh.nama')
        ->limit(10)
        ->offset($offset)
        ->exec();
    $rs_hemxxmh_all = $qs_hemxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hemxxmh_self) > 0){
        $rs_opt = array_merge($rs_hemxxmh_self, $rs_hemxxmh_all);
    }else{
        $rs_opt = $rs_hemxxmh_all;
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