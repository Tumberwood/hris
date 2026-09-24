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

	if (isset($_POST['id_makan_catering']) && $_POST['id_makan_catering'] > 0) {
		$id_makan_catering = $_POST['id_makan_catering'];
	
		$qs_makan_catering = $db
			->query('select', 'makan_catering' )
			->get([
				'tanggal_awal as start_date',
				'tanggal_akhir as end_date'
			] )
			->where('id', $id_makan_catering )
			->exec();
		$rs_makan_catering = $qs_makan_catering->fetch();
	
		$start_date = $rs_makan_catering['start_date'];
		$end_date = $rs_makan_catering['end_date'];

		$user = $_SESSION['user'];

		$qs_data_sql = $db
			->raw()
			->bind(':start_date', $start_date)
			->bind(':end_date', $end_date)
			->exec('SELECT
						DATE_FORMAT(x.tanggal, "%d %b %Y") AS tanggal,

						/* =========================
						HARGA CATERING
						========================= */
						MAX(
							CASE
								WHEN x.sub = "KARYAWAN"
								THEN x.harga_catering
								ELSE 0
							END
						) AS harga_catering_kary,

						MAX(
							CASE
								WHEN x.sub = "STAFF"
								THEN x.harga_catering
								ELSE 0
							END
						) AS harga_catering_staff,


						/* =========================
						SHIFT 1
						========================= */
						SUM(
							x.shift = 1
							AND x.sub = "KARYAWAN"
							AND x.is_makan = 1
						) AS shift1_kary,

						SUM(
							x.shift = 1
							AND x.sub = "STAFF"
							AND x.is_makan = 1
						) AS shift1_staff,


						/* =========================
						SHIFT 2
						========================= */
						SUM(
							x.shift = 2
							AND x.sub = "KARYAWAN"
							AND x.is_makan = 1
						) AS shift2_kary,

						SUM(
							x.shift = 2
							AND x.sub = "STAFF"
							AND x.is_makan = 1
						) AS shift2_staff,


						/* =========================
						SHIFT 3
						========================= */
						SUM(
							x.shift = 3
							AND x.sub = "KARYAWAN"
							AND x.is_makan = 1
						) AS shift3_kary,

						SUM(
							x.shift = 3
							AND x.sub = "STAFF"
							AND x.is_makan = 1
						) AS shift3_staff,


						/* =========================
						TOTAL KARYAWAN
						========================= */
						SUM(
							x.sub = "KARYAWAN"
							AND x.is_makan = 1
						) AS total_kary,


						/* =========================
						TOTAL STAFF
						========================= */
						SUM(
							x.sub = "STAFF"
							AND x.is_makan = 1
						) AS total_staff,


						/* =========================
						GRAND TOTAL
						========================= */
						SUM(
							x.is_makan = 1
						) AS grand_total


					FROM
					(
						SELECT
							a.tanggal,
							d.nama AS sub,
							a.is_makan,


							/* =========================
							HARGA CATERING
							========================= */
							COALESCE(
								(
									SELECT
										p.nominal
									FROM htpr_hemxxmh p
									WHERE p.id_hpcxxmh = 34
										AND p.id_hemxxmh = b.id
										AND p.tanggal_efektif <= a.tanggal
										AND p.is_active = 1
									ORDER BY
										p.tanggal_efektif DESC
									LIMIT 1
								),
								0
							) AS harga_catering,


							/* =========================
							SHIFT
							========================= */
							CASE
								WHEN a.st_jadwal LIKE "PAGI%"
									THEN 1

								WHEN a.st_jadwal LIKE "SIANG%"
								OR a.st_jadwal LIKE "SORE%"
									THEN 2

								WHEN a.st_jadwal LIKE "MALAM%"
									THEN 3

								ELSE NULL
							END AS shift


						FROM htsprrd a

						INNER JOIN hemxxmh b
							ON b.id = a.id_hemxxmh

						INNER JOIN hemjbmh c
							ON c.id_hemxxmh = a.id_hemxxmh

						INNER JOIN heyxxmd d
							ON d.id = c.id_hesxxmh

						WHERE a.tanggal BETWEEN :start_date AND :end_date

							AND d.id IN (2, 3)

					) x


					GROUP BY
						x.tanggal


					ORDER BY
						x.tanggal
		');
		$rs_data_sql = $qs_data_sql->fetchAll();

	} else {
		$rs_data_sql = [];
	}
	
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