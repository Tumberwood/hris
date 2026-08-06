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
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					jb.id_heyxxmh,
					hem.kode nik,
					hem.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					sub.nama AS sub_tipe,
					st.nama AS status_peg,
					a.st_jadwal,
					a.durasi_lembur_total_jam,
					a.durasi_lembur_final,
					DATE_FORMAT(a.break_in, "%d %b %Y %H:%i") break_in,
					DATE_FORMAT(a.break_out, "%d %b %Y %H:%i") break_out,
					DATE_FORMAT(a.jam_makan, "%d %b %Y %H:%i") jam_makan,
					CONCAT(spkl.jam_awal, " - ", spkl.jam_akhir) jam_lembur,
					CASE spkl.is_istirahat
					    WHEN 1 THEN "Ya"
					    WHEN 2 THEN "TI"
					    WHEN 3 THEN "Istirahat 2x"
					    WHEN 0 THEN "Tidak"
					    ELSE "-"
					END AS is_istirahat
				FROM htsprrd a
				JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = a.id_hemxxmh

				LEFT JOIN hodxxmh d ON d.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2
				
				LEFT JOIN heyxxmd sub ON sub.id = jb.id_heyxxmd
				LEFT JOIN hesxxmh st ON st.id = jb.id_hesxxmh
				
				LEFT JOIN htoxxrd spkl ON spkl.id_hemxxmh = a.id_hemxxmh AND spkl.tanggal = a.tanggal
				WHERE 1
				AND a.tanggal BETWEEN :start_date AND :end_date
				AND a.durasi_lembur_total_jam > 0
				AND a.break_in IS NOT NULL
				AND a.st_jadwal = "OFF"
				AND spkl.is_istirahat <> 2
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>