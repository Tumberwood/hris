<?php
	/**
	 * Kolom :
	 * 	- dari htlgrmh, dan 
	 * 	- HK		: Hari Kerja
	 * 	- OFF		: Jadwal Off
	 * 	- NJ		: Jadwal Belum Dibuat
	 */

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

		
	$awal 			= new Carbon($_POST['start_date']);
	$akhir 			= new Carbon($_POST['end_date']);
	
	$start_date = $awal->format('Y-m-d');
	$end_date 	= $akhir->format('Y-m-d');
	
	$user = $_SESSION['user'];
	if ($user > 100) {
		$w_id_heyxxmh_session = ' AND id_heyxxmh IN (' . $_SESSION['str_arr_ha_heyxxmh'] . ')';
	} else {
		$w_id_heyxxmh_session = ' AND id_heyxxmh NOT IN (-1)';
	}

	$qs_rekap_presensi = $db
		->raw()
		->bind(':start_date', $start_date)
		->bind(':end_date', $end_date)
		->exec('WITH presensi_agg AS (
					SELECT
						prr.id_hemxxmh,
						prr.kode_finger,

						/* HK */
						SUM(
						    IF( (prr.status_presensi_in  = "HK" OR prr.status_presensi_in  = "TL 1") OR prr.status_presensi_out = "HK", 1, 0)
						) AS hk,
						SUM(
							IF( prr.status_presensi_in  = "HK" OR (prr.status_presensi_out = "HK" AND prr.status_presensi_in <> "TL 1"), 1, 0)
						) AS hk_tok,
						SUM(
							IF( prr.status_presensi_in  = "TL 1", 1, 0)
						) AS late_1,

						-- SUM(
						-- 	IF( prr.status_presensi_in  <> "OFF" AND prr.status_presensi_in  <> "NJ" , 1, 0)
						-- ) AS hk,

						/* OFF & NJ (hanya dari IN sesuai logic awal) */
						SUM(IF(prr.status_presensi_in = "OFF", 1, 0)) AS st_off,
						SUM(IF(prr.status_presensi_in = "NJ",  1, 0)) AS st_nj,

						/* CUTI / IZIN */
						SUM(
							IF(absen_in.id  = 20, 0.5, 0) +
							IF(absen_out.id = 20, 0.5, 0)
						) AS hl,
						SUM(
							IF(absen_in.id  = 1, 0.5, 0) +
							IF(absen_out.id = 1, 0.5, 0)
						) AS ct,
						SUM(
							IF(absen_in.id  = 2, 0.5, 0) +
							IF(absen_out.id = 2, 0.5, 0)
						) AS cb,
						SUM(
							IF(absen_in.id  = 3, 0.5, 0) +
							IF(absen_out.id = 3, 0.5, 0)
						) AS sd,
						SUM(
							IF(absen_in.id  = 19, 0.5, 0) +
							IF(absen_out.id = 19, 0.5, 0)
						) AS kk,
						SUM(
							IF(absen_in.id  = 5, 0.5, 0) +
							IF(absen_out.id = 5, 0.5, 0)
						) AS al,
						SUM(
							IF(absen_in.id  = 6, 0.5, 0) +
							IF(absen_out.id = 6, 0.5, 0)
						) AS it,

						/* STATUS KHUSUS */
						SUM(
							IF(prr.status_presensi_in  = "SK", 0.5, 0) +
							IF(prr.status_presensi_out = "SK", 0.5, 0)
						) AS SK,
						SUM(
							IF(prr.status_presensi_in  = "SPSI", 0.5, 0) +
							IF(prr.status_presensi_out = "SPSI", 0.5, 0)
						) AS SPSI,
						SUM(
							IF(prr.status_presensi_in  = "DL", 0.5, 0) +
							IF(prr.status_presensi_out = "DL", 0.5, 0)
						) AS DL,
						SUM(
							IF(prr.status_presensi_in  = "S3", 0.5, 0) +
							IF(prr.status_presensi_out = "S3", 0.5, 0)
						) AS S3,
						SUM(
							IF(prr.status_presensi_in  = "LB", 0.5, 0) +
							IF(prr.status_presensi_out = "LB", 0.5, 0)
						) AS LB,
						SUM(
							IF(prr.status_presensi_in  = "LR", 0.5, 0) +
							IF(prr.status_presensi_out = "LR", 0.5, 0)
						) AS LR,

						SUM(
							IF(prr.status_presensi_in  = "CK", 0.5, 0) +
							IF(prr.status_presensi_out = "CK", 0.5, 0)
						) AS CK,
						SUM(
							IF(prr.status_presensi_in  = "KAK", 0.5, 0) +
							IF(prr.status_presensi_out = "KAK", 0.5, 0)
						) AS KAK,
						SUM(
							IF(prr.status_presensi_in  = "KOT", 0.5, 0) +
							IF(prr.status_presensi_out = "KOT", 0.5, 0)
						) AS KOT,
						SUM(
							IF(prr.status_presensi_in  = "PS", 0.5, 0) +
							IF(prr.status_presensi_out = "PS", 0.5, 0)
						) AS PS,
						SUM(
							IF(prr.status_presensi_in  = "IMG", 0.5, 0) +
							IF(prr.status_presensi_out = "IMG", 0.5, 0)
						) AS IMG,
						SUM(
							IF(prr.status_presensi_in  = "PKB", 0.5, 0) +
							IF(prr.status_presensi_out = "PKB", 0.5, 0)
						) AS PKB,
						SUM(
							IF(prr.status_presensi_in  = "KKR", 0.5, 0) +
							IF(prr.status_presensi_out = "KKR", 0.5, 0)
						) AS KKR,
						SUM(
							IF(prr.status_presensi_in  = "KM", 0.5, 0) +
							IF(prr.status_presensi_out = "KM", 0.5, 0)
						) AS KM,

						/* LAIN & ABSEN KHUSUS */
						SUM(
							IF(absen_in.id NOT IN (20,1,2,3,19,5,6) AND absen_in.is_cuti_khusus = 0, 0.5, 0) +
							IF(absen_out.id NOT IN (20,1,2,3,19,5,6) AND absen_out.is_cuti_khusus = 0, 0.5, 0)
						) AS lain,
						SUM(
							IF(absen_in.is_cuti_khusus  = 1, 0.5, 0) +
							IF(absen_out.is_cuti_khusus = 1, 0.5, 0)
						) AS absen_khusus

					FROM htsprrd prr
					LEFT JOIN htlxxmh absen_in  ON absen_in.kode  = prr.status_presensi_in
					LEFT JOIN htlxxmh absen_out ON absen_out.kode = prr.status_presensi_out
					WHERE prr.tanggal BETWEEN :start_date AND :end_date
					GROUP BY prr.id_hemxxmh
				),

				sc AS (
					SELECT
						a.id_hemxxmh,
						COUNT(*) AS hari_kerja_efektif
					FROM htssctd a
					WHERE a.tanggal BETWEEN :start_date AND :end_date
					AND a.id_htsxxmh <> 1
					AND a.is_active = 1
					GROUP BY a.id_hemxxmh
				)

				SELECT
					b.kode_finger,
					CONCAT(b.kode, " - ", b.nama) AS hemxxmh_data,
					d.nama AS hodxxmh_nama,
					e.nama AS hetxxmh_nama,
					DATEDIFF(:end_date,:start_date) + 1 AS hr,
					sc.hari_kerja_efektif,
					CEIL(p.hk) AS hk,
					(p.hk / sc.hari_kerja_efektif * 100) AS persen,
					p.hk_tok,
					p.late_1,
					p.st_off,
					p.st_nj,
					p.hl,
					p.ct,
					p.cb,
					p.sd,
					p.kk,
					p.al,
					p.it,
					p.SK,
					p.SPSI,
					p.DL,
					p.S3,
					p.LB,
					p.LR,
					p.CK,
					p.KAK,
					p.KOT,
					p.PS,
					p.IMG,
					p.PKB,
					p.KKR,
					p.KM,
					p.absen_khusus,
					p.lain,
					CASE WHEN b.is_active = 1 THEN "active" ELSE "nonaktif" END AS status_aktif
				FROM hemxxmh b
				LEFT JOIN hemjbmh c ON c.id_hemxxmh = b.id
				LEFT JOIN hodxxmh d ON d.id = c.id_hodxxmh
				LEFT JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN presensi_agg p ON p.id_hemxxmh = b.id
				LEFT JOIN sc ON sc.id_hemxxmh = b.id
				WHERE p.hk IS NOT NULL
				'.$w_id_heyxxmh_session
				);
	$rs_rekap_presensi = $qs_rekap_presensi->fetchAll();

	$results = array();

	if (!empty($rs_rekap_presensi)) {
		$results['data'] = $rs_rekap_presensi;
		
		$results['columns'] = [
			['data' => 'kode_finger', 'name' => 'kode_finger'],
			['data' => 'hemxxmh_data', 'name' => 'hemxxmh_data'],
			['data' => 'hodxxmh_nama', 'name' => 'hodxxmh_nama'],
			['data' => 'hetxxmh_nama', 'name' => 'hetxxmh_nama'],
			['data' => 'hr', 'name' => 'hr'],
			['data' => 'hari_kerja_efektif', 'name' => 'hari_kerja_efektif'],
			['data' => 'hk', 'name' => 'hk'],
			['data' => 'persen', 'name' => 'persen'],
			['data' => 'hk_tok', 'name' => 'hk_tok'],
			['data' => 'late_1', 'name' => 'late_1'],
			['data' => 'st_off', 'name' => 'st_off'],
			['data' => 'st_nj', 'name' => 'st_nj'],
			['data' => 'hl', 'name' => 'hl'],
			['data' => 'ct', 'name' => 'ct'],
			['data' => 'cb', 'name' => 'cb'],
			['data' => 'sd', 'name' => 'sd'],
			['data' => 'kk', 'name' => 'kk'],
			['data' => 'al', 'name' => 'al'],
			['data' => 'it', 'name' => 'it'],
			// ['data' => 'absen_khusus', 'name' => 'absen_khusus'],
			// ['data' => 'lain', 'name' => 'lain'],
			
			//ini buat untuk colspan Absen khusus
			['data' => 'SK',   'name' => 'SK'],
			['data' => 'SPSI', 'name' => 'SPSI'],
			['data' => 'DL',   'name' => 'DL'],
			['data' => 'S3',   'name' => 'S3'],
			['data' => 'LB',   'name' => 'LB'],
			['data' => 'LR',   'name' => 'LR'],

			//colspan Absen Lain
			['data' => 'CK',   'name' => 'CK'],
			['data' => 'KAK',  'name' => 'KAK'],
			['data' => 'KOT',  'name' => 'KOT'],
			['data' => 'PS',   'name' => 'PS'],
			['data' => 'IMG',  'name' => 'IMG'],
			['data' => 'PKB',  'name' => 'PKB'],
			['data' => 'KKR',  'name' => 'KKR'],
			['data' => 'KM',   'name' => 'KM'],
		];
		
	} else {
		$results['data'] = [];
		$results['columns'] = [];
	}

	echo json_encode($results);
?>