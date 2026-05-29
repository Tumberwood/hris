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
	$id_hemxxmh = $_POST['id_hemxxmh'];

	$where = '';
	if ($id_hemxxmh > 0) {
		$where .= ' AND id_hemxxmh a.id = ' . $id_hemxxmh;
	}

	$qs_data_sql = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					a.id,
					a.kode,
					a.kode_finger,
					c.ktp_no,
					a.nama,

					hodxxmh.nama AS divisi,
					hosxxmh.nama AS seksi,
					hetxxmh.nama AS title,
					holxxmd_2.nama AS lokasi,
					heyxxmh.nama AS entitas,
					heyxxmd.nama AS departemen,
					hesxxmh.nama AS status_karyawan,

					DATE_FORMAT(b.tanggal_masuk, "%d %b %Y") tanggal_masuk,
					DATE_FORMAT(b.tanggal_akhir_kontrak, "%d %b %Y") tanggal_akhir_kontrak,
					DATE_FORMAT(b.tanggal_keluar, "%d %b %Y") tanggal_keluar,
					CASE
						WHEN b.grup_hk = 1 THEN "5HK"
						WHEN b.grup_hk = 2 THEN "6HK"
						ELSE ""
					END AS grup_hk,
					CASE
						WHEN res.kode IS NOT NULL then res.kode
						WHEN kode_ps IS NOT NULL then kode_ps
						ELSE ""
				END AS kode_transaksi,
				
					CASE
						WHEN res.kode IS NOT NULL then "RESIGN"
						WHEN kode_ps IS NOT NULL then "PERUBAHAN STATUS"
						ELSE ""
				END AS keterangan

				FROM hemxxmh a
				JOIN hemjbmh b ON b.id_hemxxmh = a.id
				LEFT JOIN hemdcmh c ON c.id_hemxxmh = a.id
				LEFT JOIN hodxxmh ON hodxxmh.id = b.id_hodxxmh
				LEFT JOIN hosxxmh ON hosxxmh.id = b.id_hosxxmh
				LEFT JOIN hetxxmh ON hetxxmh.id = b.id_hetxxmh
				LEFT JOIN holxxmd holxxmd_2 ON holxxmd_2.id = b.id_holxxmd_2
				LEFT JOIN heyxxmh ON heyxxmh.id = b.id_heyxxmh
				LEFT JOIN heyxxmd ON heyxxmd.id = b.id_heyxxmd
				LEFT JOIN hesxxmh ON hesxxmh.id = b.id_hesxxmh

				LEFT JOIN hesxxtd_resign res ON res.id_hemxxmh = a.id AND res.tanggal_selesai = b.tanggal_keluar
				LEFT JOIN (
					SELECT
						ps.kode kode_ps,
						ps.id_hemxxmh
					FROM hesxxtd ps
					WHERE ps.is_approve = 1
					GROUP BY ps.id_hemxxmh
				) per ON per.id_hemxxmh = a.id
				WHERE 1
				AND b.tanggal_keluar >= :start_date
				AND b.tanggal_keluar < :end_date
	' . $where);
	$rs_data_sql = $qs_data_sql->fetchAll();
	
	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"resign" => $rs_data_sql  
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