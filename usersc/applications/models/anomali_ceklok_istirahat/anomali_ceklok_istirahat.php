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
					b.kode nik,
					b.nama,
					pr.st_jadwal,
					pr.break_in,
					pr.break_out,
					c.tanggaljam_awal_istirahat,
					c.tanggaljam_akhir_istirahat,
					
					dep.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					pr.st_jadwal,
					pr.clock_out
										
				FROM htsprrd pr
				JOIN hemxxmh b ON b.id = pr.id_hemxxmh
				JOIN htssctd c ON c.tanggal = pr.tanggal
					AND c.id_hemxxmh = pr.id_hemxxmh
					
				JOIN hemjbmh jb ON jb.id_hemxxmh = pr.id_hemxxmh

				LEFT JOIN hodxxmh dep ON dep.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = pr.id_holxxmd_2
				LEFT JOIN heyxxmh tipe ON tipe.id = jb.id_heyxxmh

				WHERE pr.tanggal BETWEEN :start_date AND :end_date
				AND pr.break_in IS NOT NULL
				AND pr.break_out IS NOT NULL

				AND ( pr.break_in NOT BETWEEN c.tanggaljam_awal_istirahat AND c.tanggaljam_akhir_istirahat)
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>