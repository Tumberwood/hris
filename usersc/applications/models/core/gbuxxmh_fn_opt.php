<?php 
    /**
     * Digunakan untuk populate options data badan usaha
     * Table terkait    : gbuxxmh
     * Parameter        : 
     *  - id_gbuxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_gbuxxmh_old'] > 0){
        $id_gbuxxmh_old = $_GET['id_gbuxxmh_old'];
    }else{
        $id_gbuxxmh_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_gbuxxmh_old > 0){
        $qs_gbuxxmh_self = $db
            ->query('select', 'gbuxxmh')
            ->get([
                'gbuxxmh.id as id',
                'IF(
                    gbuxxmh.kode IS NULL, 
                    gbuxxmh.nama,
                    IF(
                        gbuxxmh.nama IS NULL, 
                        gbuxxmh.kode,
                        CONCAT(gbuxxmh.kode," - ",gbuxxmh.nama)
                    )
                ) as text'
            ])
            ->where('gbuxxmh..id', $id_gbuxxmh_old )
            ->exec();
        $rs_gbuxxmh_self = $qs_gbuxxmh_self->fetchAll();
    }else{
        $rs_gbuxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_gbuxxmh_all = $db
        ->query('select', 'gbuxxmh')
        ->get([
            'gbuxxmh.id as id',
            'IF(
                gbuxxmh.kode IS NULL, 
                gbuxxmh.nama,
                IF(
                    gbuxxmh.nama IS NULL, 
                    gbuxxmh.kode,
                    CONCAT(gbuxxmh.kode," - ",gbuxxmh.nama)
                )
            ) as text'
        ])
        ->where('gbuxxmh.is_active',1)
        ->where('gbuxxmh.id', $id_gbuxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('gbuxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('gbuxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_gbuxxmh_all = $qs_gbuxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_gbuxxmh_self) > 0){
        $rs_opt = array_merge($rs_gbuxxmh_self, $rs_gbuxxmh_all);
    }else{
        $rs_opt = $rs_gbuxxmh_all;
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