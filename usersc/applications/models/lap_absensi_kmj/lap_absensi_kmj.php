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
					b.kode AS NIK,
					a.id_hemxxmh,
					b.nama AS Nama,
					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
					-- Sesuaikan "KMJ" jika ada kolom khusus sub tipe di tabel Anda (misal: c.sub_tipe)
					"KMJ" AS `Sub Tipe`,
					CASE 
						WHEN a.st_jadwal LIKE "%PAGI%" THEN "Pagi"
						WHEN a.st_jadwal LIKE "%SORE%" OR a.st_jadwal LIKE "%SIANG%" THEN "Siang"
						WHEN a.st_jadwal LIKE "%MALAM%" THEN "Malam"
						ELSE a.st_jadwal
					END AS Shift,
					DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS `Check In`,
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS `Check Out`,
					ROUND(TIMESTAMPDIFF(MINUTE, a.clock_in, a.clock_out) / 60.0, 2) AS `Durasi (Jam)`,
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, a.clock_in, a.clock_out) / 60.0, 2) > 0 THEN 1
						ELSE 0
					END AS `Count orang`
				FROM htsprrd a
				JOIN hemxxmh b ON b.id = a.id_hemxxmh
				JOIN hemjbmh c ON c.id_hemxxmh = a.id_hemxxmh
				WHERE a.tanggal BETWEEN :start_date AND :end_date
				AND c.id_heyxxmd = 4
				ORDER BY a.tanggal, b.kode
				'
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>