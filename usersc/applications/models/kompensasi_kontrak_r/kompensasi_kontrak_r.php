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
					peg.id,
					peg.kode,
					peg.nama,
					DATE_FORMAT(a.tanggal , "%d %b %Y") AS tanggal,
					a.nominal,
					DATE_FORMAT(jb.tanggal_masuk, "%d %b %Y") AS tanggal_masuk,
					DATE_FORMAT(jb.tanggal_akhir_kontrak, "%d %b %Y") AS tanggal_akhir_kontrak
				FROM hpy_piutang_d a
				JOIN hemxxmh b ON b.id = a.id_hemxxmh
				JOIN hesxxtd c ON c.nik_baru = b.kode
				JOIN hemxxmh peg ON peg.id = c.id_hemxxmh
				JOIN hemjbmh jb ON jb.id_hemxxmh = peg.id
				WHERE 1
				AND a.id_hpcxxmh = 108
				AND a.tanggal BETWEEN :start_date AND :end_date
				ORDER BY a.id DESC
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>