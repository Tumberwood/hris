<?php 
    /**
     * Digunakan untuk populate options data provinsi
     * Table terkait    : gpvxxmh
     * Parameter        : 
     *  - id_gpvxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_gpvxxmh_old'] > 0){
        $id_gpvxxmh_old = $_GET['id_gpvxxmh_old'];
    }else{
        $id_gpvxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_gpvxxmh_old > 0){
        $qs_gpvxxmh_self = $db
            ->query('select', 'gpvxxmh')
            ->get([
                'gpvxxmh.id as id',
                'gpvxxmh.nama as text'
            ])
            ->where('gpvxxmh.id', $id_gpvxxmh_old )
            ->exec();
        $rs_gpvxxmh_self = $qs_gpvxxmh_self->fetchAll();
    }else{
        $rs_gpvxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_gpvxxmh_all = $db
        ->query('select', 'gpvxxmh')
        ->get([
            'gpvxxmh.id as id',
            'gpvxxmh.nama as text'
        ])
        ->where('gpvxxmh.is_active',1)
        ->where('gpvxxmh.id', $id_gpvxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('gpvxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('gpvxxmh.nama')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_gpvxxmh_all = $qs_gpvxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_gpvxxmh_self) > 0){
        $rs_opt = array_merge($rs_gpvxxmh_self, $rs_gpvxxmh_all);
    }else{
        $rs_opt = $rs_gpvxxmh_all;
    }
    $c_rs_opt = count($rs_opt);
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount >= $c_rs_opt;
    // END finalisasi paginasi select2
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>