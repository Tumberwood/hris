<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : urutan_simbol_m
     * Parameter        : 
     *  - id_urutan_simbol_m_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_urutan_simbol_m_old'] > 0){
        $id_urutan_simbol_m_old = $_GET['id_urutan_simbol_m_old'];
    }else{
        $id_urutan_simbol_m_old = 0;
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_urutan_simbol_m_old > 0){
        $qs_urutan_simbol_m_self = $db
            ->query('select', 'urutan_simbol_m')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_urutan_simbol_m_old )
            ->limit(1)
            ->offset($offset)
            ->exec();
        $rs_urutan_simbol_m_self = $qs_urutan_simbol_m_self->fetchAll();
    } else {
        $rs_urutan_simbol_m_self = [];  
    }
    // END query self

    // BEGIN query options all except self
    $qs_urutan_simbol_m_all = $db
        ->query('select', 'urutan_simbol_m')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_urutan_simbol_m_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_urutan_simbol_m_all = $qs_urutan_simbol_m_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_urutan_simbol_m_self) > 0){
        $rs_opt = array_merge($rs_urutan_simbol_m_self, $rs_urutan_simbol_m_all);
    }else{
        $rs_opt = $rs_urutan_simbol_m_all;
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