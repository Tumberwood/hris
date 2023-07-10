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
    
    $qs_hadxxmh = $db
        ->query('select', 'hadxxmh')
        ->get([
            'hadxxmh.masa_berlaku_hari as masa_berlaku_hari'
        ])
        ->where('hadxxmh.id', $_POST['id_hadxxmh'] )
        ->exec();
    $rs_hadxxmh = $qs_hadxxmh->fetch();

    $tanggal_awal = new Carbon($_POST['tanggal_awal']);
    $tanggal_akhir = $tanggal_awal->addDay($rs_hadxxmh['masa_berlaku_hari']);
    $tanggal_akhir_dmy = $tanggal_akhir->format('d M Y');
    
    $data = array(
        'tanggal_akhir' => $tanggal_akhir_dmy
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>