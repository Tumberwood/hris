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
					a.id,
					a.id_hemxxmh,
					b.kode AS nik,
					b.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
					a.st_jadwal,
					DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i" ) AS masuk,
					DATE_FORMAT(a.break_in, "%d %b %Y %H:%i" ) break_in,
					DATE_FORMAT(a.break_out, "%d %b %Y %H:%i" ) break_out,
					is_makan,
					makan,
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i" ) AS pulang,
				
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,
					g.jam_awal_istirahat,
					g.jam_akhir_istirahat,
					-- TIME(break_in),
					-- TIME(break_out),
					-- TIME(jam_awal_istirahat),
					-- TIME(jam_akhir_istirahat),
					DAYNAME(a.tanggal) hari,
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 60 THEN "Istirahat > 1 jam" 
						WHEN 
							(TIME(break_in) NOT BETWEEN TIME(g.jam_awal_istirahat) AND TIME(g.jam_akhir_istirahat))
							AND
							(TIME(break_out) NOT BETWEEN TIME(g.jam_awal_istirahat) AND TIME(g.jam_akhir_istirahat))
						THEN "Istirahat di luar jam reguler"
						ELSE "Tidak Masuk Kategori"
					END AS kategori,
					a.pot_jam,
				
					a.durasi_lembur_total_jam,
					a.pot_overtime,
					a.pot_ti,
					a.durasi_lembur_final
				
				FROM htsprrd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
				INNER JOIN hemjbmh c on c.id_hemxxmh = a.id_hemxxmh
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2
				INNER JOIN htsxxmh g on g.kode = a.st_jadwal
				
				LEFT JOIN (
					SELECT
						b.id id_hemxxmh,
						a.tanggal,
						CONCAT(a.tanggal, " ", a.jam) ceklok,
						DATE_FORMAT(CONCAT(a.tanggal, " ", a.jam), "%d %b %Y %H:%i") makan
					FROM htsprtd a
					LEFT JOIN hemxxmh b ON b.kode_finger = a.kode
					WHERE a.tanggal BETWEEN :start_date AND DATE_ADD(:end_date, INTERVAL 1 DAY)
						AND a.nama IN ("MAKAN", "MAKAN MANUAL")
					GROUP BY b.id, a.tanggal
				) mk ON mk.ceklok BETWEEN a.clock_in AND a.clock_out AND mk.id_hemxxmh = a.id_hemxxmh
				WHERE 
				a.tanggal BETWEEN :start_date AND :end_date
				AND a.durasi_lembur_total_jam = 0
				AND (
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 60 AND DAYNAME(a.tanggal) <> "Friday"
					OR (
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 0
					AND g.jam_awal_istirahat <> "00:00:00" AND
					TIME(a.break_in) NOT BETWEEN TIME(g.jam_awal_istirahat) AND TIME(g.jam_akhir_istirahat)
					AND
					TIME(a.break_out) NOT BETWEEN TIME(g.jam_awal_istirahat) AND TIME(g.jam_akhir_istirahat)
					)
				)
				'.$where.'
				ORDER BY a.tanggal
		
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>