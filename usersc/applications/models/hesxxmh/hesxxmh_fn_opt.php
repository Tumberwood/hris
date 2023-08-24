<?php 
    /**
     * Digunakan untuk populate options data currency / mata uang
     * Table terkait    : hesxxmh
     * Parameter        : 
     *  - id_hesxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_hesxxmh_old'] > 0){
        $id_hesxxmh_old = $_GET['id_hesxxmh_old'];
    }else{
        $id_hesxxmh_old = 0;
    }

    if (isset($_GET['id_hesxxmh_tetap'])) {
        $id_hesxxmh_tetap = $_GET['id_hesxxmh_tetap'];
        if ($id_hesxxmh_tetap > 0) {
            if (strpos($id_hesxxmh_tetap, ',') !== false) {
                // Jika terdapat tanda koma, maka pecah string menjadi array
                $id_values = explode(',', $id_hesxxmh_tetap);
                $w_id_hesxxmh_tetap = '(' . implode(',', $id_values) . ')';
                $s_id_hesxxmh_tetap = 'IN';
            } else {
                // Jika hanya satu nilai atau bukan array, gunakan default
                $w_id_hesxxmh_tetap = '(' . $id_hesxxmh_tetap . ')';
                $s_id_hesxxmh_tetap = 'IN';
            }
        } else {
            $w_id_hesxxmh_tetap = '(-1)';
            $s_id_hesxxmh_tetap = 'NOT IN';
        }
    } else {
        // Handle the case where id_hesxxmh_tetap is not defined
        $w_id_hesxxmh_tetap = '(-1)';
        $s_id_hesxxmh_tetap = 'NOT IN';
    }
    // BEGIN query self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_hesxxmh_old > 0){
        $qs_hesxxmh_self = $db
            ->query('select', 'hesxxmh')
            ->get([
                'id as id',
                'nama as text'
            ])
            ->where('id', $id_hesxxmh_old )
            ->exec();
        $rs_hesxxmh_self = $qs_hesxxmh_self->fetchAll();
    }else{
        $rs_hesxxmh_self = [];
    }
    // END query self

    // BEGIN query options all except self
    $qs_hesxxmh_all = $db
        ->query('select', 'hesxxmh')
        ->get([
            'id as id',
            'nama as text'
        ])
        ->where('is_active',1)
        ->where('id', $w_id_hesxxmh_tetap, $s_id_hesxxmh_tetap, false )
        ->where('id', $id_hesxxmh_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('kode', '%' . $q . '%', 'LIKE' )
                ->or_where('nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_hesxxmh_all = $qs_hesxxmh_all->fetchAll();
    // END query options all except self

    // BEGIN menggabungkan options
    if(count($rs_hesxxmh_self) > 0){
        $rs_opt = array_merge($rs_hesxxmh_self, $rs_hesxxmh_all);
    }else{
        $rs_opt = $rs_hesxxmh_all;
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