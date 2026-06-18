<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : htlxxmh
     * Parameter        : 
     *  - id_htlxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_htlxxmh_old'] > 0){
        $id_htlxxmh_old = $_GET['id_htlxxmh_old'];
    }else{
        $id_htlxxmh_old = 0;
    }

    if($_GET['id_hemxxmh'] > 0){
        $id_hemxxmh = $_GET['id_hemxxmh'];
    }else{
        $id_hemxxmh = 0;
    }

    $qs_hemxxmh = $db
        ->raw()
        ->bind(':id_hemxxmh', $id_hemxxmh)
        ->exec('SELECT
                    id_heyxxmd
                FROM hemjbmh a
                WHERE a.id_hemxxmh = :id_hemxxmh
    ');
    
    $rs_hemxxmh = $qs_hemxxmh->fetch();
    $id_heyxxmd = 0;

    if ($rs_hemxxmh) {
        $id_heyxxmd = $rs_hemxxmh['id_heyxxmd'];
    } else {
        $id_heyxxmd = 0;
    }
    
    //KBM
    if ($id_heyxxmd == 1) {
        $exclude_kbm = '(1,2,3)';
        $w_exclude_kbm = 'NOT IN';
    }else{
        $exclude_kbm = '(-1)';
        $w_exclude_kbm = 'NOT IN';
    }
    

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_htlxxmh_old > 0){
        $qs_htlxxmh_self = $db
            ->query('select', 'htlxxmh')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_htlxxmh_old )
            ->exec();
        $rs_htlxxmh_self = $qs_htlxxmh_self->fetchAll();
    }else{
        $rs_htlxxmh_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_htlxxmh_all = $db
        ->query('select', 'htlxxmh')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_htlxxmh_old, '<>' )
        ->where('id', $exclude_kbm, $w_exclude_kbm, false)
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_htlxxmh_all = $qs_htlxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_htlxxmh_self) > 0){
        $rs_opt = array_merge($rs_htlxxmh_self, $rs_htlxxmh_all);
    }else{
        $rs_opt = $rs_htlxxmh_all;
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