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
		$where = ' AND jb.id_hemxxmh = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	// $qs_htsprrd = $db
	// 	->raw()
	// 	->bind(':start_date', $start_date)
	// 	->bind(':end_date', $end_date)
	// 	->exec('SELECT
	// 				a.id,
	// 				ELT(DAYOFWEEK(a.tanggal), "Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu") AS hari,
	// 				DATE_FORMAT(a.tanggal, "%d %b %Y") tanggal,
	// 				a.id_hemxxmh,
	// 				sf.kode AS shift,
	// 				kode_finger,
	// 				hem.kode AS nik,
	// 				hem.nama,
	// 				d.nama AS bag,
	// 				hem.asal_non_karyawan AS asal,
	// 				DATE_FORMAT
	// 				(
	// 					(
	// 						SELECT MIN(ceklok.tanggal_jam)
	// 						FROM htsprtd ceklok
	// 						WHERE ceklok.kode = hem.kode_finger
	// 						AND ceklok.tanggal_jam BETWEEN
	// 							a.tanggaljam_awal_t1
	// 							AND a.tanggaljam_awal_t2
	// 					)    
	// 				, "%d %b %Y %H:%i:%s")
	// 				AS clock_in,

	// 				DATE_FORMAT
	// 				(
	// 					(
	// 						SELECT MAX(ceklok.tanggal_jam)
	// 						FROM htsprtd ceklok
	// 						WHERE ceklok.kode = hem.kode_finger
	// 						AND ceklok.tanggal_jam BETWEEN
	// 							a.tanggaljam_akhir_t1
	// 							AND a.tanggaljam_akhir_t2
	// 					)    
	// 				, "%d %b %Y %H:%i:%s")
	// 				AS clock_out

	// 			FROM htssctd a

	// 			JOIN hemxxmh hem ON hem.id = a.id_hemxxmh
	// 			JOIN hemjbmh jb ON jb.id_hemxxmh = hem.id
	// 			JOIN htsxxmh sf ON sf.id = a.id_htsxxmh
	// 			LEFT JOIN hobxxmh d ON d.id = jb.id_hobxxmh
	// 			WHERE jb.is_non_karyawan = 1
	// 			AND a.is_active = 1
	// 			AND a.tanggal BETWEEN :start_date AND :end_date
	// 			' . $where
	// 			);
	// $rs_htsprrd = $qs_htsprrd->fetchAll();

	$qs_htsprrd = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('WITH jadwal AS (
					-- JADWAL NORMAL
					SELECT
						a.id,
						a.tanggal,
						a.id_hemxxmh,
						a.id_htsxxmh,
						a.tanggaljam_awal_t1,
						a.tanggaljam_awal_t2,
						a.tanggaljam_akhir_t1,
						a.tanggaljam_akhir_t2
					FROM htssctd a
					WHERE a.is_active = 1
					AND a.tanggal BETWEEN :start_date AND :end_date

					UNION ALL

					-- ABSENSI HARIAN TANPA JADWAL
					SELECT
						NULL AS id,
						r.tanggal,
						r.id_hemxxmh,
						NULL AS id_htsxxmh,
						NULL AS tanggaljam_awal_t1,
						NULL AS tanggaljam_awal_t2,
						NULL AS tanggaljam_akhir_t1,
						NULL AS tanggaljam_akhir_t2
					FROM htlxxrh r
					WHERE r.jenis = 1
					AND r.is_active = 1
					AND r.tanggal BETWEEN :start_date AND :end_date
					AND NOT EXISTS (
						SELECT 1
						FROM htssctd s
						WHERE s.id_hemxxmh = r.id_hemxxmh
							AND s.tanggal = r.tanggal
							AND s.is_active = 1
					)
					GROUP BY
						r.tanggal,
						r.id_hemxxmh
				)

				SELECT
					a.id,

					ELT(
						DAYOFWEEK(a.tanggal),
						"Minggu", "Senin", "Selasa", "Rabu",
						"Kamis", "Jumat", "Sabtu"
					) AS hari,

					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,

					a.id_hemxxmh,
					sf.kode AS shift,
					hem.kode_finger,
					hem.kode AS nik,
					hem.nama,
					d.nama AS bag,
					hem.asal_non_karyawan AS asal,

					-- ==========================================
					-- CLOCK IN
					-- ==========================================
					DATE_FORMAT(
						(
							SELECT MIN(c.tanggal_jam)
							FROM htsprtd c
							WHERE c.kode = hem.kode_finger
							AND a.tanggaljam_awal_t1 IS NOT NULL
							AND c.tanggal_jam BETWEEN
								a.tanggaljam_awal_t1
								AND a.tanggaljam_awal_t2
						),
						"%d %b %Y %H:%i:%s"
					) AS clock_in,

					-- ==========================================
					-- CLOCK OUT
					-- ==========================================
					DATE_FORMAT(
						(
							SELECT MAX(c.tanggal_jam)
							FROM htsprtd c
							WHERE c.kode = hem.kode_finger
							AND a.tanggaljam_akhir_t1 IS NOT NULL
							AND c.tanggal_jam BETWEEN
								a.tanggaljam_akhir_t1
								AND a.tanggaljam_akhir_t2
						),
						"%d %b %Y %H:%i:%s"
					) AS clock_out,

					-- ==========================================
					-- ABSEN HARIAN
					-- ==========================================
					absen.htlxxrh_kode AS kode_absen,
					absen.is_approve AS approve_absen,
					absen.saldo AS saldo_absen,
					absen.is_potongupah,
					absen.is_potong_premi AS potong_premi_absen,
					absen.htlxxmh_kode AS jenis_absen,
					absen.htlgrmh_kode AS grup_absen,

					-- ==========================================
					-- IZIN IN
					-- ==========================================
					izin.izin_in AS tanggal_jam_izin_in,
					izin.jam_awal_in,
					izin.kode_izin_in,
					izin.kode_grup_izin_in,
					izin.approve_izin_in,
					izin.is_potong_gaji_in,
					izin.is_potong_premi_in,

					-- ==========================================
					-- IZIN OUT
					-- ==========================================
					izin.izin_out AS tanggal_jam_izin_out,
					izin.jam_akhir_out,
					izin.kode_izin_out,
					izin.kode_grup_izin_out,
					izin.approve_izin_out,
					izin.is_potong_gaji_out,
					izin.is_potong_premi_out,

					-- ==========================================
					-- IZIN MID
					-- ==========================================
					izin.izin_mid_awal AS tanggal_jam_izin_awal_mid,
					izin.izin_mid_akhir AS tanggal_jam_izin_akhir_mid,
					izin.jam_awal_mid,
					izin.jam_akhir_mid,
					izin.kode_izin_mid,
					izin.kode_grup_izin_mid,
					izin.approve_izin_mid,
					izin.is_potong_gaji_mid,
					izin.is_potong_premi_mid

				FROM jadwal a

				JOIN hemxxmh hem
					ON hem.id = a.id_hemxxmh

				JOIN hemjbmh jb
					ON jb.id_hemxxmh = hem.id

				LEFT JOIN htsxxmh sf
					ON sf.id = a.id_htsxxmh

				LEFT JOIN hobxxmh d
					ON d.id = jb.id_hobxxmh

				-- ==========================================
				-- ABSEN HARIAN
				-- ==========================================
				LEFT JOIN (
					SELECT
						r.tanggal,
						r.kode AS htlxxrh_kode,
						r.id_hemxxmh,
						r.is_approve,
						r.saldo,
						m.is_potongupah,
						m.is_potong_premi,
						m.kode AS htlxxmh_kode,
						g.kode AS htlgrmh_kode

					FROM htlxxrh r

					LEFT JOIN htlxxmh m
						ON m.id = r.id_htlxxmh

					LEFT JOIN htlgrmh g
						ON g.id = m.id_htlgrmh

					WHERE r.jenis = 1
					AND r.is_active = 1

				) AS absen
					ON absen.id_hemxxmh = a.id_hemxxmh
				AND absen.tanggal = a.tanggal

				-- ==========================================
				-- IZIN IN / OUT / MID
				-- ==========================================
				LEFT JOIN (
					SELECT
						r.id_hemxxmh,
						r.tanggal,

						-- IZIN IN: ID 1 DAN 5
						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN CONCAT(r.tanggal, " ", r.jam_awal)
						END) AS izin_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN r.jam_awal
						END) AS jam_awal_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN r.kode
						END) AS kode_izin_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN g.kode
						END) AS kode_grup_izin_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN r.is_approve
						END) AS approve_izin_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN p.is_potong_gaji
						END) AS is_potong_gaji_in,

						MAX(CASE
							WHEN r.id_htlxxmh IN (1, 5)
							THEN p.is_potong_premi
						END) AS is_potong_premi_in,

						-- IZIN OUT: ID 2 DAN 6
						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN CONCAT(r.tanggal, " ", r.jam_akhir)
						END) AS izin_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN r.jam_akhir
						END) AS jam_akhir_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN r.kode
						END) AS kode_izin_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN g.kode
						END) AS kode_grup_izin_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN r.is_approve
						END) AS approve_izin_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN p.is_potong_gaji
						END) AS is_potong_gaji_out,

						MAX(CASE
							WHEN r.id_htlxxmh IN (2, 6)
							THEN p.is_potong_premi
						END) AS is_potong_premi_out,

						-- IZIN MID: ID 3 DAN 4
						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN CONCAT(r.tanggal, " ", r.jam_awal)
						END) AS izin_mid_awal,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN CONCAT(r.tanggal, " ", r.jam_akhir)
						END) AS izin_mid_akhir,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN r.jam_awal
						END) AS jam_awal_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN r.jam_akhir
						END) AS jam_akhir_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN r.kode
						END) AS kode_izin_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN g.kode
						END) AS kode_grup_izin_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN r.is_approve
						END) AS approve_izin_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN p.is_potong_gaji
						END) AS is_potong_gaji_mid,

						MAX(CASE
							WHEN r.id_htlxxmh IN (3, 4)
							THEN p.is_potong_premi
						END) AS is_potong_premi_mid

					FROM htlxxrh r

					INNER JOIN htpxxmh p
						ON p.id = r.id_htlxxmh

					LEFT JOIN htlgrmh g
						ON g.id = p.id_htlgrmh

					WHERE r.is_active = 1
					AND r.jenis = 2
					AND r.id_htlxxmh IN (1, 2, 3, 4, 5, 6)

					GROUP BY
						r.id_hemxxmh,
						r.tanggal

				) AS izin
					ON izin.id_hemxxmh = a.id_hemxxmh
				AND izin.tanggal = a.tanggal

				WHERE jb.is_non_karyawan = 1
				AND a.tanggal BETWEEN :start_date AND :end_date
				' . $where . '

				ORDER BY
					a.tanggal,
					hem.nama

				'
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>