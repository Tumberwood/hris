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
	$qs_hemxxmh  = $db
		->raw()
		->bind(':id_hemxxmh', $id_hemxxmh )
		->exec(' SELECT
                    c.id as id_het,
                    c.nama as nama_het,
                    d.id as id_hod,
                    d.nama as nama_hod
                FROM hemxxmh AS a
                LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
                LEFT JOIN hetxxmh AS c ON c.id = b.id_hetxxmh
                LEFT JOIN hodxxmh AS d ON d.id = b.id_hodxxmh
                WHERE a.id = :id_hemxxmh;
    
				'
				);
    $rs_hemxxmh  = $qs_hemxxmh ->fetch();

	$data = array(
        'rs_hemxxmh'=>$rs_hemxxmh
    );

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>