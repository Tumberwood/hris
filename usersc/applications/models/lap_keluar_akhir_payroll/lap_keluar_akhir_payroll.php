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
	
	
	$periode_payroll = $_POST['periode_payroll'];

	$qs_htsprrd = $db
		->raw()
		->bind(':periode_payroll', $periode_payroll)
		->exec('SELECT
					a.id,
					a.kode AS nik,
					a.nama,
					DATE_FORMAT(b.tanggal_masuk, "%d %b %Y") AS tanggal_masuk,
					DATE_FORMAT(b.tanggal_keluar, "%d %b %Y") AS tanggal_keluar
				FROM hemxxmh a
				JOIN hemjbmh b
					ON b.id_hemxxmh = a.id
				WHERE b.tanggal_keluar IN (
					SELECT
					-- p.tanggal_awal - INTERVAL 1 DAY
					p.tanggal_awal
					FROM periode_payroll p
					WHERE p.is_active = 1
					AND p.id = :periode_payroll
				)
				AND b.id_heyxxmd = 1
				ORDER BY b.tanggal_keluar DESC
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>