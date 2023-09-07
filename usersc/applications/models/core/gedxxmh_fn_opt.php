<?php 
    /**
     * Digunakan untuk populate options data city / kota
     * Table terkait    : gedxxmh
     * Parameter        : 
     *  - id_gedxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_gedxxmh_old'] > 0){
        $id_gedxxmh_old = $_GET['id_gedxxmh_old'];
    }else{
        $id_gedxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_gedxxmh_old > 0){
        $qs_gedxxmh_self = $db
            ->query('select', 'gedxxmh')
            ->get([
                'gedxxmh.id as id',
                'concat(gedxxmh.nama) as text'
            ])
            ->where('gedxxmh.id', $id_gedxxmh_old )
            ->exec();
        $rs_gedxxmh_self = $qs_gedxxmh_self->fetchAll();
    }else{
        $rs_gedxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_gedxxmh_all = $db
        ->query('select', 'gedxxmh')
        ->get([
            'gedxxmh.id as id',
            'concat(gedxxmh.nama) as text'
        ])
        ->where('gedxxmh.is_active',1)
        ->where('gedxxmh.id', $id_gedxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('gedxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_gedxxmh_all = $qs_gedxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_gedxxmh_self) > 0){
        $rs_opt = array_merge($rs_gedxxmh_self, $rs_gedxxmh_all);
    }else{
        $rs_opt = $rs_gedxxmh_all;
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