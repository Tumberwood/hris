<?php 
    /**
     * Digunakan untuk populate options data menus
     * Table terkait    : menus
     * Parameter        : 
     *  - id_menus_old       : data existing (untuk keperluan edit), dipakai di query self 
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

    if($_GET['id_menus_old'] > 0){
        $id_menus_old = $_GET['id_menus_old'];
    }else{
        $id_menus_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_menus_old > 0){
        $qs_menus_self = $db
            ->query('select', 'menus')
            ->get([
                'id as id',
                'CONCAT(display_order, " - ",label) as text'
            ])
            ->where('id', $id_menus_old )
            ->exec();
        $rs_menus_self = $qs_menus_self->fetchAll();
    }else{
        $rs_menus_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_menus_all = $db
        ->query('select', 'menus')
        ->get([
            'id as id',
            'CONCAT(display_order, " - ",label) as text'
        ])
        ->where('id', $id_menus_old, '<>' )
        ->where( 'dropdown', 1 )
        ->where( 'menu_title', 'side' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r->where('label', '%' . $q . '%', 'LIKE' );
        } )
        ->order('display_order,label')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_menus_all  = $qs_menus_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_menus_self) > 0){
        $rs_opt = array_merge($rs_menus_self, $rs_menus_all);
    }else{
        $rs_opt = $rs_menus_all;
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