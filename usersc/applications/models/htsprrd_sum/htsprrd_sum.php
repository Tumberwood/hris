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
		->exec('WITH qs_rekap_presensi AS (
					SELECT DISTINCT
						b.kode_finger,
						c.id_heyxxmh,
						CONCAT(b.kode, " - ", b.nama) AS hemxxmh_data,
						DATEDIFF(:end_date, :start_date) + 1 AS HR,
						d.nama AS hodxxmh_nama,
						e.nama AS hetxxmh_nama,
						CEIL(hk_in + hk_out) AS hk,
						st_off,
						st_nj,
						hl_in + hl_out AS hl,
						ct_in + ct_out AS ct,
						cb_in + cb_out AS cb,
						sd_in + sd_out AS sd,
						kk_in + kk_out AS kk,
						al_in + al_out AS al,
						ip_in + ip_out AS ip,
						lain_in + lain_out AS lain,
						ak_in + ak_out AS absen_khusus,
						case 
							when b.is_active = 1 then "active"
							ELSE "nonaktif"
						END AS status_aktif,
						hari_kerja_efektif
						
					FROM hemxxmh AS b
					LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = b.id
					LEFT JOIN hodxxmh AS d ON d.id = c.id_hodxxmh
					LEFT JOIN hetxxmh AS e ON e.id = c.id_hetxxmh
						
					-- Cek Status IN
					LEFT JOIN (
						SELECT
							id_hemxxmh,
							kode_finger,
							hk_in,
							st_off,
							st_nj,
							hl_in,
							ct_in,
							cb_in,
							sd_in,
							kk_in,
							al_in,
							ip_in,
							lain_in,
							ak_in
						FROM (
							SELECT
								prr.id_hemxxmh,
								prr.kode_finger,
								SUM(if(prr.status_presensi_in = "HK", 0.5,0)) AS hk_in,
								SUM(if(prr.status_presensi_in = "OFF", 1,0)) AS st_off,
								SUM(if(prr.status_presensi_in = "NJ", 1,0)) AS st_nj,
								SUM(if(absen.id = 20, 0.5,0)) AS hl_in,
								SUM(if(absen.id = 1, 0.5,0)) AS ct_in,
								SUM(if(absen.id = 2, 0.5,0)) AS cb_in,
								SUM(if(absen.id = 3, 0.5,0)) AS sd_in,
								SUM(if(absen.id = 19, 0.5,0)) AS kk_in,
								SUM(if(absen.id = 5, 0.5,0)) AS al_in,
								SUM(if(absen.id = 6, 0.5,0)) AS ip_in,
								SUM(if(absen.id NOT IN (20,1,2,3,19,5,6), 0.5,0)) AS lain_in,
								SUM(if(absen.is_cuti_khusus = 1, 0.5,0)) AS ak_in
							FROM htsprrd AS prr
							LEFT JOIN htlxxmh AS absen ON absen.kode = prr.status_presensi_in
							WHERE tanggal BETWEEN :start_date AND :end_date
							GROUP BY id_hemxxmh
						) lembur_sum_table
					) st_in ON st_in.id_hemxxmh = b.id
					
					-- Cek Status IN
					LEFT JOIN (
						SELECT
							id_hemxxmh,
							kode_finger,
							hk_out,
							hl_out,
							ct_out,
							cb_out,
							sd_out,
							kk_out,
							al_out,
							ip_out,
							lain_out,
							ak_out
						FROM (
							SELECT
								prr.id_hemxxmh,
								prr.kode_finger,
								SUM(if(prr.status_presensi_out = "HK", 0.5,0)) AS hk_out,
								SUM(if(absen.id = 20, 0.5,0)) AS hl_out,
								SUM(if(absen.id = 1, 0.5,0)) AS ct_out,
								SUM(if(absen.id = 2, 0.5,0)) AS cb_out,
								SUM(if(absen.id = 3, 0.5,0)) AS sd_out,
								SUM(if(absen.id = 19, 0.5,0)) AS kk_out,
								SUM(if(absen.id = 5, 0.5,0)) AS al_out,
								SUM(if(absen.id = 6, 0.5,0)) AS ip_out,
								SUM(if(absen.id NOT IN (20,1,2,3,19,5,6), 0.5,0)) AS lain_out,
								SUM(if(absen.is_cuti_khusus = 1, 0.5,0)) AS ak_out
							FROM htsprrd AS prr
							LEFT JOIN htlxxmh AS absen ON absen.kode = prr.status_presensi_out
							WHERE tanggal BETWEEN :start_date AND :end_date
							GROUP BY id_hemxxmh
						) lembur_sum_table
					) st_out ON st_out.id_hemxxmh = b.id

					LEFT JOIN (
						SELECT
							a.id_hemxxmh,
							b.nama,
							COUNT(a.id) hari_kerja_efektif
						FROM htssctd a
						LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
						LEFT JOIN hemjbmh c ON c.id_hemxxmh = b.id
						WHERE a.tanggal BETWEEN :start_date AND :end_date AND a.id_htsxxmh <> 1
						GROUP BY a.id_hemxxmh
					) sc on sc.id_hemxxmh = b.id
				)
				SELECT
					kode_finger,
					hemxxmh_data,
					hodxxmh_nama,
					hetxxmh_nama,
					hr,
					hari_kerja_efektif,
					hk,
					hk /hari_kerja_efektif * 100 as persen,
					st_off,
					st_nj,
					hl,
					ct,
					cb,
					sd,
					kk,
					al,
					ip,
					absen_khusus,
					lain,
					status_aktif
				FROM qs_rekap_presensi
				WHERE hk IS NOT null
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
			['data' => 'st_off', 'name' => 'st_off'],
			['data' => 'st_nj', 'name' => 'st_nj'],
			['data' => 'hl', 'name' => 'hl'],
			['data' => 'ct', 'name' => 'ct'],
			['data' => 'cb', 'name' => 'cb'],
			['data' => 'sd', 'name' => 'sd'],
			['data' => 'kk', 'name' => 'kk'],
			['data' => 'al', 'name' => 'al'],
			['data' => 'ip', 'name' => 'ip'],
			['data' => 'absen_khusus', 'name' => 'absen_khusus'],
			['data' => 'lain', 'name' => 'lain']
		];
		
	} else {
		$results['data'] = [];
		$results['columns'] = [];
	}

	echo json_encode($results);
?>