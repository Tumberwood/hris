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

	$qs_hemxxmh  = $db
		->raw()
		->bind(':nama', $nama )
		->exec(' SELECT
                    a.nama,
                    a.id,
                    c.nama,
                    b.tanggal_awal as tanggal_masuk,
                    b.tanggal_akhir as tanggal_keluar
                FROM hemxxmh AS a
                LEFT JOIN hemjbrd AS b ON b.id_hemxxmh = a.id
                LEFT JOIN hesxxmh AS c ON c.id = b.id_hesxxmh
                WHERE a.nama = :nama
                ORDER BY a.id DESC
                LIMIT 1;
    
				'
				);
    $rs_hemxxmh  = $qs_hemxxmh ->fetch();

	$qs_c_hemxxmh  = $db
		->raw()
		->bind(':nama', $nama )
		->exec(' SELECT
                    COUNT(a.id) AS status_ke
                FROM hemxxmh AS a
                LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
                LEFT JOIN hesxxmh AS c ON c.id = b.id_hesxxmh
                WHERE a.nama = :nama;
    
				'
				);
    $rs_c_hemxxmh  = $qs_c_hemxxmh ->fetch();

	$qs_c_hemjbrd  = $db
		->raw()
		->bind(':nama', $nama )
		->exec(' SELECT
                    COUNT(b.id) AS status_ke
                FROM hemxxmh AS a
                LEFT JOIN hemjbrd AS b ON b.id_hemxxmh = a.id
                WHERE a.nama = :nama 
                    AND b.id_harxxmh = 2;
    
				'
				);
    $rs_c_hemjbrd  = $qs_c_hemjbrd ->fetch();

    $status_ke = $rs_c_hemxxmh['status_ke'] + $rs_c_hemjbrd['status_ke'];

	$data = array(
        'rs_hemxxmh'=>$rs_hemxxmh,
        'status_ke'=>$status_ke,
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>