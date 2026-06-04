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
					a.st_jadwal,
					a.pot_hk
				FROM htsprrd a
				JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = a.id_hemxxmh

				LEFT JOIN hodxxmh d ON d.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2
				WHERE 1
					AND a.tanggal BETWEEN :start_date AND :end_date
					AND a.pot_hk > 0
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>