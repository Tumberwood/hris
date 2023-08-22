<?php 
    /**
     * Digunakan untuk populate options data users
     * Table terkait    : users
     * Parameter        : 
     *  - id_users_old       : data existing (untuk keperluan edit), dipakai di query self 
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

    if($_GET['id_users_old'] > 0){
        $id_users_old = $_GET['id_users_old'];
    }else{
        $id_users_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_users_old > 0){
        $qs_users_self = $db
            ->query('select', 'users')
            ->get([
                'users.id as id',
                'concat(users.username," - ",hemxxmh.nama) as text'
            ])
            ->join('hemxxmh','hemxxmh.id_users = users.id','LEFT' )
            ->where('users.id', $id_users_old )
            ->exec();
        $rs_users_self = $qs_users_self->fetchAll();
    }else{
        $rs_users_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_users_all = $db
        ->query('select', 'users')
        ->get([
            'users.id as id',
            'if(
                hemxxmh.nama = null,
                concat(users.username," - ",users.fname, " ", users.lname),
                concat(users.username," - ",hemxxmh.nama),
            ) as text'
        ])
        ->join('users_extend','users_extend.id = users.id','LEFT' )
        ->join('hemxxmh','hemxxmh.id_users = users.id','LEFT' )
        ->where('users.active',1)
        ->where('users.id', 100, '>' )
        ->where('users.id', $id_users_old, '<>' )
        
        ->where( function ( $r ) {
            $r
                ->or_where('users_extend.is_hakakses', 1, '<>' )
                ->or_where('users_extend.is_hakakses', null, '=' );
        } )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r->where('users.username', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_users_all  = $qs_users_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_users_self) > 0){
        $rs_opt = array_merge($rs_users_self, $rs_users_all);
    }else{
        $rs_opt = $rs_users_all;
    }
    $c_rs_opt = count($rs_opt);
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END finalisasi paginasi select2
    
    // tampilkan results
    // tidak pakai fn_ajax_result.php
    // karena ada username yang depannya 0, sehingga hilang kena JSON_NUMERIC_CHECK

    // BEGIN results akhir
    $is_debug = true;
    if($is_debug == true){
        $results = array(
            "debug" => $debug,
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }else{
        $results = array(
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }
    
    // END results akhir

    echo json_encode($results);

?>