<?php

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$qs_dashboard = $db
		->raw()
		->exec('WITH komp AS (
				SELECT
					a.kode,
					a.nama,
					IF(c.id_gtxpkmh IS NULL, "PTKP", NULL) AS ptkp,
					IF(b.id_heyxxmh IS NULL, "Tipe", NULL) AS tipe,
					IF(b.id_heyxxmd IS NULL, "Sub Tipe", NULL) AS sub_tipe,
					IF(b.id_hevxxmh IS NULL, "Level", NULL) AS lv,
					IF(b.id_hesxxmh IS NULL, "Status", NULL) AS st,
					IF(c.is_npwp IS NULL, "NPWP", NULL) AS npwp
				FROM hemxxmh AS a
				LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
				LEFT JOIN hemdcmh AS c ON c.id_hemxxmh = a.id
				WHERE a.is_active = 1 AND b.is_checkclock = 1 
					AND (
								c.id_gtxpkmh IS NULL 
								OR b.id_heyxxmd IS NULL 
								OR c.is_npwp IS NULL 
							OR b.id_heyxxmh IS NULL
								OR b.id_hevxxmh IS NULL
								OR b.id_hesxxmh IS NULL
							)
			)
			SELECT
				kode,
				nama,
				CASE 
					WHEN LENGTH(CONCAT_WS(" ", ptkp, tipe, sub_tipe, lv, npwp)) > 1 
					THEN CONCAT_WS(", ", ptkp, tipe, sub_tipe, lv, npwp)
					ELSE CONCAT_WS(" ", ptkp, tipe, sub_tipe, lv, npwp)
				END AS keterangan
			FROM komp;
		
				'
				);
	$rs_dashboard = $qs_dashboard->fetchAll();

	$results = array();

	$results['data'] = !empty($rs_dashboard) ? $rs_dashboard : [];

	echo json_encode($results);
?>