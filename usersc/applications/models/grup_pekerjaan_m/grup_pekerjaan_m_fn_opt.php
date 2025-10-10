<?php 
    /**
     * Digunakan untuk populate options data branch / cabang
     * Table terkait    : grup_pekerjaan_m
     * Parameter        : 
     *  - id_grup_pekerjaan_m_old       : data existing (untuk keperluan edit), dipakai di query self
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

    if($_GET['id_grup_pekerjaan_m_old'] > 0){
        $id_grup_pekerjaan_m_old = $_GET['id_grup_pekerjaan_m_old'];
    }else{
        $id_grup_pekerjaan_m_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_grup_pekerjaan_m_old > 0){
        $qs_grup_pekerjaan_m_self = $db
            ->query('select', 'grup_pekerjaan_m')
            ->get([
                'grup_pekerjaan_m.id as id',
                'IF(
                    grup_pekerjaan_m.kode IS NULL, 
                    grup_pekerjaan_m.nama,
                    IF(
                        grup_pekerjaan_m.nama IS NULL, 
                        grup_pekerjaan_m.kode,
                        CONCAT(grup_pekerjaan_m.kode," - ",grup_pekerjaan_m.nama)
                    )
                ) as text'
            ])
            ->where('grup_pekerjaan_m..id', $id_grup_pekerjaan_m_old )
            ->exec();
        $rs_grup_pekerjaan_m_self = $qs_grup_pekerjaan_m_self->fetchAll();
    }else{
        $rs_grup_pekerjaan_m_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_grup_pekerjaan_m_all = $db
        ->query('select', 'grup_pekerjaan_m')
        ->get([
            'grup_pekerjaan_m.id as id',
            'IF(
                grup_pekerjaan_m.kode IS NULL, 
                grup_pekerjaan_m.nama,
                IF(
                    grup_pekerjaan_m.nama IS NULL, 
                    grup_pekerjaan_m.kode,
                    CONCAT(grup_pekerjaan_m.kode," - ",grup_pekerjaan_m.nama)
                )
            ) as text'
        ])
        ->where('grup_pekerjaan_m.is_active',1)
        ->where('grup_pekerjaan_m.id', $id_grup_pekerjaan_m_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r
                ->where('grup_pekerjaan_m.kode', '%' . $q . '%', 'LIKE' )
                ->or_where('grup_pekerjaan_m.nama', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_grup_pekerjaan_m_all = $qs_grup_pekerjaan_m_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_grup_pekerjaan_m_self) > 0){
        $rs_opt = array_merge($rs_grup_pekerjaan_m_self, $rs_grup_pekerjaan_m_all);
    }else{
        $rs_opt = $rs_grup_pekerjaan_m_all;
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