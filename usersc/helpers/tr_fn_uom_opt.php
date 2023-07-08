<?php 
    /**
     * Digunakan untuk mendapatkan generate options uom berdasarkan item terpilih
     * 
     * Parameter dari front end (dipanggil di dependent):
     * $_POST['id_iimxxmh']
     */

    include_once( "../../users/init.php" );
	include_once( "../../usersc/lib/DataTables.php" );

	$id_iimxxmh = $_POST['id_iimxxmh'];

    $qs_iumxxmh = $db
        ->query('select', 'iimummd')
        ->get([
            'iumxxmh.id as value',
            'iumxxmh.kode as label'
        ])
        ->join('iumxxmh','iumxxmh.id = iimummd.id_iumxxmh','LEFT')
        ->where('iimummd.id_iimxxmh', $id_iimxxmh)
        ->where('iumxxmh.is_active', 1)
        ->exec();
    $rs_iumxxmh = $qs_iumxxmh->fetchAll();

    echo json_encode($rs_iumxxmh);

?>