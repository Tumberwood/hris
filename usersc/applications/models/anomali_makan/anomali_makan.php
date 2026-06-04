<?php
	// tes webhook
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	if ($_POST['id_hemxxmh'] > 0) {
		$where = ' AND a.id_hemxxmh = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	$qs_htsprrd = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					a.id_hemxxmh,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					jb.id_heyxxmh,
					hem.kode nik,
					hem.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					a.st_jadwal,
					a.cek,
					-- b.jam_awal jam_awal_lembur,
					-- b.jam_akhir jam_akhir_lembur,
					DATE_FORMAT(ceklok.tanggal_jam, "%d %b %Y %H:%i:%s") makan,
					"CEKLOK DILUAR RANGE LEMBUR" keterangan
				FROM htsprrd a
				JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = a.id_hemxxmh

				LEFT JOIN hodxxmh d ON d.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2

				JOIN htoxxrd b ON b.tanggal = a.tanggal AND a.id_hemxxmh = b.id_hemxxmh
				LEFT JOIN (
					SELECT
						ck.tanggal_jam,
						ck.id_hemxxmh,
						ck.kode
					FROM htsprtd ck
					WHERE ck.nama IN ("makan", "makan manual")
						AND ck.tanggal BETWEEN :start_date
										AND DATE_ADD(:end_date, INTERVAL 1 DAY)
					GROUP BY
						ck.kode,
						ck.tanggal_jam
				) ceklok
					ON ceklok.kode = hem.kode_finger
					AND ceklok.tanggal_jam BETWEEN
						CONCAT(b.tanggal, " ", b.jam_awal)
					AND
						DATE_ADD(
							CONCAT(
								IF(
									b.jam_awal > b.jam_akhir,
									DATE_ADD(b.tanggal, INTERVAL 1 DAY),
									b.tanggal
								),
								" ",
								b.jam_akhir
							),
							INTERVAL 15 MINUTE
						)
				WHERE 1
					AND a.tanggal BETWEEN :start_date AND :end_date
					AND a.is_makan = 0
					AND a.durasi_lembur_final > 0
					AND ceklok.tanggal_jam IS NOT NULL

				UNION ALL

				SELECT
					a.id_hemxxmh,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					jb.id_heyxxmh,
					hem.kode nik,
					hem.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					a.st_jadwal,
					a.cek,
					DATE_FORMAT(ceklok.tanggal_jam, "%d %b %Y %H:%i:%s") makan,
					"OFF - TIDAK ADA LEMBUR - ADA CEKLOK MAKAN" keterangan
				FROM htsprrd a
				JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = a.id_hemxxmh

				LEFT JOIN hodxxmh d ON d.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2

				LEFT JOIN (
					SELECT
						ck.tanggal_jam,
						ck.id_hemxxmh,
						ck.kode
					FROM htsprtd ck
					WHERE ck.nama IN ("makan", "makan manual")
						AND ck.tanggal BETWEEN :start_date
										AND DATE_ADD(:end_date, INTERVAL 1 DAY)
					GROUP BY
						ck.kode,
						ck.tanggal_jam
				) ceklok
					ON ceklok.kode = hem.kode_finger
					AND ceklok.tanggal_jam BETWEEN
						CONCAT(a.tanggal, " 07:00")
					AND 
						CONCAT(a.tanggal, " 23:59")
				WHERE 1
					AND a.tanggal BETWEEN :start_date AND :end_date
					AND a.is_makan = 0
					AND a.durasi_lembur_final = 0
					AND a.st_jadwal = "OFF"
					AND ceklok.tanggal_jam IS NOT NULL
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>