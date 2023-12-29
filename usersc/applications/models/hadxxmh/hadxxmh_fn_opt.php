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

    if($_GET['id_hadxxmh_old'] > 0){
        $id_hadxxmh_old = $_GET['id_hadxxmh_old'];
    }else{
        $id_hadxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hadxxmh_old > 0){
        $qs_hadxxmh_self = $db
            ->query('select', 'hadxxmh')
            ->get([
                'hadxxmh.id as id',
                'hadxxmh.nama as text'
            ])
            ->where('hadxxmh.id', $id_hadxxmh_old )
            ->limit(1)
            ->offset($offset)
            ->exec();
        $rs_hadxxmh_self = $qs_hadxxmh_self->fetchAll();
    }else{
        // $rs_hadxxmh_self = [];
        if(isset($_GET['id_hadxxmh_saran']) && $_GET['id_hadxxmh_saran'] > 0){
            $qs_hadxxmh_self = $db
                ->query('select', 'havxxmh')
                ->get([
                    'hadxxmh.id as id',
                    'hadxxmh.nama as text'
                ])
                ->where('hadxxmh.id', $_GET['id_hadxxmh_saran'] )
                ->join('hadxxmh','hadxxmh.id = havxxmh.id_hadxxmh','LEFT')
                ->limit(1)
                ->offset($offset)
                ->exec();
            $rs_hadxxmh_self = $qs_hadxxmh_self->fetchAll();
        }else{
            $rs_hadxxmh_self = [];
        }
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hadxxmh_all = $db
        ->query('select', 'hadxxmh')
        ->get([
            'hadxxmh.id as id',
            'hadxxmh.nama as text'
        ])
        ->where('hadxxmh.is_active',1)
        ->where('hadxxmh.id', $id_hadxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hadxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hadxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hadxxmh.id')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hadxxmh_all = $qs_hadxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hadxxmh_self) > 0){
        $rs_opt = array_merge($rs_hadxxmh_self, $rs_hadxxmh_all);
    }else{
        $rs_opt = $rs_hadxxmh_all;
    }
    $c_rs_opt = count($rs_opt);    
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END finalisasi paginasi select2
    $data = array(
        'id_hadxxmh_old'=> $id_hadxxmh_old
    );
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>