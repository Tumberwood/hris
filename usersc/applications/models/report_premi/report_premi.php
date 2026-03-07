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
					:start_date AS start_date,
					:end_date AS end_date,
					a.id_hemxxmh,
					b.kode AS nik,
					b.nama,
					d.nama AS dep,
					e.nama AS jab,
					f.nama AS area,
					SUM(a.is_pot_premi) pot_premi
				FROM htsprrd a
				INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh

				INNER JOIN (
					SELECT
						j.id_hemxxmh,
						j.id_heyxxmh,
						j.id_hevxxmh,
						j.id_heyxxmd,
						j.is_checkclock,
						j.tanggal_masuk,
						j.tanggal_keluar,
						IFNULL(history.id_hesxxmh, j.id_hesxxmh) id_hesxxmh,
						IFNULL(history.jumlah_grup, j.jumlah_grup) jumlah_grup,
						IF(
							IFNULL(history.id_holxxmd_2_akhir, 0) > 0,
							history.id_holxxmd_2_akhir,
							IF(
								IFNULL(history.id_holxxmd_2_awal, 0) > 0,
								history.id_holxxmd_2_awal,
								IFNULL(j.id_holxxmd_2, 0)
							)
						) AS id_holxxmd_2,
						IF(
							IFNULL(history.id_hetxxmh_akhir, 0) > 0,
							history.id_hetxxmh_akhir,
							IF(
								IFNULL(history.id_hetxxmh_awal, 0) > 0,
								history.id_hetxxmh_awal,
								IFNULL(j.id_hetxxmh, 0)
							)
						) AS id_hetxxmh,
						IF(
							IFNULL(history.id_hodxxmh_akhir, 0) > 0,
							history.id_hodxxmh_akhir,
							IF(
								IFNULL(history.id_hodxxmh_awal, 0) > 0,
								history.id_hodxxmh_awal,
								IFNULL(j.id_hodxxmh, 0)
							)
						) AS id_hodxxmh,
						IF(
							IFNULL(history.id_hosxxmh_akhir, 0) > 0,
							history.id_hosxxmh_akhir,
							IF(
								IFNULL(history.id_hosxxmh_awal, 0) > 0,
								history.id_hosxxmh_awal,
								IFNULL(j.id_hosxxmh, 0)
							)
						) AS id_hosxxmh,
						IFNULL(history.grup_hk, j.grup_hk) grup_hk
					FROM hemjbmh j
					LEFT JOIN (
						SELECT
							*
						FROM (
							SELECT
								*,
								ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_awal DESC) AS row_num
							FROM hemjbrd
							WHERE
								tanggal_awal <= :end_date
						) AS subquery
						WHERE row_num = 1
					) history ON history.id_hemxxmh = j.id_hemxxmh
				) c ON c.id_hemxxmh = b.id

				INNER JOIN hodxxmh d ON d.id = c.id_hodxxmh
				INNER JOIN hetxxmh e ON e.id = c.id_hetxxmh
				LEFT JOIN holxxmd_2 f ON f.id = c.id_holxxmd_2

				WHERE a.tanggal BETWEEN :start_date AND :end_date
				'.$where.'
				GROUP BY a.id_hemxxmh
				HAVING pot_premi = 0
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>