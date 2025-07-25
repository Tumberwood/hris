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
		->exec('WITH history AS (
					SELECT *
					FROM (
						SELECT hemjbrd.*,
							ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_awal DESC) AS row_num
						FROM hemjbrd
						INNER JOIN hemxxmh ON hemxxmh.id = hemjbrd.id_hemxxmh
						WHERE tanggal_awal <= :end_date AND hemxxmh.is_active = 1
					) AS sub
					WHERE row_num = 1
				)
				SELECT
					a.id,
					a.id_hemxxmh,
					b.kode AS nik,
					b.nama,
					DAYNAME(a.tanggal) hari,
					DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,
					a.break_in awal,
					a.break_out akhir,
					tanggaljam_awal_istirahat,
					tanggaljam_akhir_istirahat,
					is_makan,
					makan,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					a.st_jadwal,
					DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i" ) AS masuk,
					DATE_FORMAT(a.break_in, "%d %b %Y %H:%i" ) break_in,
					DATE_FORMAT(a.break_out, "%d %b %Y %H:%i" ) break_out,
					
					DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i" ) AS pulang,
				
					TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,
				
					CASE
						WHEN jumlah_grup = 2 AND TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30 THEN "4 Grup, Istirahat > 30 Menit"
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 60 THEN "Istirahat > 60 Menit"
						-- WHEN id_hodxxmh = 9 AND st_jadwal LIKE "%06:00%" THEN "QC"
						
						-- Yang break_in atau break_out di luar rentang istirahat
						WHEN g.jam_awal_istirahat <> "00:00:00" AND (
							a.break_in NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						) THEN "Istirahat di luar jam reguler"
			
						WHEN DAYNAME(a.tanggal) = "Friday" AND st_jadwal LIKE "%PAGI%" AND gender = "Laki-laki" THEN "AMAN"
						ELSE "AMAN"
					END AS kategori,
					a.durasi_lembur_total_jam,
					a.pot_ti,
					a.durasi_lembur_final
				
				FROM htsprrd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
				
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
					LEFT JOIN history h ON h.id_hemxxmh = j.id_hemxxmh
				) c ON c.id_hemxxmh = b.id AND (c.tanggal_masuk IS NULL OR a.tanggal >= c.tanggal_masuk)
				
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2
				INNER JOIN htsxxmh g on g.kode = a.st_jadwal
				INNER JOIN htssctd jad on jad.id_hemxxmh = a.id_hemxxmh AND jad.tanggal = a.tanggal AND jad.is_active = 1
				
				LEFT JOIN (
					SELECT
						b.id id_hemxxmh,
						a.tanggal,
						CONCAT(a.tanggal, " ", a.jam) ceklok,
						DATE_FORMAT(CONCAT(a.tanggal, " ", a.jam), "%d %b %Y %H:%i") makan
					FROM htsprtd a
					LEFT JOIN hemxxmh b ON b.kode_finger = a.kode
					WHERE a.tanggal BETWEEN :start_date AND DATE_ADD(:end_date, INTERVAL 1 DAY)
						AND a.nama IN ("MAKAN", "MAKAN MANUAL")
					GROUP BY b.id, a.tanggal
				) mk ON mk.ceklok BETWEEN a.clock_in AND a.clock_out AND mk.id_hemxxmh = a.id_hemxxmh
				
				WHERE 
					a.tanggal BETWEEN :start_date AND :end_date
					AND a.durasi_lembur_total_jam = 0
					AND a.pot_jam > 0
					'.$where.'
				HAVING kategori <> "AMAN"
				ORDER BY a.tanggal
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>