<?php 
    /**
     * Digunakan untuk populate options data branch / cabang
     * Table terkait    : gbrxxmh
     * Parameter        : 
     *  - id_gbrxxmh_old       : data existing (untuk keperluan edit), dipakai di query self
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    // $tanggal_akhir = $_POST['tanggal_akhir'];
    $tanggal_akhir = new Carbon();
    ;

    $qs_hemxxmh = $db
        ->query('select', 'hemxxmh' )
        ->get([
            'hemxxmh.gender as hemxxmh_gender',
            'count(hemxxmh.id) as c_gender'
        ] )
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        // ->where('hemxxmh.is_active', 1 )
        ->where( function ( $r ) use ($tanggal_akhir) {
            $r
                ->where( 'hemjbmh.tanggal_keluar', NULL)
                // ->or_where( 'hemjbmh.tanggal_keluar', '0000-00-00')
                ->or_where( 'hemjbmh.tanggal_keluar', $tanggal_akhir->format('Y-m-d') , '>=');
        } )
        ->group_by('hemxxmh.gender')
        ->order('hemxxmh.gender')
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'hemxxmh_gender' => $rs_hemxxmh
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>