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
						5500 AS harga_catering_kary,
						5500 AS harga_catering_staff,
						5500 AS harga_catering_hl,

						/* =========================
						SHIFT 1
						========================= */
						SUM(
							x.shift = 1
							AND x.sub <> "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift1_kary,

						SUM(
							x.shift = 1
							AND x.sub = "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift1_staff,

						MAX(IFNULL(hl.hl_shift1, 0)) AS shift1_hl,

						/* =========================
						SHIFT 2
						========================= */
						SUM(
							x.shift = 2
							AND x.sub <> "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift2_kary,

						SUM(
							x.shift = 2
							AND x.sub = "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift2_staff,

						MAX(IFNULL(hl.hl_shift2, 0)) AS shift2_hl,

						/* =========================
						SHIFT 3
						========================= */
						SUM(
							x.shift = 3
							AND x.sub <> "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift3_kary,

						SUM(
							x.shift = 3
							AND x.sub = "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS shift3_staff,

						MAX(IFNULL(hl.hl_shift3, 0)) AS shift3_hl,

						/* =========================
						TOTAL KARYAWAN
						========================= */
						SUM(
							x.sub <> "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS total_kary,

						/* =========================
						TOTAL STAFF
						========================= */
						SUM(
							x.sub = "STAFF"
							AND x.jam_makan IS NOT NULL
						) AS total_staff,

						/* =========================
						TOTAL HL
						========================= */
						MAX(
							IFNULL(hl.hl_shift1, 0)
							+ IFNULL(hl.hl_shift2, 0)
							+ IFNULL(hl.hl_shift3, 0)
						) AS total_hl,

						/* =========================
						GRAND TOTAL
						========================= */
						SUM(x.jam_makan IS NOT NULL)
						+ MAX(
							IFNULL(hl.hl_shift1, 0)
							+ IFNULL(hl.hl_shift2, 0)
							+ IFNULL(hl.hl_shift3, 0)
						) AS grand_total

					FROM
					(
						SELECT
							a.tanggal,
							d.nama AS sub,
							a.jam_makan,

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
									OR a.jam_awal_lembur_libur BETWEEN "06:00:00" AND "08:00:00"
									THEN 1

								WHEN a.st_jadwal LIKE "SIANG%"
									OR a.st_jadwal LIKE "SORE%"
									OR a.jam_awal_lembur_libur = "15:00:00"
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
							ON d.id = c.id_heyxxmd

						WHERE a.tanggal BETWEEN :start_date AND :end_date
							-- AND d.id IN (2, 3)

						GROUP BY a.id_hemxxmh, a.tanggal
					) x

					/* =========================
					HL: pivot per tanggal (1 baris per tanggal)
					========================= */
					LEFT JOIN (
						SELECT
							tanggal,
							SUM(CASE WHEN shift = 1 THEN jumlah ELSE 0 END) AS hl_shift1,
							SUM(CASE WHEN shift = 2 THEN jumlah ELSE 0 END) AS hl_shift2,
							SUM(CASE WHEN shift = 3 THEN jumlah ELSE 0 END) AS hl_shift3
						FROM makan_catering_hl_umum
						GROUP BY tanggal
					) hl ON hl.tanggal = x.tanggal

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