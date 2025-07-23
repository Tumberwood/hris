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
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i" ) AS pulang,
					
					-- Tambahkan durasi istirahat (dalam menit)
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,

					-- Kategori berdasarkan istirahat dan jam kerja
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30 THEN "Istirahat > 30 menit"
						WHEN a.pot_jam > 0 then "Jam Kerja (durasi kerja < 7/8 jam)"
						ELSE "Normal"
					END AS kategori,

					a.durasi_lembur_total_jam,
					a.pot_ti,
					a.durasi_lembur_final

				FROM htsprrd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
				INNER JOIN hemjbmh c ON c.id_hemxxmh = b.id
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2

				WHERE 
					a.tanggal BETWEEN :start_date AND :end_date
					AND a.durasi_lembur_total_jam > 0
					AND a.pot_ti > 0
				' . $where
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>