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
		$where = ' AND jb.id_hemxxmh = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	$qs_htsprrd = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('SELECT
					a.id,
					ELT(DAYOFWEEK(a.tanggal), "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu") AS hari,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					a.id_hemxxmh,
					sf.kode AS shift,
					kode_finger,
					hem.kode AS nik,
					hem.nama,
					d.nama AS bag,
					hem.asal_non_karyawan AS asal,
					DATE_FORMAT
					(
						(
							SELECT MIN(ceklok.tanggal_jam)
							FROM htsprtd ceklok
							WHERE ceklok.kode = hem.kode_finger
							AND ceklok.tanggal_jam BETWEEN
								a.tanggaljam_awal_t1
								AND a.tanggaljam_awal_t2
						)    
					, "%d %b %Y %H:%i:%s")
					AS clock_in,

					DATE_FORMAT
					(
						(
							SELECT MAX(ceklok.tanggal_jam)
							FROM htsprtd ceklok
							WHERE ceklok.kode = hem.kode_finger
							AND ceklok.tanggal_jam BETWEEN
								a.tanggaljam_akhir_t1
								AND a.tanggaljam_akhir_t2
						)    
					, "%d %b %Y %H:%i:%s")
					AS clock_out

				FROM htssctd a

				JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = hem.id
				JOIN htsxxmh sf ON sf.id = a.id_htsxxmh
				LEFT JOIN hobxxmh d ON d.id = jb.id_hobxxmh
				WHERE jb.is_non_karyawan = 1
				AND a.is_active = 1
				AND a.tanggal BETWEEN :start_date AND :end_date
				' . $where
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>