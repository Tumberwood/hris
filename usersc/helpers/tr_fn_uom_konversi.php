<?php
	/**
     * Digunakan untuk mendapatkan data:
     * jumlah_konversi
     * jumlah_terkecil
     * id_iumxxmh_terkecil
     * 
     * Parameter dari front end (dipanggil di dependent):
     * $_POST['id_iimxxmh']
     * $_POST['id_iumxxmh_unit']
     * $_POST['jumlah_unit']
     */
	
	include_once( "../../users/init.php" );
	include_once( "../../usersc/lib/DataTables.php" );
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

    $id_iimxxmh      = $_POST['id_iimxxmh'];
    $id_iumxxmh_unit = $_POST['id_iumxxmh_unit'];
    $jumlah_unit     = $_POST['jumlah_unit'];

    $results = array();
    
    $qs_jumlah_konversi = $db
        ->query('select', 'iimummd')
        ->get('jumlah_konversi')
        ->where('id_iimxxmh',$id_iimxxmh)
        ->where('id_iumxxmh',$id_iumxxmh_unit)
        ->exec();
    
    $rs_jumlah_konversi = $qs_jumlah_konversi->fetch();
    $jumlah_konversi        = intval($rs_jumlah_konversi['jumlah_konversi']);
    $jumlah_terkecil        = intval($jumlah_konversi) * intval($jumlah_unit); 

    $results['jumlah_konversi'] = $jumlah_konversi;
    $results['jumlah_terkecil'] = $jumlah_terkecil;

    $qs_select = $db
        ->query('select', 'iimummd')
        ->get(['id','id_iumxxmh'])
        ->where('id_iimxxmh',$id_iimxxmh)
        ->where('is_terkecil',1)
        ->exec();

    $rs_id_iumxxmh_terkecil = $qs_select->fetch(); 
    $id_iumxxmh_terkecil        = intval($rs_id_iumxxmh_terkecil['id_iumxxmh']);

    $results['id_iumxxmh_terkecil'] = $id_iumxxmh_terkecil;

    echo json_encode($results);	
?>