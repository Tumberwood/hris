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
						WHEN DAYNAME(a.tanggal) = "Friday" AND a.st_jadwal LIKE "%PAGI%" THEN "AMAN"
						WHEN c.jumlah_grup = 2 AND TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 30 AND IF(mesin = "MAKAN MANUAL", break_in <> makan_ymd, 1) THEN "4 Grup > 30 Menit"
						WHEN TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) > 60 AND IF(mesin = "MAKAN MANUAL", break_in <> makan_ymd, 1) THEN "Istirahat > 60 Menit"
						
                        -- QC SHIFT 1
                        WHEN id_hodxxmh = 9 AND st_jadwal LIKE "%06:00-%" AND 
                        (
							a.break_in BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						)
                        THEN "Aman"

						WHEN id_hodxxmh = 9 AND st_jadwal LIKE "%06:00-%" AND 
                        (
							a.break_in NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						)
                        THEN "QC - Shift 1, Istirahat Reguler Tidak Sesuai"
						
						-- SHIFT 1 ADA LEMBUR TI
						when ot.is_istirahat = 2 AND a.st_jadwal LIKE "%PAGI%" AND  
						(
							a.break_in NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out NOT BETWEEN jad.tanggaljam_awal_istirahat AND DATE_SUB(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						)
						then "Shift 1 Lembur TI, Istirahat TI Tidak Sesuai"
						
						-- SHIFT 1 TIDAK ADA LEMBUR TI
						when ot.id is null AND a.st_jadwal LIKE "%PAGI%" AND  
						(
							a.break_in NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
							OR
							a.break_out NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
						)
						then "Shift 1, Istirahat Reguler Tidak Sesuai"
						
						-- SHIFT 2 ADA LEMBUR TI
						when ot.is_istirahat = 2 AND (a.st_jadwal LIKE "%SIANG%" OR a.st_jadwal LIKE "%SORE%") AND  
						(
							a.break_in NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
							OR
							a.break_out NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
						)
						then "Shift 2 Lembur TI, Istirahat TI Tidak Sesuai"
						
						-- SHIFT 2 TIDAK ADA LEMBUR TI
						when ot.id is null AND (a.st_jadwal LIKE "%SIANG%" OR a.st_jadwal LIKE "%SORE%") AND  
						(
							a.break_in NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 2 HOUR) AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 2 HOUR) AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						)
						then "Shift 2, Istirahat Reguler Tidak Sesuai"
						
						-- SHIFt 3 ADA LEMBUR TI
						when ot.is_istirahat = 2 AND a.st_jadwal LIKE "%MALAM%" AND  
						(
							a.break_in NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
							OR
							a.break_out NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 1 HOUR) AND jad.tanggaljam_akhir_istirahat
						)
						then "Shift 3 Lembur TI, Istirahat TI Tidak Sesuai"
						
						-- SHIFt 3 TIDAK ADA LEMBUR TI
						when ot.id is null AND a.st_jadwal LIKE "%MALAM%" AND  
						(
							a.break_in NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 2 HOUR) AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
							OR
							a.break_out NOT BETWEEN DATE_ADD(jad.tanggaljam_awal_istirahat, INTERVAL 2 HOUR) AND DATE_ADD(jad.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
						)
						then "Shift 3, Istirahat Reguler Tidak Sesuai"
						
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
					hey.nama type,
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
				
				INNER JOIN heyxxmd hey ON hey.id = c.id_heyxxmd
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2
				INNER JOIN htsxxmh g on g.kode = a.st_jadwal
				INNER JOIN htssctd jad on jad.id_hemxxmh = a.id_hemxxmh AND jad.tanggal = a.tanggal AND jad.is_active = 1
				LEFT JOIN htoxxrd ot ON ot.tanggal = a.tanggal AND ot.id_hemxxmh = a.id_hemxxmh
				LEFT JOIN (
					SELECT
						b.id id_hemxxmh,
						a.tanggal,
						a.nama AS mesin,
						CONCAT(a.tanggal, " ", a.jam) AS ceklok,
						DATE_FORMAT(CONCAT(a.tanggal, " ", a.jam), "%d %b %Y %H:%i") as makan,
						CONCAT(a.tanggal, " ", a.jam) makan_ymd
					FROM htsprtd a
					LEFT JOIN hemxxmh b ON b.kode_finger = a.kode
					WHERE a.tanggal BETWEEN :start_date AND DATE_ADD(:end_date, INTERVAL 1 DAY)
						AND a.nama IN ("MAKAN", "MAKAN MANUAL")
					GROUP BY b.id, a.tanggal
				) mk ON mk.ceklok BETWEEN a.clock_in AND a.clock_out AND mk.id_hemxxmh = a.id_hemxxmh
				
				WHERE 
					a.tanggal BETWEEN :start_date AND :end_date
				--	AND a.durasi_lembur_total_jam = 0
				--	AND a.pot_jam > 0
					'.$where.'
				HAVING durasi_istirahat_menit > 0 AND kategori <> "AMAN"
				ORDER BY a.tanggal
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>