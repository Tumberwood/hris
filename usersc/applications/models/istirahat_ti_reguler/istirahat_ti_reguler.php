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
		->exec('
			SELECT
				a.id,
				a.id_hemxxmh,
				b.kode AS nik,
				b.nama,
				spkl.kode AS kode_spkl,
				d.nama AS dep,
				e.nama AS jab,
				f.nama AS area,
				g.nama AS type,
				DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
				a.st_jadwal,
				DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS masuk,
				DATE_FORMAT(a.break_in, "%d %b %Y %H:%i") AS break_in,
				DATE_FORMAT(a.break_out, "%d %b %Y %H:%i") AS break_out,
				(
					SELECT
						DATE_FORMAT(x.tanggal_jam, "%d %b %Y %H:%i")
					FROM htsprtd x
					LEFT JOIN hemxxmh hx
						ON hx.kode_finger = x.kode
					WHERE hx.id = a.id_hemxxmh
						AND x.nama IN ("MAKAN", "MAKAN MANUAL")
						AND x.tanggal_jam BETWEEN a.clock_in AND a.clock_out
					LIMIT 1
				) AS makan,
				a.is_makan,
				DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS pulang,

				TIMESTAMPDIFF(
					MINUTE,
					a.break_in,
					a.break_out
				) AS durasi_istirahat_menit,

				CASE
					WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 0
						AND IFNULL(a.is_makan, 0) = 1
					THEN "Istirahat + Makan"

					WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30
					THEN "Istirahat > 30 menit"

					WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) <= 30
						AND IFNULL(a.is_makan, 0) = 1
					THEN "Istirahat ≤ 30 + Makan"

					WHEN pot_jam_istirahat > 0 THEN "durasi kerja < 7/8 jam"

					ELSE "Tidak Masuk Kategori"
				END AS kategori,

				a.durasi_lembur_total_jam,
				a.pot_ti,
				bag.nama bagian,
				a.durasi_lembur_final

			FROM htsprrd a

			INNER JOIN hemxxmh b
				ON b.id = a.id_hemxxmh

			LEFT JOIN htoxxrd spkl
				ON spkl.id_hemxxmh = a.id_hemxxmh
				AND spkl.tanggal = a.tanggal

			LEFT JOIN (
				SELECT
					j.id_hemxxmh,
					j.id_holxxmd_2,
					j.id_heyxxmh,
					j.id_hevxxmh,
					j.id_hetxxmh,
					j.id_hosxxmh,
					j.id_hodxxmh,
					j.id_hobxxmh,
					j.id_heyxxmd,
					j.is_checkclock,
					j.tanggal_masuk,
					j.tanggal_keluar,
					IFNULL(j.id_hesxxmh, 0) AS id_hesxxmh,
					IFNULL(j.jumlah_grup, 0) AS jumlah_grup,
					IFNULL(j.grup_hk, 0) AS grup_hk
				FROM hemjbmh j
			) c
				ON c.id_hemxxmh = b.id
				AND (
					c.tanggal_masuk IS NULL
					OR a.tanggal >= c.tanggal_masuk
				)

			INNER JOIN hodxxmh d
				ON d.id = c.id_hodxxmh

			INNER JOIN hetxxmh e
				ON e.id = c.id_hetxxmh

			LEFT JOIN holxxmd_2 f
				ON f.id = a.id_holxxmd_2

			LEFT JOIN heyxxmh g
				ON g.id = c.id_heyxxmh
			LEFT JOIN hobxxmh bag ON bag.id = c.id_hobxxmh

			WHERE
				a.tanggal BETWEEN :start_date AND :end_date
				AND a.pot_jam > 0
				AND c.jumlah_grup = 2

				-- AND a.is_pot_premi <> 1
				-- yang potongan jam karena early, late dsb ini agar tidak masuk

				AND (
					a.is_pot_premi <> 1
					OR a.pot_jam_istirahat > 0
				)
				' . $where . '

			HAVING kategori <> "Tidak Masuk Kategori"

			UNION ALL

			SELECT
				a.id,
				a.id_hemxxmh,
				b.kode AS nik,
				b.nama,
				NULL AS kode_spkl,
				d.nama AS dep,
				e.nama AS jab,
				f.nama AS area,
				g.nama AS type,
				DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
				a.st_jadwal,
				DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS masuk,
				DATE_FORMAT(a.break_in, "%d %b %Y %H:%i") AS break_in,
				DATE_FORMAT(a.break_out, "%d %b %Y %H:%i") AS break_out,
				mk.makan AS makan,
				a.is_makan,
				DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS pulang,

				TIMESTAMPDIFF(
					MINUTE,
					a.break_in,
					a.break_out
				) AS durasi_istirahat_menit,

				CASE
					WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30
					THEN "Istirahat > 30 menit"

					WHEN a.pot_ti > 0
						AND f.id = 1
						AND a.htlxxrh_kode = ""
						AND TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) < 30
					THEN "TI Gedung 3 Tidak Sah"

					WHEN a.pot_ti > 0
						AND f.id = 1
						AND a.htlxxrh_kode = ""
						AND a.break_in IS NOT NULL
					THEN "TI Gedung 3 Tidak Sah"

					WHEN a.pot_jam = 0.5
						AND f.id = 1
						AND a.htlxxrh_kode = ""
						AND a.break_in IS NOT NULL
					THEN "TI Gedung 3 Tidak Sah"

					WHEN a.pot_jam > 0
					THEN "Jam Kerja (durasi kerja < 7/8 jam)"

					ELSE "Normal"
				END AS kategori,

				a.durasi_lembur_total_jam,
				a.pot_ti,
				bag.nama bagian,
				a.durasi_lembur_final

			FROM htsprrd a

			INNER JOIN hemxxmh b
				ON b.id = a.id_hemxxmh

			INNER JOIN hemjbmh c
				ON c.id_hemxxmh = b.id

			INNER JOIN hodxxmh d
				ON d.id = c.id_hodxxmh

			INNER JOIN hetxxmh e
				ON e.id = c.id_hetxxmh

			LEFT JOIN holxxmd_2 f
				ON f.id = a.id_holxxmd_2

			LEFT JOIN heyxxmh g ON g.id = c.id_heyxxmh
			LEFT JOIN hobxxmh bag ON bag.id = c.id_hobxxmh

			LEFT JOIN (
				SELECT
					b.id AS id_hemxxmh,
					a.tanggal,
					CONCAT(a.tanggal, " ", a.jam) AS ceklok,
					DATE_FORMAT(
						CONCAT(a.tanggal, " ", a.jam),
						"%d %b %Y %H:%i"
					) AS makan

				FROM htsprtd a

				LEFT JOIN hemxxmh AS b
					ON b.kode_finger = a.kode

				WHERE
					a.tanggal BETWEEN :start_date
					AND DATE_ADD(:end_date, INTERVAL 1 DAY)
					AND a.nama IN ("MAKAN", "MAKAN MANUAL")

				GROUP BY
					b.id,
					a.tanggal
			) mk
				ON mk.ceklok BETWEEN a.clock_in AND a.clock_out
				AND mk.id_hemxxmh = a.id_hemxxmh

			WHERE
				a.tanggal BETWEEN :start_date AND :end_date
				AND a.durasi_lembur_total_jam > 0
				AND (
					a.pot_ti > 0
					OR a.pot_overtime = 0.5
				)
				' . $where . '

			UNION ALL

			SELECT
				a.id,
				a.id_hemxxmh,
				b.kode AS nik,
				b.nama,
				spkl.kode AS kode_spkl,
				d.nama AS dep,
				e.nama AS jab,
				f.nama AS area,
				hey.nama AS type,
				DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
				a.st_jadwal,
				DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i") AS masuk,
				DATE_FORMAT(a.break_in, "%d %b %Y %H:%i") AS break_in,
				DATE_FORMAT(a.break_out, "%d %b %Y %H:%i") AS break_out,
				mk.makan AS makan,
				a.is_makan,
				DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i") AS pulang,

				TIMESTAMPDIFF(
					MINUTE,
					a.break_in,
					a.break_out
				) AS durasi_istirahat_menit,

				CASE
					/* SEMUA CASE KATEGORI QUERY KEDUA TETAP DI SINI */
				END AS kategori,

				a.durasi_lembur_total_jam,
				a.pot_ti,
				NULL AS bagian,
				a.durasi_lembur_final

			FROM htsprrd a

			INNER JOIN hemxxmh b
				ON b.id = a.id_hemxxmh

			LEFT JOIN htoxxrd spkl
				ON spkl.id_hemxxmh = a.id_hemxxmh
				AND spkl.tanggal = a.tanggal

			INNER JOIN (
				SELECT
					j.id_hemxxmh,
					j.id_holxxmd_2,
					j.id_heyxxmh,
					j.id_hevxxmh,
					j.id_hetxxmh,
					j.id_hosxxmh,
					j.id_hodxxmh,
					j.id_heyxxmd,
					j.is_checkclock,
					j.tanggal_masuk,
					j.tanggal_keluar,
					IFNULL(h.id_hesxxmh, j.id_hesxxmh) AS id_hesxxmh,
					IFNULL(h.jumlah_grup, j.jumlah_grup) AS jumlah_grup,
					IFNULL(h.grup_hk, j.grup_hk) AS grup_hk
				FROM hemjbmh j
				LEFT JOIN history h
					ON h.id_hemxxmh = j.id_hemxxmh
			) c
				ON c.id_hemxxmh = b.id
				AND (
					c.tanggal_masuk IS NULL
					OR a.tanggal >= c.tanggal_masuk
				)

			LEFT JOIN heyxxmd hey
				ON hey.id = c.id_heyxxmd

			LEFT JOIN hodxxmh d
				ON d.id = c.id_hodxxmh

			LEFT JOIN hetxxmh e
				ON e.id = c.id_hetxxmh

			LEFT JOIN holxxmd_2 f
				ON f.id = c.id_holxxmd_2

			LEFT JOIN htsxxmh g
				ON g.kode = a.st_jadwal

			LEFT JOIN htssctd jad
				ON jad.id_hemxxmh = a.id_hemxxmh
				AND jad.tanggal = a.tanggal
				AND jad.is_active = 1

			LEFT JOIN htoxxrd ot
				ON ot.tanggal = a.tanggal
				AND ot.id_hemxxmh = a.id_hemxxmh

			LEFT JOIN (
				SELECT
					b.id AS id_hemxxmh,
					a.tanggal,
					a.nama AS mesin,
					CONCAT(a.tanggal, " ", a.jam) AS ceklok,
					DATE_FORMAT(
						CONCAT(a.tanggal, " ", a.jam),
						"%d %b %Y %H:%i"
					) AS makan,
					CONCAT(a.tanggal, " ", a.jam) AS makan_ymd
				FROM htsprtd a
				LEFT JOIN hemxxmh b
					ON b.kode_finger = a.kode
				WHERE
					a.tanggal BETWEEN :start_date
					AND DATE_ADD(:end_date, INTERVAL 1 DAY)
					AND a.nama IN ("MAKAN", "MAKAN MANUAL")
				GROUP BY
					b.id,
					a.tanggal
			) mk
				ON mk.ceklok BETWEEN a.clock_in AND a.clock_out
				AND mk.id_hemxxmh = a.id_hemxxmh

			WHERE
				a.tanggal BETWEEN :start_date AND :end_date
				AND (
					a.is_pot_premi <> 1
					OR a.pot_jam_istirahat > 0
				)
				'.$where.'

			HAVING
				durasi_istirahat_menit > 0
				AND kategori <> "AMAN"

			ORDER BY
				tanggal
		');

	$rs_htsprrd = $qs_htsprrd->fetchAll();
	
	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>