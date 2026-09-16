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
	
	
	// $start_date = $_POST['start_date'];
	// $end_date = $_POST['end_date'];

	if ($_POST['id_hemxxmh'] > 0) {
		$where = ' AND a.id_hemxxmh = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	$qs_htsprrd = $db
		->raw()
		// ->bind(':start_date', $start_date)
		// ->bind(':end_date', $end_date)
		->exec('SELECT
					a.kode AS nik,
					a.nama,
					DATE_FORMAT(b.tanggal_masuk, "%d %b %Y") AS tanggal_masuk,
					DATE_FORMAT(b.tanggal_akhir_kontrak, "%d %b %Y") AS tanggal_akhir_kontrak,
					DATE_FORMAT(b.tanggal_keluar, "%d %b %Y") AS tanggal_keluar,
					
					c.kode kode_kontrak,
					
					hey.nama tipe,
					sub.nama sub_tipe,
					st.nama stat,
					dep.nama dep,
					jab.nama jab,
					ar.nama are
					
				FROM hemxxmh a
				JOIN hemjbmh b ON b.id_hemxxmh = a.id
				LEFT JOIN hesxxtd c ON c.id_hemxxmh = a.id

				LEFT JOIN heyxxmh hey ON hey.id = b.id_heyxxmh
				LEFT JOIN heyxxmd sub ON sub.id = b.id_heyxxmd
				LEFT JOIN hesxxmh st ON st.id = b.id_hesxxmh
				LEFT JOIN hodxxmh dep ON dep.id = b.id_hodxxmh
				LEFT JOIN hetxxmh jab ON jab.id = b.id_hetxxmh
				LEFT JOIN holxxmd_2 ar ON ar.id = b.id_holxxmd_2

				WHERE 1
					AND c.kode IS NULL
					AND (b.tanggal_keluar IS NULL OR b.tanggal_keluar >= CURDATE())
					AND b.tanggal_akhir_kontrak BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 2 MONTH);
				' . $where
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>