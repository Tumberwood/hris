<?php
	/**
     * Digunakan untuk mengambil data dan kalkulasi total penawaran
	 */

    require_once( "../../../../users/init.php" );
    require_once( "../../../../usersc/lib/DataTables.php" );
    require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
 
    use
        DataTables\Editor,
        DataTables\Editor\Query,
        DataTables\Editor\Result;
	
    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	$id_hemxxmh = $_POST['id_hemxxmh'];
    $id_hesxxmh = $_POST['id_hesxxmh'];

	// $qs_hemxxmh = $db
    // ->raw()
    // ->bind(':id_hesxxmh', $id_hesxxmh)
    // ->bind(':id_hemxxmh', $id_hemxxmh)
    // ->exec('SELECT
    //             COUNT(a.id) AS status_ke
    //         FROM hemxxmh AS a
    //         LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
    //         WHERE a.id = :id_hemxxmh AND b.id_hesxxmh = id_hesxxmh
    //         '
    //         );
    // $rs_hemxxmh = $qs_hemxxmh->fetch();

    
    $qs_hemxxmh_nama = $db
        ->query('select', 'hemxxmh')
        ->get('nama')
        ->where('hemxxmh.id', $id_hemxxmh)
        ->exec();
    $rs_hemxxmh_nama = $qs_hemxxmh_nama->fetch();
    $nama = $rs_hemxxmh_nama['nama'];

    $qs_hemxxmh = $db
        ->query('select', 'hemxxmh')
        ->get('count(hemxxmh.id) as status_ke')
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        ->where('hemxxmh.nama', $nama) //Where dengan nama, karena kalau pakai id_hasilnya akan tetap 1
        ->where('hemjbmh.id_hesxxmh', $id_hesxxmh) //dengan status yang sama dengan yang dipilih
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetch();

	$data = array(
        'rs_hemxxmh'=>$rs_hemxxmh
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>