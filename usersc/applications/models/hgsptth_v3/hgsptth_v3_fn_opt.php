<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : hgsptth_v3
     * Parameter        : 
     *  - id_hgsptth_v3_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_hgsptth_v3_old'] > 0){
        $id_hgsptth_v3_old = $_GET['id_hgsptth_v3_old'];
    }else{
        $id_hgsptth_v3_old = 0;
    }

    if($_GET['id_transaksi_h'] > 0){
        $id_transaksi_h = $_GET['id_transaksi_h'];
    }else{
        $id_transaksi_h = 0;
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hgsptth_v3_old > 0){
        $qs_hgsptth_v3_self = $db
            ->query('select', 'hgsptth_v3')
            ->get([
                'id as id',
                'CONCAT(DATE_FORMAT(tanggal_awal, "%d %b %Y"), " - ", DATE_FORMAT(tanggal_akhir, "%d %b %Y") ) as text'
            ])
            ->where('id', $id_hgsptth_v3_old )
            ->exec();
        $rs_hgsptth_v3_self = $qs_hgsptth_v3_self->fetchAll();
    }else{
        $rs_hgsptth_v3_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_hgsptth_v3_all = $db
        ->query('select', 'hgsptth_v3')
        ->get([
            'id as id',
            'CONCAT(DATE_FORMAT(tanggal_awal, "%d %b %Y"), " - ", DATE_FORMAT(tanggal_akhir, "%d %b %Y") ) as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_hgsptth_v3_old, '<>' )
        ->where('id', $id_transaksi_h, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('DATE_FORMAT(tanggal_awal, "%d %b %Y")', '%' . $q . '%', 'LIKE' )
                ->or_where('DATE_FORMAT(tanggal_akhir, "%d %b %Y")', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->order('tanggal_awal DESC')
        ->offset($offset)
        ->exec();
    $rs_hgsptth_v3_all = $qs_hgsptth_v3_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_hgsptth_v3_self) > 0){
        $rs_opt = array_merge($rs_hgsptth_v3_self, $rs_hgsptth_v3_all);
    }else{
        $rs_opt = $rs_hgsptth_v3_all;
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