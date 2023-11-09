<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );
    require '../../../../usersc/vendor/autoload.php';

    use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	// BEGIN definisi variable untuk fn_ajax_results.php
	$data      = array();
	$rs_opt    = array();
	$c_rs_opt  = 0;
	$morePages = 0;
	// END definisi variable untuk fn_ajax_results.php
	
	$id = $_POST['id_transaksi_h'];
	$state = $_POST['state'];
	
	$qs_kode = $db
		->raw()
		->bind(':id', $id)
		->exec(' SELECT
					a.kode,
					a.id_hemxxmh
				FROM harxxth AS a
				WHERE a.id = :id
				'
				);
	$rs_kode = $qs_kode->fetch();

	if($state == 1){

		$qi_hemjbrd = $db
		->raw()
			->bind(':id', $id)
			->exec('INSERT INTO hemjbrd(
						id_hemxxmh,
						id_hovxxmh_akhir,
						id_hodxxmh_akhir,
						id_hosxxmh_akhir,
						id_hevxxmh_akhir,
						id_hetxxmh_akhir,
					
						id_hovxxmh_awal,
						id_hodxxmh_awal,
						id_hosxxmh_awal,
						id_hevxxmh_awal,
						id_hetxxmh_awal,
						tanggal_awal,
						kode,
						is_rotasi,
						keterangan
					)
					SELECT
						b.id_hemxxmh,
						b.id_hovxxmh_akhir,
						b.id_hodxxmh_akhir,
						b.id_hosxxmh_akhir,
						b.id_hevxxmh_akhir,
						b.id_hetxxmh_akhir,
						a.id_hovxxmh,
						a.id_hodxxmh,
						a.id_hosxxmh,
						a.id_hevxxmh,
						a.id_hetxxmh,
						b.tanggal_efektif,
						b.kode,
						1,
						IF(b.id_hovxxmh_akhir <> a.id_hovxxmh,
						CONCAT("Perubahan Divisi ", hov.nama , " Menjadi ", hov_new.nama),
						IF(b.id_hodxxmh_akhir <> a.id_hodxxmh,
							CONCAT("Perubahan Department ", hod.nama , " Menjadi ", hod_new.nama),
							IF(b.id_hosxxmh_akhir <> a.id_hosxxmh,
								CONCAT("Perubahan Section ", hos.nama , " Menjadi ", hos_new.nama),
								IF(b.id_hevxxmh_akhir <> a.id_hevxxmh,
									CONCAT("Perubahan Level ", hev.nama , " Menjadi ", hev_new.nama),
									IF(b.id_hetxxmh_akhir <> a.id_hetxxmh,
										CONCAT("Perubahan Jabatan ", het.nama , " Menjadi ", het_new.nama),
										"" 
									)
								)
							)
						)
					) AS ket
					FROM hemjbmh AS a
					LEFT JOIN harxxth AS b ON b.id_hemxxmh = a.id_hemxxmh
					LEFT JOIN hovxxmh AS hov ON hov.id = a.id_hovxxmh
					LEFT JOIN hovxxmh AS hov_new ON hov_new.id = b.id_hovxxmh_akhir
					LEFT JOIN hodxxmh AS hod ON hod.id = a.id_hodxxmh
					LEFT JOIN hodxxmh AS hod_new ON hod_new.id = b.id_hodxxmh_akhir
					LEFT JOIN hosxxmh AS hos ON hos.id = a.id_hosxxmh
					LEFT JOIN hosxxmh AS hos_new ON hos_new.id = b.id_hosxxmh_akhir
					LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
					LEFT JOIN hevxxmh AS hev_new ON hev_new.id = b.id_hevxxmh_akhir
					LEFT JOIN hetxxmh AS het ON het.id = a.id_hetxxmh
					LEFT JOIN hetxxmh AS het_new ON het_new.id = b.id_hetxxmh_akhir
					WHERE b.id = :id;
					'
					);

		$qu_hemjbmh = $db
			->raw()
			->bind(':id', $id)
			->exec('UPDATE hemjbmh AS a
					LEFT JOIN harxxth AS b ON b.id_hemxxmh = a.id_hemxxmh 
					SET 
						a.id_hovxxmh = b.id_hovxxmh_akhir,
						a.id_hodxxmh = b.id_hodxxmh_akhir,
						a.id_hosxxmh = b.id_hosxxmh_akhir,
						a.id_hevxxmh = b.id_hevxxmh_akhir,
						a.id_hetxxmh = b.id_hetxxmh_akhir
					WHERE b.id = :id;
					'
					);

	} else if($state == 2) {

		$qu_hemjbmh = $db
			->raw()
			->bind(':id', $id)
			->exec('UPDATE hemjbmh AS a
					LEFT JOIN harxxth AS b ON b.id_hemxxmh = a.id_hemxxmh 
					LEFT JOIN hemjbrd AS c ON c.kode = b.kode
					SET 
						a.id_hovxxmh = c.id_hovxxmh_awal,
						a.id_hodxxmh = c.id_hodxxmh_awal,
						a.id_hosxxmh = c.id_hosxxmh_awal,
						a.id_hevxxmh = c.id_hevxxmh_awal,
						a.id_hetxxmh = c.id_hetxxmh_awal
					WHERE b.id = :id;
					'
					);
					
		$qd_ = $db
			->query('delete', 'hemjbrd')
			->where('id_hemxxmh',$rs_kode['id_hemxxmh'])
			->where('kode',$rs_kode['kode'])
			->exec();
	}

	

?>