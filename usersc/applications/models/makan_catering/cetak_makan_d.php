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
						x.tanggal,

						/* =========================
						SHIFT 1 - PAGI
						========================= */
						SUM(
							CASE
								WHEN x.shift = 1
									AND x.sub = "KARYAWAN"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift1_kary,

						SUM(
							CASE
								WHEN x.shift = 1
									AND x.sub = "STAFF"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift1_staff,

						/* =========================
						SHIFT 2 - SIANG / SORE
						========================= */
						SUM(
							CASE
								WHEN x.shift = 2
									AND x.sub = "KARYAWAN"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift2_kary,

						SUM(
							CASE
								WHEN x.shift = 2
									AND x.sub = "STAFF"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift2_staff,

						/* =========================
						SHIFT 3 - MALAM
						========================= */
						SUM(
							CASE
								WHEN x.shift = 3
									AND x.sub = "KARYAWAN"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift3_kary,

						SUM(
							CASE
								WHEN x.shift = 3
									AND x.sub = "STAFF"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS shift3_staff,

						/* =========================
						TOTAL KARYAWAN
						========================= */
						SUM(
							CASE
								WHEN x.sub = "KARYAWAN"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS total_kary,

						/* =========================
						TOTAL STAFF
						========================= */
						SUM(
							CASE
								WHEN x.sub = "STAFF"
									AND x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS total_staff,

						/* =========================
						GRAND TOTAL
						========================= */
						SUM(
							CASE
								WHEN x.is_makan = 1
								THEN 1
								ELSE 0
							END
						) AS grand_total

					FROM
					(
						SELECT
							b.kode,
							b.nama,
							a.tanggal,
							d.nama AS sub,
							a.st_jadwal,
							a.is_makan,
							a.jam_makan,
							b.is_pot_makan,

							/* =========================
							PENENTUAN SHIFT
							========================= */
							CASE
								WHEN UPPER(a.st_jadwal) LIKE "PAGI%"
									THEN 1

								WHEN UPPER(a.st_jadwal) LIKE "SIANG%"
								OR UPPER(a.st_jadwal) LIKE "SORE%"
									THEN 2

								WHEN UPPER(a.st_jadwal) LIKE "MALAM%"
									THEN 3

								ELSE NULL
							END AS shift

						FROM htsprrd a

						JOIN hemxxmh b
							ON b.id = a.id_hemxxmh

						JOIN hemjbmh c
							ON c.id_hemxxmh = a.id_hemxxmh

						LEFT JOIN heyxxmd d
							ON d.id = c.id_hesxxmh

						WHERE 1 = 1

							AND a.tanggal BETWEEN :start_date AND :end_date

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