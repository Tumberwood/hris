<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : hodxxmh
     * Parameter        : 
     *  - id_hodxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    $id_hodxxmh = 0;
    if($_GET['id_hodxxmh_old'] > 0){
        $id_hodxxmh_old = $_GET['id_hodxxmh_old'];
    }else{
        $id_hodxxmh_old = 0;
    }

    if (isset($_GET['id_hodxxmh'])) {
        if($_GET['id_hodxxmh'] > 0){
            $id_hodxxmh = $_GET['id_hodxxmh'];
        }else{
            $id_hodxxmh = 0;
        }
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hodxxmh_old > 0){
        $qs_hodxxmh_self = $db
            ->query('select', 'hodxxmh')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_hodxxmh_old )
            ->limit(1)
            ->offset($offset)
            ->exec();
        $rs_hodxxmh_self = $qs_hodxxmh_self->fetchAll();
    }else{
        // $rs_hodxxmh_self = [];
        if($id_hodxxmh > 0){
            $qs_hodxxmh_self = $db
                ->query('select', 'hodxxmh')
                ->get([
                    'id as id',
                    'nama as text'
                ])
                ->where('id', $id_hodxxmh )
                ->limit(1)
                ->offset($offset)
                ->exec();
            $rs_hodxxmh_self = $qs_hodxxmh_self->fetchAll();
        }else{
            $rs_hodxxmh_self = [];
        }
    }
    // END query self

    // BEGIN query options all except self
    $qs_hodxxmh_all = $db
        ->query('select', 'hodxxmh')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_hodxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hodxxmh_all = $qs_hodxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_hodxxmh_self) > 0){
        $rs_opt = array_merge($rs_hodxxmh_self, $rs_hodxxmh_all);
    }else{
        $rs_opt = $rs_hodxxmh_all;
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