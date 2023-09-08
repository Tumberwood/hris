<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    if($_POST['tanggal'] != '' && $_POST['id_hemxxmh'] > 0){
        $tanggal = new Carbon($_POST['tanggal']);
        $qs_htsxxmh = $db
            ->query('select', 'htssctd')
            ->get([
                'htsxxmh.id as id',
                'htsxxmh.kode as kode',
                'htsxxmh.jam_awal as jam_awal',
                'htsxxmh.jam_akhir as jam_akhir'
            ])
            ->join('htsxxmh','htsxxmh.id = htssctd.id_htsxxmh','LEFT' )
            ->where('htssctd.id_hemxxmh', $_POST['id_hemxxmh'] )
            ->where('htssctd.tanggal', $tanggal->format('Y-m-d') )
            ->where('htssctd.is_active', 1 ) //add by ferry, jika tidak dikasih ini maka data yang cancel approve akan dipilih pertama
            ->exec();
        $rs_htsxxmh = $qs_htsxxmh->fetch();
        $data = array(
            'rs_htsxxmh' => $rs_htsxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>