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
					peg.kode nik,
					peg.nama nama,
					a.tanggal,
					a.st_jadwal,
					a.break_in,
					a.break_out,
					a.jam_makan,
					(
						SELECT DISTINCT
							ck.nama
						FROM htsprtd ck
						WHERE ck.tanggal = a.tanggal
						AND ck.kode = peg.kode_finger
						AND ck.tanggal_jam = a.break_in
						GROUP BY ck.tanggal, ck.kode
					) mesin_break_in,
					(
						SELECT DISTINCT
							ck.nama
						FROM htsprtd ck
						WHERE ck.tanggal = a.tanggal
						AND ck.kode = peg.kode_finger
						AND ck.tanggal_jam = a.break_out
						GROUP BY ck.tanggal, ck.kode
					) mesin_break_out,
					(
						SELECT DISTINCT
							ck.nama
						FROM htsprtd ck
						WHERE ck.tanggal = a.tanggal
						AND ck.kode = peg.kode_finger
						AND ck.tanggal_jam = a.jam_makan
						GROUP BY ck.tanggal, ck.kode
					) mesin_jam_makan,
					CASE 
						WHEN jam_makan BETWEEN break_in AND break_out THEN 1
						ELSE 0
					END AS is_anomali,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					sub.nama AS sub_tipe,
					st.nama AS status_peg
				FROM htsprrd a
				JOIN hemxxmh peg ON peg.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = a.id_hemxxmh

				LEFT JOIN hodxxmh d ON d.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2

				LEFT JOIN heyxxmd sub ON sub.id = jb.id_heyxxmd
				LEFT JOIN hesxxmh st ON st.id = jb.id_hesxxmh
								
				WHERE 1
				AND a.break_in IS NOT NULL
				AND a.break_out IS NOT NULL
				AND a.jam_makan IS NOT NULL
				AND a.tanggal BETWEEN :start_date AND :end_date

				AND a.break_in <> a.jam_makan
				AND a.break_out <> a.jam_makan

				HAVING mesin_break_in IN ("ISTIRAHAT", "OS", "PMI")
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>