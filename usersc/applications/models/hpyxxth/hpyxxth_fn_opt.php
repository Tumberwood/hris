<?php 
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

    if($_GET['id_hpyxxth_old'] > 0){
        $id_hpyxxth_old = $_GET['id_hpyxxth_old'];
    }else{
        $id_hpyxxth_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hpyxxth_old > 0){
        $qs_hpyxxth_self = $db
            ->query('select', 'hpyxxth')
            ->get([
                'hpyxxth.id as id',
                'concat(hpyxxth.tanggal_awal," s/d ",hpyxxth.tanggal_akhir ) as text'
            ])
            ->where('hpyxxth.id', $id_hpyxxth_old )
            ->exec();
        $rs_hpyxxth_self = $qs_hpyxxth_self->fetchAll();
    }else{
        $rs_hpyxxth_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hpyxxth_all = $db
        ->query('select', 'hpyxxth')
        ->get([
            'hpyxxth.id as id',
            'concat(hpyxxth.tanggal_awal," s/d ",hpyxxth.tanggal_akhir ) as text'
        ])
        ->where('hpyxxth.is_single',0)
        ->where('hpyxxth.is_active',1)
        ->where('hpyxxth.id', $id_hpyxxth_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hpyxxth.tanggal_awal', '%' . $q . '%', 'LIKE' )
                ->or_where('hpyxxth.tanggal_akhir', '%' . $q . '%', 'LIKE' )
                ;
        } )
        ->order('hpyxxth.tanggal_akhir')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hpyxxth_all = $qs_hpyxxth_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hpyxxth_self) > 0){
        $rs_opt = array_merge($rs_hpyxxth_self, $rs_hpyxxth_all);
    }else{
        $rs_opt = $rs_hpyxxth_all;
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