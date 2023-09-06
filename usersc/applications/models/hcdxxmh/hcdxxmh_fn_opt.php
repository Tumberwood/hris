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

    if($_GET['id_hcdxxmh_old'] > 0){
        $id_hcdxxmh_old = $_GET['id_hcdxxmh_old'];
    }else{
        $id_hcdxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hcdxxmh_old > 0){
        $qs_hcdxxmh_self = $db
            ->query('select', 'hcdxxmh')
            ->get([
                'hcdxxmh.id as id',
                'concat(hcdxxmh.kode," - ",hcdxxmh.nama) as text'
            ])
            ->where('id', $id_hcdxxmh_old )
            ->exec();
        $rs_hcdxxmh_self = $qs_hcdxxmh_self->fetchAll();
    }else{
        $rs_hcdxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hcdxxmh_all = $db
        ->query('select', 'hcdxxmh')
        ->get([
            'hcdxxmh.id as id',
            'concat(hcdxxmh.kode," - ",hcdxxmh.nama) as text'
        ])
        ->where('hcdxxmh.is_active',1)
        ->where('hcdxxmh.id', $id_hcdxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hcdxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hcdxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hcdxxmh.nama')
        ->limit(10)
        ->offset($offset)
        ->exec();
    $rs_hcdxxmh_all = $qs_hcdxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hcdxxmh_self) > 0){
        $rs_opt = array_merge($rs_hcdxxmh_self, $rs_hcdxxmh_all);
    }else{
        $rs_opt = $rs_hcdxxmh_all;
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