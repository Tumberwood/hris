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
					jadwal.id,
					DATE_FORMAT(jadwal.tanggal, "%d %b %Y") tanggal,
					DAYNAME(jadwal.tanggal) hari,
					b.kode nik,
					b.nama,
					shift.kode shift,
					pr.pot_jam_istirahat,
					ij.keterangan ijin,
					jadwal.id_hemxxmh,
					
					dep.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					pr.st_jadwal,
					
					COUNT(
						DISTINCT
						CASE
							-- 🔹 PAKAI RANGE OVERRIDE (htoXXrd)
							WHEN d.id IS NOT NULL
								AND jadwal.id_htsxxmh = 1
								AND c.tanggal_jam BETWEEN
									CONCAT(d.tanggal, " ", d.jam_awal)
									AND CONCAT(
										IF(d.jam_awal > d.jam_akhir,
											DATE_ADD(d.tanggal, INTERVAL 1 DAY),
											d.tanggal
										),
										" ",
										d.jam_akhir
									)
								AND (
									-- 🔹 Istirahat Gedung 3
									(
										jadwal.tanggal > "2025-07-27" 
										AND jb.id_holxxmd_2 = 1 

										AND (
											-- Filter jika gedung 3 dan 4 Grup
											(
												jumlah_grup = 2 
												AND c.nama NOT IN ("Makan Manual", "PMI-Gedung-3","OS-Gedung-3")
											)
											OR jumlah_grup <> 2
										)

										AND (
											-- 1. Gedung 3 selalu lolos
											c.nama IN ("PMI-Gedung-3","OS-Gedung-3")

											OR (

												-- 2. selain gedung 3 (OS, PMI, dll)
												c.nama NOT IN ("PMI-Gedung-3","OS-Gedung-3")

												-- hanya kalau tidak ada pasangan gedung 3 di waktu yg sama
												AND NOT EXISTS (
													SELECT 1
													FROM htsprtd c2
													WHERE c2.kode = c.kode
													AND c2.tanggal_jam = c.tanggal_jam
													AND c2.nama IN ("PMI-Gedung-3","OS-Gedung-3")
												)
											)
										)
									)

									-- 🔹 Istirahat Selain Gedung 3
									OR (
										jadwal.tanggal > "2025-07-27"
										AND jb.id_holxxmd_2 <> 1 
										AND c.nama IN ("os","out","staff","PMI","PMI-Gedung-3","OS-Gedung-3","istirahat","istirahat manual")
									)

									OR (
										jadwal.tanggal BETWEEN "2025-04-14" AND "2025-07-27"
										AND c.nama IN ("os","out","staff","PMI","PMI-Gedung-3","OS-Gedung-3","istirahat","istirahat manual","makan")
									)

									OR (
										jadwal.tanggal < "2025-04-14"
										AND c.nama IN ("istirahat","istirahat manual","os","out","staff","PMI","makan")
									)
								)
							THEN CONCAT(c.tanggal_jam,"|",c.nama)

							-- 🔹 DEFAULT RANGE ISTIRAHAT (jadwal)
							WHEN (d.id IS NULL OR is_istirahat = 2)
								AND ij.keterangan IS NULL -- JIKA ADA IZIN DI JAM ISTIRAHAT
								AND c.tanggal_jam BETWEEN
									jadwal.tanggaljam_awal_istirahat
									AND DATE_ADD(jadwal.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
								AND (
									-- 🔹 Istirahat Gedung 3
									(
										jadwal.tanggal > "2025-07-27" 
										AND jb.id_holxxmd_2 = 1 

										AND (
											-- Filter jika gedung 3 dan 4 Grup
											(
												jumlah_grup = 2 
												AND c.nama NOT IN ("Makan Manual", "PMI-Gedung-3","OS-Gedung-3")
											)
											OR jumlah_grup <> 2
										)

										AND (
											-- 1. Gedung 3 selalu lolos
											c.nama IN ("PMI-Gedung-3","OS-Gedung-3")

											OR (

												-- 2. selain gedung 3 (OS, PMI, dll)
												c.nama NOT IN ("PMI-Gedung-3","OS-Gedung-3")

												-- hanya kalau tidak ada pasangan gedung 3 di waktu yg sama
												AND NOT EXISTS (
													SELECT 1
													FROM htsprtd c2
													WHERE c2.kode = c.kode
													AND c2.tanggal_jam = c.tanggal_jam
													AND c2.nama IN ("PMI-Gedung-3","OS-Gedung-3")
												)
											)
										)
									)

									-- 🔹 Istirahat Selain Gedung 3
									OR (
										jadwal.tanggal > "2025-07-27"
										AND jb.id_holxxmd_2 <> 1 
										AND c.nama IN ("os","out","staff","PMI","PMI-Gedung-3","OS-Gedung-3","istirahat","istirahat manual")
									)

									OR (
										jadwal.tanggal BETWEEN "2025-04-14" AND "2025-07-27"
										AND c.nama IN ("os","out","staff","PMI","PMI-Gedung-3","OS-Gedung-3","istirahat","istirahat manual","makan")
									)

									OR (
										jadwal.tanggal < "2025-04-14"
										AND c.nama IN ("istirahat","istirahat manual","os","out","staff","PMI","makan")
									)
								)
							THEN CONCAT(c.tanggal_jam,"|",c.nama)
						END
					) AS count_break

				FROM htssctd AS jadwal
				INNER JOIN hemxxmh AS b ON b.id = jadwal.id_hemxxmh AND b.is_active = 1
				INNER JOIN hemjbmh jb on jb.id_hemxxmh = b.id

				LEFT JOIN htoxxrd d ON d.id_hemxxmh = jadwal.id_hemxxmh AND d.tanggal = jadwal.tanggal
				LEFT JOIN htsprtd c ON c.kode = b.kode_finger
				AND c.tanggal_jam >= IF(
					d.id_hemxxmh IS NOT NULL AND jadwal.id_htsxxmh = 1,
					CONCAT(d.tanggal, " ", d.jam_awal),
					jadwal.tanggaljam_awal_t1
				)

				AND c.tanggal_jam <= IF(
					d.id_hemxxmh IS NOT NULL AND jadwal.id_htsxxmh = 1,
					CONCAT(
						IF(
							d.jam_awal > d.jam_akhir,
							DATE_ADD(d.tanggal, INTERVAL 1 DAY),
							d.tanggal
						),
						" ",
						d.jam_akhir
					),
					DATE_ADD(jadwal.tanggaljam_akhir_t2, INTERVAL 1 DAY)
				)

				LEFT JOIN htsxxmh shift ON shift.id = jadwal.id_htsxxmh

				-- CEK APAKAH ADA IZIN DI JAM ISTIRAHAT
				LEFT JOIN htlxxrh ij ON ij.id_hemxxmh = jadwal.id_hemxxmh
					AND ij.tanggal = jadwal.tanggal
					
					AND ij.jam_awal BETWEEN shift.jam_awal_istirahat 
					AND shift.jam_akhir_istirahat

					AND ij.jam_akhir BETWEEN shift.jam_awal_istirahat 
					AND DATE_ADD(shift.jam_akhir_istirahat, INTERVAL 1 HOUR)
					
				LEFT JOIN htsprrd pr ON pr.tanggal = jadwal.tanggal AND pr.id_hemxxmh = jadwal.id_hemxxmh

				LEFT JOIN hodxxmh dep ON dep.id = jb.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = jb.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = pr.id_holxxmd_2

				WHERE jadwal.is_active = 1
					AND jadwal.tanggal BETWEEN :start_date AND :end_date
					AND pr.pot_jam_istirahat >= 1
				GROUP BY jadwal.id
				HAVING count_break > 2
				ORDER BY jadwal.id
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>