<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : periode_payroll
     * Parameter        : 
     *  - id_periode_payroll_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_periode_payroll_old'] > 0){
        $id_periode_payroll_old = $_GET['id_periode_payroll_old'];
    }else{
        $id_periode_payroll_old = 0;
    }

    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_periode_payroll_old > 0){
        $qs_periode_payroll_self = $db
            ->query('select', 'periode_payroll')
            ->get([
                'id as id',
                'CONCAT(DATE_FORMAT(tanggal_awal, "%d %b %Y"), " - ", DATE_FORMAT(tanggal_akhir, "%d %b %Y") ) as text'
            ])
            ->where('id', $id_periode_payroll_old )
            ->exec();
        $rs_periode_payroll_self = $qs_periode_payroll_self->fetchAll();
    }else{
        $rs_periode_payroll_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_periode_payroll_all = $db
        ->query('select', 'periode_payroll')
        ->get([
            'id as id',
            'CONCAT(DATE_FORMAT(tanggal_awal, "%d %b %Y"), " - ", DATE_FORMAT(tanggal_akhir, "%d %b %Y") ) as text'
        ])
        ->where('is_active',1)
        ->where('status',"Dibuka")
        ->where('id', $id_periode_payroll_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('DATE_FORMAT(tanggal_awal, "%d %b %Y")', '%' . $q . '%', 'LIKE' )
                ->or_where('DATE_FORMAT(tanggal_akhir, "%d %b %Y")', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_periode_payroll_all = $qs_periode_payroll_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_periode_payroll_self) > 0){
        $rs_opt = array_merge($rs_periode_payroll_self, $rs_periode_payroll_all);
    }else{
        $rs_opt = $rs_periode_payroll_all;
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