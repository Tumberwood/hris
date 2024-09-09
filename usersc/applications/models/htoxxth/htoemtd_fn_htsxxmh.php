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
            ->raw()
            ->bind(':id_hemxxmh', $_POST['id_hemxxmh'] )
            ->bind(':tanggal', $tanggal->format('Y-m-d') )
            ->exec('SELECT 
                        htsxxmh.id AS id,
                        htsxxmh.kode AS kode,
                        htsxxmh.jam_awal AS jam_awal,
                        htsxxmh.jam_akhir AS jam_akhir,
                        DAYNAME(htssctd.tanggal) AS dayname
                    FROM 
                        htssctd
                    LEFT JOIN 
                        htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                    WHERE 
                        htssctd.is_active = 1
                        AND htssctd.id_hemxxmh = :id_hemxxmh
                        AND htssctd.tanggal = :tanggal;
        
            '
            );
        $rs_htsxxmh = $qs_htsxxmh->fetch();
        $data = array(
            'rs_htsxxmh' => $rs_htsxxmh
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>