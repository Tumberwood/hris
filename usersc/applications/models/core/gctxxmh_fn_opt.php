<?php 
    /**
     * Digunakan untuk populate options data city / kota
     * Table terkait    : gctxxmh
     * Parameter        : 
     *  - id_gctxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_gctxxmh_old'] > 0){
        $id_gctxxmh_old = $_GET['id_gctxxmh_old'];
    }else{
        $id_gctxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_gctxxmh_old > 0){
        $qs_gctxxmh_self = $db
            ->query('select', 'gctxxmh')
            ->get([
                'gctxxmh.id as id',
                'concat(gpvxxmh.nama," - ",gctxxmh.nama) as text'
            ])
            ->join('gpvxxmh','gpvxxmh.id = gctxxmh.id_gpvxxmh','LEFT')
            ->where('gctxxmh.id', $id_gctxxmh_old )
            ->exec();
        $rs_gctxxmh_self = $qs_gctxxmh_self->fetchAll();
    }else{
        $rs_gctxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_gctxxmh_all = $db
        ->query('select', 'gctxxmh')
        ->get([
            'gctxxmh.id as id',
            'concat(gpvxxmh.nama," - ",gctxxmh.nama) as text'
        ])
        ->join('gpvxxmh','gpvxxmh.id = gctxxmh.id_gpvxxmh','LEFT')
        ->where('gctxxmh.is_active',1)
        ->where('gctxxmh.id', $id_gctxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('gpvxxmh.nama', '%' . $q . '%', 'LIKE' )
                ->or_where('gctxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_gctxxmh_all = $qs_gctxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_gctxxmh_self) > 0){
        $rs_opt = array_merge($rs_gctxxmh_self, $rs_gctxxmh_all);
    }else{
        $rs_opt = $rs_gctxxmh_all;
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