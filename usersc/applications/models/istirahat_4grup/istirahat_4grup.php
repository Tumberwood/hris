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
					a.id,
					a.id_hemxxmh,
					b.kode AS nik,
					b.nama,
					spkl.kode AS kode_spkl,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
					a.st_jadwal,
					DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i" ) AS masuk,
					DATE_FORMAT(a.break_in, "%d %b %Y %H:%i" ) break_in,
					DATE_FORMAT(a.break_out, "%d %b %Y %H:%i" ) break_out,
					is_makan,
					(
						SELECT
							DATE_FORMAT(x.tanggal_jam, "%d %b %Y %H:%i")
						FROM htsprtd x
						LEFT JOIN hemxxmh hx ON hx.kode_finger = x.kode
						WHERE hx.id = a.id_hemxxmh
						AND x.nama IN ("MAKAN", "MAKAN MANUAL")
						AND x.tanggal_jam BETWEEN a.clock_in AND a.clock_out
						LIMIT 1
					) AS makan,
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i" ) AS pulang,
				
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,
				
					CASE
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 0 AND IFNULL(is_makan, 0) = 1 THEN "Istirahat + Makan"
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30 THEN "Istirahat > 30 menit"
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) <= 30 AND IFNULL(is_makan, 0) = 1 THEN "Istirahat ≤ 30 + Makan"
						ELSE "Tidak Masuk Kategori"
					END AS kategori,
					a.durasi_lembur_total_jam,
					a.pot_ti,
					a.durasi_lembur_final
				
				FROM htsprrd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
				LEFT JOIN htoxxrd spkl on spkl.id_hemxxmh = a.id_hemxxmh and spkl.tanggal = a.tanggal
				
				LEFT JOIN (
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
						IFNULL(j.id_hesxxmh, 0) AS id_hesxxmh,
						IFNULL(j.jumlah_grup, 0) AS jumlah_grup,
						IFNULL(j.grup_hk, 0) AS grup_hk
					FROM hemjbmh j
				) c ON c.id_hemxxmh = b.id AND (c.tanggal_masuk IS NULL OR a.tanggal >= c.tanggal_masuk)
				
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = a.id_holxxmd_2
				
				WHERE 
					a.tanggal BETWEEN :start_date AND :end_date
					AND a.pot_jam > 0
					AND jumlah_grup = 2
					-- AND a.is_pot_premi <> 1 -- yang potongan jam karena early, late dsb ini agar tidak masuk
					AND (a.is_pot_premi <> 1 OR a.pot_jam_istirahat > 0)
					AND a.htlxxrh_kode = ""
					'. $where .'
				HAVING (
				durasi_istirahat_menit > 30 
				OR 
				(durasi_istirahat_menit BETWEEN 1 AND 30 AND IFNULL(is_makan, 0) = 1)
				)
				ORDER BY a.tanggal
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>