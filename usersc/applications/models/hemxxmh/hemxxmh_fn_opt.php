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

    if($_GET['id_hemxxmh_old'] > 0){
        $id_hemxxmh_old = $_GET['id_hemxxmh_old'];
    }else{
        $id_hemxxmh_old = 0;
    }

    if (isset($_GET['id_heyxxmh'])) {
        $id_heyxxmh = $_GET['id_heyxxmh'];
        if ($id_heyxxmh > 0) {
            if (strpos($id_heyxxmh, ',') !== false) {
                // Jika terdapat tanda koma, maka pecah string menjadi array
                $id_values = explode(',', $id_heyxxmh);
                $w_id_heyxxmh = '(' . implode(',', $id_values) . ')';
                $s_id_heyxxmh = 'IN';
            } else {
                // Jika hanya satu nilai atau bukan array, gunakan default
                $w_id_heyxxmh = '(' . $id_heyxxmh . ')';
                $s_id_heyxxmh = 'IN';
            }
        } else {
            $w_id_heyxxmh = '(-1)';
            $s_id_heyxxmh = 'NOT IN';
        }
    } else {
        // Handle the case where id_heyxxmh is not defined
        $w_id_heyxxmh = '(-1)';
        $s_id_heyxxmh = 'NOT IN';
    }

    //ambil status pegawai (ex: kontrak , tetap , ect)
    if (isset($_GET['id_hesxxmh'])) {
        $id_hesxxmh = $_GET['id_hesxxmh'];
        if ($id_hesxxmh != '') {
            $w_id_hesxxmh = $id_hesxxmh;
            $s_id_hesxxmh = '=';
        } else {
            $w_id_hesxxmh = -1;
            $s_id_hesxxmh = '<>';
        }
    } else {
        // Handle the case where id_hesxxmh is not defined
        $w_id_hesxxmh = -1;
        $s_id_hesxxmh = '<>';
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hemxxmh_old > 0){
        $qs_hemxxmh_self = $db
            ->query('select', 'hemxxmh')
            ->get([
                'hemxxmh.id as id',
                'concat(hemxxmh.kode," - ",hemxxmh.nama," - ",IFNULL(hetxxmh.nama,"Jabatan Missing") ) as text'
            ])
            ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
            ->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
            ->where('hemxxmh.id', $id_hemxxmh_old )
            ->exec();
        $rs_hemxxmh_self = $qs_hemxxmh_self->fetchAll();
    }else{
        $rs_hemxxmh_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_hemxxmh_all = $db
        ->query('select', 'hemxxmh')
        ->get([
            'hemxxmh.id as id',
            'concat(hemxxmh.kode," - ",hemxxmh.nama," - ",IFNULL(hetxxmh.nama,"Jabatan Missing") ) as text'
        ])
		->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT')
		->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT')
        ->where('hemxxmh.is_active',1)
        ->where('hemjbmh.id_heyxxmh', $w_id_heyxxmh, $s_id_heyxxmh, false )
        ->where('hemjbmh.id_hesxxmh', $w_id_hesxxmh, $s_id_hesxxmh)
        ->where('hemxxmh.id', $id_hemxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('hemxxmh.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('hemxxmh.nama', '%' . $q . '%', 'LIKE' )
                ->or_where('hetxxmh.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->order('hemxxmh.nama')
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hemxxmh_all = $qs_hemxxmh_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_hemxxmh_self) > 0){
        $rs_opt = array_merge($rs_hemxxmh_self, $rs_hemxxmh_all);
    }else{
        $rs_opt = $rs_hemxxmh_all;
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