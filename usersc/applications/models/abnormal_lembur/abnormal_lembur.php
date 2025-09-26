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
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$qs_data_sql = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					a.id_hemxxmh,
					TRIM(b.kode) AS kode,
					b.nama,
					d.nama status,
					e.nama sub_tipe,
					SUM(IFNULL(lembur15,0)) AS sum_lembur15,
					SUM(IFNULL(lembur2,0)) AS sum_lembur2,
					SUM(IFNULL(lembur3,0)) AS sum_lembur3,
					SUM(IFNULL(lembur4,0)) AS sum_lembur4,
					SUM(IFNULL(a.is_makan,0)) AS sum_makan
				FROM htsprrd a
				LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
				LEFT JOIN hemjbmh c ON c.id_hemxxmh = b.id
				LEFT JOIN hesxxmh d ON d.id = c.id_hesxxmh
				LEFT JOIN heyxxmd e ON e.id = c.id_heyxxmd
				WHERE a.tanggal BETWEEN :start_date AND :end_date AND a.is_active = 1
				GROUP BY a.id_hemxxmh
	');
	$rs_data_sql = $qs_data_sql->fetchAll();
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"lembur" => $rs_data_sql  
	];

    // BEGIN results akhir
	$is_debug = true;
    if($is_debug == true){
        $results = array(
            "debug" => $debug,
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }else{
        $results = array(
            "data" => $data,
            "results" => $rs_opt,
            "pagination" => array(
                "more" => $morePages
            )
        );
    }
    // END results akhir

    echo json_encode($results);

?>