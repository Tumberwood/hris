<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : hmsxxmh
     * Parameter        : 
     *  - mesin       : data existing (untuk keperluan edit), dipakai di query self
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
    
    if($_GET['mesin'] != ''){
        $mesin = $_GET['mesin'];
    }else{
        $mesin = '';
    }
    // echo $mesin;
    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($mesin != ''){
        $qs_hmsxxmh_self = $db
            ->query('select', 'hmsxxmh')
            ->get([
                'nama as id',
                'nama as text'
            ])
            ->where('nama', $mesin )
            ->exec();
        $rs_hmsxxmh_self = $qs_hmsxxmh_self->fetchAll();
    }else{
        $rs_hmsxxmh_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_hmsxxmh_all = $db
        ->query('select', 'hmsxxmh')
        ->get([
            'nama as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('nama', $mesin, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('nama', '%' . $q . '%', 'LIKE' )
                ;
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hmsxxmh_all = $qs_hmsxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_hmsxxmh_self) > 0){
        $rs_opt = array_merge($rs_hmsxxmh_self, $rs_hmsxxmh_all);
    }else{
        $rs_opt = $rs_hmsxxmh_all;
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