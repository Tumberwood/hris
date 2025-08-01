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

	if ($_POST['id_hemxxmh'] > 0) {
		$where = ' AND id = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	$qs_htsprrd = $db
		->raw()
		->bind(':start_date', $start_date)
		->exec('SELECT
					a.id,
					a.kode nik,
					a.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					g.nama AS level,
					h.nama AS status,
					i.nama AS tipe,
					if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp) komp_gaji,
					ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) , if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0) komp_t_jab,
					IFNULL(nominal_var_cost, 0) AS var_cost,
					IFNULL(nominal_mk,0) fix_cost,
					IFNULL(premiabs,0) premi,
					(FLOOR(IF(c.id_hesxxmh = 3, COALESCE(nominal_lembur_mati, 0), (COALESCE(nominal_gp, 0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) ) / 173))) AS nominal_lembur_jam,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(gaji_bpjs,0) ,0) gaji_bpjs,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(persen_jkk,0) ,0) persen_jkk,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(persen_jkm,0) ,0) persen_jkm,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(persen_jht_karyawan,0) ,0) persen_jht_karyawan,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(persen_jp_karyawan,0) ,0) persen_jp_karyawan,
					if(c.id_hesxxmh IN (1,2,5), IFNULL(persen_karyawan,0) ,0) bpjs_kes,
					IFNULL(pot_uang_makan,0) pot_uang_makan
				FROM hemxxmh a
				INNER JOIN hemjbmh c ON c.id_hemxxmh = a.id
				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2
				LEFT JOIN hevxxmh g ON g.id = c.id_hevxxmh
				LEFT JOIN hesxxmh h ON h.id = c.id_hesxxmh
				LEFT JOIN heyxxmd i ON i.id = c.id_heyxxmd
				
				-- gaji pokok
				LEFT JOIN (
					SELECT
						id_hemxxmh,
						tanggal_efektif,
						nominal AS nominal_gp
					FROM (
						SELECT
							id,
							id_hemxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hemxxmh
						WHERE
							htpr_hemxxmh.id_hpcxxmh = 1
							AND tanggal_efektif < :start_date
					) AS subquery
					WHERE row_num = 1
				) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = c.id_hemxxmh
				
				
				-- gaji untuk pelatihan
				LEFT JOIN (
						SELECT
							id_hesxxmh,
							tanggal_efektif,
							IFNULL(nominal, 0) AS gp_pelatihan
						FROM (
							SELECT
								id,
								id_hesxxmh,
								tanggal_efektif,
								nominal,
								ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
							FROM htpr_hesxxmh
						WHERE
							htpr_hesxxmh.id_hpcxxmh = 1
							AND tanggal_efektif < :start_date
					) AS subquery
					WHERE row_num = 1
				) gaji_pelatihan ON gaji_pelatihan.id_hesxxmh = c.id_hesxxmh
				
				-- t jabatan untuk yang Tetap
				LEFT JOIN (
					SELECT
						id_hevxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS nominal_t_jab
					FROM (
						SELECT
							id,
							id_hevxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hevxxmh
						WHERE
							htpr_hevxxmh.id_hpcxxmh = 32
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh
				
				-- nominal tunjangan jabatan di menu per karyawan
				LEFT JOIN (
					SELECT
						id_hemxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS nominal_jabatan
					FROM (
						SELECT
							id,
							id_hemxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hemxxmh
						WHERE
							htpr_hemxxmh.id_hpcxxmh = 32
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id
				
				-- var_cost htpr_hemxxmh.id_hpcxxmh = 102
				LEFT JOIN (
					SELECT
						id_hemxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS nominal_var_cost
					FROM (
						SELECT
							id,
							id_hemxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hemxxmh
						WHERE
							htpr_hemxxmh.id_hpcxxmh = 102
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id
				
				-- Masa Kerja
				LEFT JOIN (
					SELECT
						job.id_hemxxmh,
						nominal AS nominal_mk,
						job.id_hevgrmh,
						masa_kerja_year
					FROM (
						SELECT
							a.id_hemxxmh,
							hev.id_hevgrmh AS id_hevgrmh,
							IF(
								a.tanggal_keluar IS NULL,
								TIMESTAMPDIFF(MONTH, a.tanggal_masuk, :start_date) / 12,
								TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) / 12
							) AS masa_kerja_year
						FROM hemjbmh AS a
						LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
						WHERE is_active = 1
						GROUP BY a.id_hemxxmh
					) AS job
					LEFT JOIN (
						SELECT
							id_hevgrmh,
							tanggal_efektif,
							nominal,
							tahun_min,
							tahun_max,
							ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hevgrmh_mk
						WHERE
							id_hpcxxmh = 31
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
					WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
					GROUP BY job.id_hemxxmh
				) AS mk ON mk.id_hemxxmh = a.id
				
				-- potongan makan htpr_hesxxmh
				LEFT JOIN (
					SELECT
						id_hesxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS pot_uang_makan
					FROM (
						SELECT
							id,
							id_hesxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hesxxmh
						WHERE
							htpr_hesxxmh.id_hpcxxmh = 34
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
				
				-- premi absen
				LEFT JOIN (
					SELECT
						id_hevxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS premiabs 
					FROM (
						SELECT
							id,
							id_hevxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hevxxmh
						WHERE
							htpr_hevxxmh.id_hpcxxmh = 33
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
				
				-- select data dari hibtkmh untuk hitung bpjs
				LEFT JOIN (
					SELECT
					persen_jkk,
					persen_jkm,
					persen_jht_karyawan,
					persen_jp_karyawan,
					is_active
					FROM (
						SELECT
							persen_jkk,
							persen_jkm,
							persen_jht_karyawan,
							persen_jp_karyawan,
							is_active
						FROM hibtkmh
					) sel_bpjs
				) bpjs ON bpjs.is_active = 1
				
				-- select gaji bpjs
				LEFT JOIN (
					SELECT
						id_hemxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS gaji_bpjs
					FROM (
						SELECT
							id,
							id_hemxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hemxxmh
						WHERE
							htpr_hemxxmh.id_hpcxxmh = 2
							AND tanggal_efektif < :start_date
							AND is_active = 1
					) AS subquery
					WHERE row_num = 1
				) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id
				
				-- select data dari hibksmh untuk hitung bpjs kesehatan
				LEFT JOIN (
					SELECT
					persen_karyawan,
					is_active
					FROM (
						SELECT
							persen_karyawan,
							is_active
						FROM hibksmh
					) sel_bpjs
				) bpjs_kesehatan ON bpjs_kesehatan.is_active = 1
				
				-- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
				LEFT JOIN (
					SELECT
						id_hesxxmh,
						tanggal_efektif,
						IFNULL(nominal, 0) AS nominal_lembur_mati
					FROM (
						SELECT
							id,
							id_hesxxmh,
							tanggal_efektif,
							nominal,
							ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
						FROM htpr_hesxxmh
						WHERE
							htpr_hesxxmh.id_hpcxxmh = 36
							AND tanggal_efektif < :start_date
					) AS subquery
					WHERE row_num = 1
				) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
				WHERE a.is_active = 1
				HAVING komp_gaji IS NOT null
				' . $where
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>