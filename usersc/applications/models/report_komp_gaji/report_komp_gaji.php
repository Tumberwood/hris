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
		$where = ' AND id_hemxxmh = ' . $_POST['id_hemxxmh'];
	} else {
		$where = '';
	}

	$qs_htsprrd = $db
		->raw()
		->bind(':start_date', $start_date)
		->exec('SELECT
					a.id id_hemxxmh,
					a.kode nik,
					a.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					g.nama AS level,
					h.nama AS status,
					i.nama AS tipe,
					c.tanggal_masuk,
					c.tanggal_keluar,
					if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp) komp_gaji
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