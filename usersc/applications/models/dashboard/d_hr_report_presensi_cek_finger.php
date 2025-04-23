<?php

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	if (isset($_POST['start_date'])){
		$awal		= new Carbon($_POST['start_date']);
	}

	$start_date = $awal->format('Y-m-d');

	$qs_cek_finger = $db
		->raw()
		->bind(':start_date', $start_date)
		->exec('SELECT 
					a.id, 
					b.kode,
					b.nama,
					dep.nama dept,
					jab.nama jabatan,
					area.nama area,
					-- clock_in
					DATE_FORMAT(
						MIN(
						CASE 
								WHEN c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3") 
									AND CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_t1 AND a.tanggaljam_awal_t2
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS ceklok_in,
					
					-- clock_out
					DATE_FORMAT(
						MAX(
						CASE 
								WHEN c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3") 
									AND CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_akhir_t1 AND a.tanggaljam_akhir_t2
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS ceklok_out,
				
					-- break_in_gedung3
					DATE_FORMAT(
						MIN(
						CASE 
								WHEN  CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 2 HOUR)
									AND c.nama IN ("PMI-Gedung-3", "OS-Gedung-3")
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS break_in_gedung3,
					
				
					-- break_out_gedung3
					DATE_FORMAT(
						MAX(
						CASE 
								WHEN  CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 2 HOUR)
									AND c.nama IN ("PMI-Gedung-3", "OS-Gedung-3")
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS break_out_gedung3,
				
					-- break_in_luar_gedung3
					DATE_FORMAT(
						MIN(
						CASE 
								WHEN  CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 2 HOUR)
									AND c.nama NOT IN ("PMI-Gedung-3", "OS-Gedung-3")
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS break_in_luar_gedung3,
					
				
					-- break_out_luar_gedung3
					DATE_FORMAT(
						MAX(
						CASE 
								WHEN  CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 2 HOUR)
									AND c.nama NOT IN ("PMI-Gedung-3", "OS-Gedung-3")
								THEN CONCAT(c.tanggal, " ", c.jam)
								ELSE NULL
							END
						) , "%d %b %Y %H:%i" 
					) AS break_out_luar_gedung3
					
				
				FROM htssctd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
				INNER JOIN (
					SELECT
						*
					FROM htsprtd
					WHERE is_active = 1 AND tanggal BETWEEN :start_date AND DATE_ADD(:start_date , INTERVAL 1 DAY)
				) c ON c.kode = b.kode_finger
				INNER JOIN hemjbmh jb ON jb.id_hemxxmh = b.id
				INNER JOIN hodxxmh dep ON dep.id = jb.id_hodxxmh
				INNER JOIN hetxxmh jab ON jab.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 area ON area.id = jb.id_holxxmd_2
				WHERE a.tanggal = :start_date AND a.is_active = 1
					AND CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_t1 AND a.tanggaljam_akhir_t2
				GROUP BY a.id
				'
				);
	$rs_cek_finger = $qs_cek_finger->fetchAll();

	$results = array();

	$results['data9'] = $rs_cek_finger;
	
	
	$results['columns9'] = [
		['data' => 'kode', 'name' => 'kode'],
		['data' => 'nama', 'name' => 'nama'],
		['data' => 'dept', 'name' => 'dept'],
		['data' => 'jabatan', 'name' => 'jabatan'],
		['data' => 'area', 'name' => 'area'],
		['data' => 'ceklok_in', 'name' => 'ceklok_in'],
		['data' => 'ceklok_out', 'name' => 'ceklok_out'],
		['data' => 'break_in_gedung3', 'name' => 'break_in_gedung3'],
		['data' => 'break_out_gedung3', 'name' => 'break_out_gedung3'],
		['data' => 'break_in_luar_gedung3', 'name' => 'break_in_luar_gedung3'],
		['data' => 'break_out_luar_gedung3', 'name' => 'break_out_luar_gedung3'],
	];
	
	echo json_encode($results);
?>