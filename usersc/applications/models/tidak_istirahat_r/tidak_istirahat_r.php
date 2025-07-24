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
		->exec('WITH report AS (
					SELECT
						a.id,
						a.id_hemxxmh,
						b.kode AS nik,
						b.nama,
						d.nama AS dep,
						e.nama AS jab,
						f.nama AS area,
						DATE_FORMAT(a.tanggal, "%d %b %Y") AS tanggal,
						CONCAT(
							CASE
								WHEN DATE_ADD(h.jam_akhir, INTERVAL h.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
									OR h.kode like "malam%" AND h.jam_akhir <= "12:00:00" THEN DATE_ADD(a.tanggal, INTERVAL 1 DAY)
								ELSE a.tanggal
							END,
							" ",
							TIME(
								CASE
									WHEN DATE_ADD(h.jam_akhir, INTERVAL h.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
										TIMEDIFF(DATE_ADD(h.jam_akhir, INTERVAL h.menit_toleransi_akhir_out MINUTE), "24:00:00")
									ELSE
										DATE_ADD(h.jam_akhir, INTERVAL h.menit_toleransi_akhir_out MINUTE)
								END
							)
						) AS tanggaljam_akhir_t2,
						a.st_jadwal,
						clock_in,
						DATE_FORMAT(a.clock_in, "%d %b %Y %H:%i" ) AS masuk,
						DATE_FORMAT(a.break_in, "%d %b %Y %H:%i" ) break_in,
						DATE_FORMAT(a.break_out, "%d %b %Y %H:%i" ) break_out,
						is_makan,
						-- makan,
						DATE_FORMAT(a.clock_out, "%d %b %Y %H:%i" ) AS pulang,
				
						TIMESTAMPDIFF(MINUTE, a.break_in, a.break_out) AS durasi_istirahat_menit,
				
						CASE
							WHEN break_in IS NULL AND is_makan = 0 then "Tidak Istirahat dan Makan"
							WHEN break_in IS NULL then "Tidak Istirahat"
							ELSE "Tidak Masuk Kategori"
						END AS kategori,
						a.durasi_lembur_total_jam,
						a.pot_ti,
						a.durasi_lembur_final
				
					FROM htsprrd a
					INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
					INNER JOIN hemjbmh c on c.id_hemxxmh = a.id_hemxxmh
					INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
					INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
					LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2
					INNER JOIN htsxxmh h ON h.kode = a.st_jadwal
				
				
					WHERE 
						a.tanggal BETWEEN :start_date AND :end_date
						AND a.durasi_lembur_total_jam = 0
						AND a.st_jadwal <> "OFF"
						'.$where.'
					HAVING (
					break_in IS NULL
					OR 
					(break_in IS NULL AND is_makan = 0)
					)
					ORDER BY a.tanggal
				)
				SELECT
					report.*,
					mk.makan
				FROM report
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
				) mk ON mk.ceklok BETWEEN report.clock_in AND DATE_SUB(report.tanggaljam_akhir_t2, INTERVAL 60 MINUTE) AND mk.id_hemxxmh = report.id_hemxxmh
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>