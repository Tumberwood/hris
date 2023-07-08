<?php 
    /**
     * Digunakan untuk populate options data company
     * Table terkait    : gcpxxmh
     * Parameter        : 
     *  - id_gcpxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_gcpxxmh_old'] > 0){
        $id_gcpxxmh_old = $_GET['id_gcpxxmh_old'];
    }else{
        $id_gcpxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_gcpxxmh_old > 0){
        $qs_gcpxxmh_self = $db
            ->query('select', 'gcpxxmh')
            ->get([
                'id as id',
                'concat(kode," - ",nama) as text'
            ])
            ->where('id', $id_gcpxxmh_old )
            ->exec();
        $rs_gcpxxmh_self = $qs_gcpxxmh_self->fetchAll();
    }else{
        $rs_gcpxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_gcpxxmh_all = $db
        ->query('select', 'gcpxxmh')
        ->get([
            'id as id',
            'concat(kode," - ",nama) as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_gcpxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_gcpxxmh_all = $qs_gcpxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_gcpxxmh_self) > 0){
        $rs_opt = array_merge($rs_gcpxxmh_self, $rs_gcpxxmh_all);
    }else{
        $rs_opt = $rs_gcpxxmh_all;
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