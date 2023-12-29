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
		->exec('WITH RECURSIVE date_range AS (
					SELECT DATE(DATE_FORMAT(CURDATE(), "%Y-%m-01")) AS senjum
					UNION ALL
					SELECT DATE_ADD(senjum, INTERVAL 1 DAY)
					FROM date_range
					WHERE senjum < LAST_DAY(CURDATE())
				)
				
				SELECT
					CONCAT(hem.kode, " - ", hem.nama) AS peg,
					a.id_hemxxmh,
					DATE_FORMAT(senjum, "%d %b %Y") AS tanggal,
					jb.tanggal_masuk,
					if(jb.tanggal_masuk >= senjum, "Tercatat", 
						CASE
							WHEN htssctd.tanggal = senjum THEN "Tercatat"
							ELSE "No Jadwal"
						END
					) AS status
				FROM (
					SELECT DISTINCT id_hemxxmh
					FROM htssctd
					WHERE tanggal BETWEEN DATE_FORMAT(CURDATE(), "%Y-%m-01") AND LAST_DAY(CURDATE()) AND is_active = 1
				) AS a
				CROSS JOIN date_range
				LEFT JOIN htssctd ON a.id_hemxxmh = htssctd.id_hemxxmh AND htssctd.tanggal = senjum
				LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
				LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = hem.id
				-- Kondisi tanggal masuk kurang atau sama dengan tanggal jadwal yg belum dibaut (senjum) dan tanggal_keluar is null atau tanggal keluar lebih dari tanggal jadwal yang belum dibuat
				WHERE a.id_hemxxmh > 0 AND jb.tanggal_masuk <= senjum AND jb.is_checkclock = 1 AND hem.is_active = 1  AND (jb.tanggal_keluar IS NULL OR jb.tanggal_keluar > senjum)
				GROUP BY senjum, id_hemxxmh
				HAVING STATUS = "No Jadwal"
				ORDER BY id_hemxxmh, senjum
				;
				'
				);
	$rs_dashboard = $qs_dashboard->fetchAll();

	$results = array();

	$results['data'] = !empty($rs_dashboard) ? $rs_dashboard : [];

	echo json_encode($results);
?>