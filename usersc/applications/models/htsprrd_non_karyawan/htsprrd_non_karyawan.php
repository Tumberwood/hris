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
		->exec('SELECT DISTINCT
					a.id,
					hem.id AS id_hemxxmh,
					hem.kode nik,
					hem.nama,
					kode_finger,
					d.nama AS bag,
					hem.asal_non_karyawan AS asal,
					DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
					DATE_FORMAT(MIN(a.tanggal_jam), "%d %b %Y %H:%i:%s") AS clock_in,
					DATE_FORMAT(MAX(a.tanggal_jam), "%d %b %Y %H:%i:%s") AS clock_out
				FROM htsprtd a
				JOIN hemxxmh hem ON hem.kode_finger = a.kode
				JOIN hemjbmh jb ON jb.id_hemxxmh = hem.id
				LEFT JOIN hobxxmh d ON d.id = jb.id_hobxxmh

				WHERE 1
				AND a.tanggal BETWEEN :start_date AND :end_date
				AND jb.is_non_karyawan = 1
				AND a.nama IN ("PMI")
				GROUP BY a.tanggal, hem.id
				' . $where
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>