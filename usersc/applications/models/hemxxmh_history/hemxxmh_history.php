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
		->bind(':end_date', $end_date)
		->exec('SELECT
					nrp,
					c.ktp_no,
					peg.nama,
					foto,
					hod.nama departemen,
					hos.nama bagian,
					het.nama jabatan,
					c.ktp_alamat AS alamat_ktp,
					c.ktp_desa,
					c.ktp_kecamatan,
					ktp.nama kota_ktp,
					
					lahir.nama kota_lahir,
					DATE_FORMAT(tanggal_lahir, "%d %b %Y") tanggal_lahir,
					CONCAT(
						TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()), " tahun ",
						TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) % 12, " bulan ",
						DATEDIFF(
							CURDATE(),
							DATE_ADD(
								tanggal_lahir,
								INTERVAL TIMESTAMPDIFF(MONTH, tanggal_lahir, CURDATE()) MONTH
							)
						), " hari"
					) AS umur,
					
					c.alamat,
					c.domisili_desa,
					c.domisili_kecamatan,
					domisili.nama kota_domisili,
					
					hol2.nama area_kerja,
					hey.nama tipe,
					heymd.nama sub_tipe,
					hes.nama status,
					DATE_FORMAT(tanggal_bekerja, "%d %b %Y") tanggal_bekerja,
					DATE_FORMAT(tanggal_akhir_kontrak, "%d %b %Y") tanggal_akhir_kontrak,
					DATE_FORMAT(tanggal_keluar, "%d %b %Y") tanggal_keluar,
					IF(b.grup_hk = 1, 5, 6) grup_hk,
					gender,
					f.web_path,
					f.filename,
					peg.is_active
				FROM (
					SELECT
						COUNT(a.id) c_id,
						id_gctxxmh_lahir,
						MAX(a.id) id_baru,
						MAX(a.id_files_foto) foto,
						tanggal_lahir,
						a.nama,
						a.gender,
						max(a.kode) nrp,
						MIN(jb.tanggal_masuk) tanggal_bekerja,

						MAX(a.is_active) is_active
					FROM hemxxmh a
					JOIN hemjbmh jb ON jb.id_hemxxmh = a.id
					GROUP BY a.nama
				) peg
				JOIN hemjbmh b ON b.id_hemxxmh = peg.id_baru
				JOIN hemdcmh c ON c.id_hemxxmh = peg.id_baru
				LEFT JOIN gctxxmh ktp ON ktp.id = c.id_gctxxmh_ktp
				LEFT JOIN gctxxmh lahir ON lahir.id = id_gctxxmh_lahir
				LEFT JOIN gctxxmh domisili ON domisili.id = id_gctxxmh_domisili
				LEFT JOIN files f on f.id = peg.foto

				LEFT JOIN hodxxmh hod ON hod.id = b.id_hodxxmh
				LEFT JOIN hosxxmh hos ON hos.id = b.id_hosxxmh
				LEFT JOIN hetxxmh het ON het.id = b.id_hetxxmh
				LEFT JOIN holxxmd_2 hol2 ON hol2.id = b.id_holxxmd_2
				LEFT JOIN heyxxmh hey ON hey.id = b.id_heyxxmh
				LEFT JOIN heyxxmd heymd ON heymd.id = b.id_heyxxmd
				LEFT JOIN hesxxmh hes ON hes.id = b.id_hesxxmh

				WHERE 1
					AND b.is_harian_lepas = 0
					AND (
						tanggal_keluar IS NULL
						OR
						tanggal_keluar >= :end_date
					)
					AND peg.is_active = 1
				' 
				);
	$rs_htsprrd = $qs_htsprrd->fetchAll();

	$results = array();

	$results['data']['htsprrd'] = !empty($rs_htsprrd) ? $rs_htsprrd : [];

	echo json_encode($results);
?>