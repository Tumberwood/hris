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
            
            // IFNULL(web_path,'img/profile_small.jpg') as web_path,
			// nama_lengkap,
			// namamhjabatan,
			// masa_kerja,
			// DATE_FORMAT(tanggal_masuk,'%d %m') as tanggal_masuk

            'hemxxmh.kode as hemxxmh_kode',
            'hemxxmh.nama as hemxxmh_nama',
            'hodxxmh.nama as hodxxmh_nama',
            'hetxxmh.nama as hetxxmh_nama',
            'TIMESTAMPDIFF(YEAR, hemjbmh.tanggal_masuk, CURDATE()) as masakerja'
        ] )
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        ->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT' )
        ->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT' )
        ->where('hemxxmh.id_users', $_SESSION['user'] )
        // ->where( function ( $r ) use ($tanggal_akhir) {
        //     $r
        //         ->where( 'hemjbmh.tanggal_keluar', NULL)
        //         ->or_where( 'hemjbmh.tanggal_keluar', '0000-00-00')
        //         ->or_where( 'hemjbmh.tanggal_keluar', $tanggal_akhir->format('Y-m-d') , '>=');
        // } )
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetch();

    $data = array(
        'hemxxmh' => $rs_hemxxmh
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>