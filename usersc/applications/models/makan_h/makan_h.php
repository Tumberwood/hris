<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$qs_makan_all = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec(' SELECT
					a.id,
					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
					SUM(a.jumlah_ceklok_s1) AS shift1,
					SUM(a.jumlah_ceklok_s2) AS shift2,
					SUM(a.jumlah_ceklok_s3) AS shift3,
					SUM(a.subtotal_s1) AS total_s1,
					SUM(a.subtotal_s2) AS total_s2,
					SUM(a.subtotal_s3) AS total_s3,
					SUM(a.subtotal_all) AS subtotal_all
				FROM makan_h AS a
				WHERE a.tanggal BETWEEN :start_date AND :end_date
				GROUP BY a.tanggal
				'
				);
	$rs_makan_all = $qs_makan_all->fetchAll();
	
	echo json_encode($rs_makan_all);
?>