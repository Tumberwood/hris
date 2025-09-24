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
	
	$id_hesxxtd = $_POST['id_transaksi_h'];
	$state = $_POST['state'];

	$qs_hesxxtd = $db
		->query('select', 'hesxxtd' )
		->get([
			'hesxxtd.kode as kode_hesxxtd',
			'hesxxtd.id_hemxxmh as id_hemxxmh',
			'hesxxtd.id_hesxxmh as id_hesxxmh',
			'hesxxtd.tanggal_mulai as tanggal_mulai',
			'hesxxtd.tanggal_selesai as tanggal_selesai',
			'hesxxtd.id_hesxxmh_tetap as id_hesxxmh_tetap',
			'hesxxtd.keterangan as keterangan',
			'hesxxtd.keputusan as keputusan',
			'hemxxmh.kode as kode',
			'hesxxtd.nik_baru as nik_baru'
		] )
		->where('hesxxtd.id', $id_hesxxtd )
		->join('hemxxmh','hemxxmh.id = hesxxtd.id_hemxxmh','LEFT' )
		->exec();

	$rs_hesxxtd = $qs_hesxxtd->fetch();

	$kode_hesxxtd = $rs_hesxxtd['kode_hesxxtd'];
	$keputusan = $rs_hesxxtd['keputusan'];
	$id_hemxxmh = $rs_hesxxtd['id_hemxxmh'];
	$tanggal_selesai = $rs_hesxxtd['tanggal_selesai'];
	$tanggal_mulai = $rs_hesxxtd['tanggal_mulai'];
	$id_hesxxmh = $rs_hesxxtd['id_hesxxmh'];
	$id_hesxxmh_tetap = $rs_hesxxtd['id_hesxxmh_tetap'];
	$nik_baru = $rs_hesxxtd['nik_baru'];
	$kode_lama = $rs_hesxxtd['kode'];

	if($state == 1){


		// ini untuk flag
		$qs_flag_status = $db
			->raw()
			->bind(':id_hemxxmh', $id_hemxxmh)
			->exec(' SELECT
						a.id_heyxxmd,
						a.id_heyxxmh,
						a.id_hesxxmh
					FROM hemjbmh AS a
					WHERE a.id_hemxxmh = :id_hemxxmh
					'
					);
		$rs_flag_status = $qs_flag_status->fetch();

		// ini untuk insert
		$qs_hemxxmh = $db
			->query('select', 'hemxxmh' )
			->get([
				'hemxxmh.id_files_foto as id_files_foto',
				'hemxxmh.id_gcrxxmh as id_gcrxxmh',
				'hemxxmh.id_gctxxmh_lahir as id_gctxxmh_lahir',
				'hemxxmh.id_users as id_users',
				'hemxxmh.nama as nama',
				'hemxxmh.kode_finger as kode_finger',
				'hemxxmh.nama_panggilan as nama_panggilan',
				'hemxxmh.tanggal_lahir as tanggal_lahir',
				'hemxxmh.gender as gender',
				'hemxxmh.agama as agama',
				'hemxxmh.is_pot_makan as is_pot_makan',
				'hemxxmh.perkawinan as perkawinan'
			] )
			->where('hemxxmh.id', $id_hemxxmh )
			->exec();
		$rs_hemxxmh = $qs_hemxxmh->fetch();

		// ini untuk insert
		$qs_hemjbmh = $db
			->query('select', 'hemxxmh' )
			->get([
				'hemjbmh.id_gcpxxmh as id_gcpxxmh',
				'hemjbmh.id_gbrxxmh as id_gbrxxmh',
				'hemjbmh.id_hovxxmh as id_hovxxmh',
				'hemjbmh.id_hodxxmh as id_hodxxmh',
				'hemjbmh.id_hosxxmh as id_hosxxmh',
				'hemjbmh.id_hobxxmh as id_hobxxmh',
				'hemjbmh.id_hevxxmh as id_hevxxmh',
				'hemjbmh.id_hetxxmh as id_hetxxmh',
				'hemjbmh.id_holxxmh as id_holxxmh',
				'hemjbmh.id_hemxxmh_al as id_hemxxmh_al',
				'hemjbmh.id_heyxxmh as id_heyxxmh',
				'hemjbmh.id_heyxxmd as id_heyxxmd',
				'hemjbmh.id_hbnxxmh as id_hbnxxmh',
				'hemjbmh.is_checkclock as is_checkclock',
				'hemjbmh.email_perusahaan as email_perusahaan',
				'hemjbmh.jenis_lembur as jenis_lembur',
				'hemjbmh.rekening_no as rekening_no',
				'hemjbmh.rekening_nama as rekening_nama',
				'hemjbmh.bpjskes_no as bpjskes_no',
				'hemjbmh.bpjstk_no as bpjstk_no',
				'hemjbmh.grup_hk as grup_hk',
				'hemjbmh.jumlah_grup as jumlah_grup'
			] )
			->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
			->where('hemxxmh.id', $id_hemxxmh )
			->exec();
		$rs_hemjbmh = $qs_hemjbmh->fetch();
		
		$flag_1hari = 0;
		$is_kompensasi = 0;

		if ($keputusan != 'Terminasi') {
			$is_htpr_no_hemxxmh = 0;
			$is_per_karyawan = 1;
			if ($keputusan == 'Rekontrak') {
				$id_hesxxmh = 2;
				$is_kompensasi = 1;
			} else if ($keputusan == 'Kontrak') {
				if ($rs_flag_status['id_hesxxmh'] == 3) {
					$flag_1hari = 1;
					$is_per_karyawan = 0;
				}
				$id_hesxxmh = 2;
				$is_htpr_no_hemxxmh = 1;
			} else if ($keputusan == 'Reguler') {
				$id_hesxxmh = 4;
				$is_htpr_no_hemxxmh = 1;

			} else if ($keputusan == 'Perpanjangan Pelatihan') {
				$id_hesxxmh = 3;
			} else if ($keputusan == 'Tetap') {
				$id_hesxxmh = $id_hesxxmh_tetap;
				if ($rs_flag_status['id_hesxxmh'] == 2) {
					$flag_1hari = 1;
				}
			}

			// jika kbm maka di min 1 hari
			if ($rs_flag_status['id_heyxxmd'] == 1) {
				$flag_1hari = 1;
			}
			
			$qi_hemxxmh = $db
				->query('insert', 'hemxxmh')
				->set($rs_hemxxmh)
				->set('kode', $nik_baru )
				->exec();
			$id_insert_hemx = $qi_hemxxmh->insertId();

			//add id_hemxxmh yang baru ini di jadwal 3 grup agar bisa dibuatkan otomatis schedule
			$qs_hgsemtd_v3 = $db
				->raw()
				->bind(':id_hemxxmh', $id_hemxxmh)
				->bind(':id_insert_hemx', $id_insert_hemx)
				->exec('INSERT INTO hgsemtd_v3(
							id_hgsptth_v3,
							id_htsptth_v3,
							id_hemxxmh,
							id_holxxmd,
							id_htsxxmh,
							nama,
							shift,
							jam
						)
						SELECT
							b.id_hgsptth_v3,
							b.id_htsptth_v3,
							:id_insert_hemx,
							b.id_holxxmd,
							b.id_htsxxmh,
							b.nama,
							b.shift,
							b.jam
						FROM hgsptth_v3 a
						LEFT JOIN hgsemtd_v3 b ON b.id_hgsptth_v3 = a.id
						WHERE b.id_hemxxmh = :id_hemxxmh
						ORDER BY a.tanggal_akhir DESC
						LIMIT 3
			');

			//flag jika kontrak maka insert ke htpr_no_hemxxmh
			if ($is_htpr_no_hemxxmh == 1) {
				// 2 hari sebelum $tanggal_mulai
				$datesBefore = [];
				$datesBefore[] = Carbon::parse($tanggal_mulai)->subDays(2);
				$datesBefore[] = Carbon::parse($tanggal_mulai)->subDays(1);
				
				// Insert data for each date in the array
				foreach ($datesBefore as $dateBefore) {
					$qi_htpr_no_hemxxmh = $db
						->query('insert', 'htpr_no_hemxxmh')
						->set('tanggal', $dateBefore->toDateString())
						->set('id_hemxxmh', $id_insert_hemx)
						->set('id_hodxxmh', $rs_hemjbmh['id_hodxxmh'])
						->set('id_hetxxmh', $rs_hemjbmh['id_hetxxmh'])
						->exec();
				}
			}

			if ($is_per_karyawan == 1) {
				$qi_htpr = $db
					->raw()
					->bind(':id_insert_hemx', $id_insert_hemx)
					->bind(':id_hemxxmh', $id_hemxxmh)
					->exec(' INSERT INTO htpr_hemxxmh
							(
								id_hemxxmh,
								id_hpcxxmh,
								tanggal_efektif,
								nominal
							)
							SELECT
								:id_insert_hemx,
								a.id_hpcxxmh,
								a.tanggal_efektif,
								a.nominal
							FROM htpr_hemxxmh AS a
							WHERE a.id_hemxxmh = :id_hemxxmh
							'
							);
			}
			
			$qi_hemjbmh = $db
			->query('insert', 'hemjbmh')
			->set('id_hemxxmh',$id_insert_hemx)
			->set('id_hesxxmh',$id_hesxxmh)
			->set($rs_hemjbmh)
			->set('tanggal_masuk',$tanggal_mulai)
			->set('tanggal_keluar',$tanggal_selesai)
			->exec();

			// insert ke hemdcmh
			$qi_hemdcmh = $db
			->raw()
			->bind(':id_hemxxmh', $id_hemxxmh)
			->exec('INSERT INTO hemdcmh
					(
						id_hemxxmh,
						id_gtxpkmh,
						ktp_no,
						no_bpjs_tk,
						no_bpjs_kes
					)
					SELECT
					' . $id_insert_hemx . ',
						a.id_gtxpkmh,
						a.ktp_no,
						a.no_bpjs_tk,
						a.no_bpjs_kes
					FROM hemdcmh AS a
					WHERE a.id_hemxxmh = :id_hemxxmh;
					'
					);
		
			$qi_hemjbrd = $db
			->query('insert', 'hemjbrd')
			->set('kode', $kode_hesxxtd)
			->set('id_harxxmh',1)
			->set('is_email_status',1)
			->set('id_hemxxmh',$id_insert_hemx)
			->set('id_hesxxmh',$id_hesxxmh)
			->set('id_gcpxxmh_awal',$rs_hemjbmh['id_gcpxxmh'])
			->set('id_gcpxxmh_akhir',$rs_hemjbmh['id_gcpxxmh'])
			->set('id_gbrxxmh_awal',$rs_hemjbmh['id_gbrxxmh'])
			->set('id_gbrxxmh_akhir',$rs_hemjbmh['id_gbrxxmh'])
			->set('id_holxxmh_awal',$rs_hemjbmh['id_holxxmh'])
			->set('id_holxxmh_akhir',$rs_hemjbmh['id_holxxmh'])
			->set('id_hovxxmh_awal',$rs_hemjbmh['id_hovxxmh'])
			->set('id_hovxxmh_akhir',$rs_hemjbmh['id_hovxxmh'])
			->set('id_hodxxmh_awal',$rs_hemjbmh['id_hodxxmh'])
			->set('id_hodxxmh_akhir',$rs_hemjbmh['id_hodxxmh'])
			->set('id_hosxxmh_awal',$rs_hemjbmh['id_hosxxmh'])
			->set('id_hosxxmh_akhir',$rs_hemjbmh['id_hosxxmh'])
			->set('id_hobxxmh_awal',$rs_hemjbmh['id_hobxxmh'])
			->set('id_hobxxmh_akhir',$rs_hemjbmh['id_hobxxmh'])
			->set('id_hevxxmh_awal',$rs_hemjbmh['id_hevxxmh'])
			->set('id_hevxxmh_akhir',$rs_hemjbmh['id_hevxxmh'])
			->set('id_hetxxmh_awal',$rs_hemjbmh['id_hetxxmh'])
			->set('id_hetxxmh_akhir',$rs_hemjbmh['id_hetxxmh'])
			->set('grup_hk',$rs_hemjbmh['grup_hk'])
			->set('jumlah_grup',$rs_hemjbmh['jumlah_grup'])
			->set('tanggal_awal',$tanggal_mulai)
			->set('tanggal_akhir',$tanggal_selesai)
			->exec();

			$qs_pola_shift = $db
				->query('select', 'htsptth' )
				->get(['htsemtd.id_htsptth as id_htsptth'] )
				->get(['htsemtd.grup_ke as grup_ke'] )
				->join('htsemtd','htsemtd.id_htsptth = htsptth.id','LEFT' )
				->where('htsemtd.id_hemxxmh', $id_hemxxmh )
				->exec();
			$rs_pola_shift = $qs_pola_shift->fetch();

			if (!empty($rs_pola_shift)) {
				$qi_pola_shift = $db
					->query('insert', 'htsemtd')
					->set('id_hemxxmh',$id_insert_hemx)
					->set($rs_pola_shift)
					->exec();
			}

			//update hemjbmh tanggal keluar
			// if ($flag_1hari == 1) {
			// 		if ($rs_flag_status['id_heyxxmh'] == 2) {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai);
			// 		} else {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai)->subDays(1);
			// 		}
			// } else {
			// 	if ($rs_flag_status['id_heyxxmh'] == 2) { // Case Outsourcing
			// 		$tanggal_hitung = Carbon::parse($tanggal_mulai)->subDays(2);
			// 	} else {
			// 		$tanggal_hitung = Carbon::parse($tanggal_mulai)->subDays(3);
			// 	}

			// 	// cek apakah hari libur
			// 	$qs_holiday = $db
			// 		->query('select', 'hthhdth' )
			// 		->get(['id'] )
			// 		->where('tanggal', $tanggal_hitung )
			// 		->exec();
			// 	$rs_holiday = $qs_holiday->fetch();

			// 	// jika hari libur maka mundur 4 hari
			// 	if ($rs_flag_status['id_heyxxmh'] == 2) {
			// 		if (!empty($rs_holiday)) {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai)->subDays(3);
			// 		} else {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai)->subDays(2);
			// 		}
			// 	} else {
			// 		if (!empty($rs_holiday)) {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai)->subDays(4);
			// 		} else {
			// 			$hemjbmh_tgl_akhir = Carbon::parse($tanggal_mulai)->subDays(3);
			// 		}
			// 	}
			// }

			// $qu_hemxxmh = $db
			// 	->query('update', 'hemjbmh')
			// 	->set('tanggal_keluar', $hemjbmh_tgl_akhir)
			// 	->where('id_hemxxmh', $id_hemxxmh )
			// 	->exec();
			
			//Update masih muncul di cek 1, padahal ybs sudah naik ke reguler (nik baru) sejak 12/4. Bu Cia 27 Apr 2024 WA Grup HRIS PMI
			$qs_keluar = $db
				->raw()
				->bind(':id_hemxxmh', $id_hemxxmh)
				->exec(' UPDATE hemjbmh b
						SET 
							b.tanggal_keluar =  IF(b.tanggal_keluar IS NULL, DATE_SUB(DATE_ADD(b.tanggal_masuk, INTERVAL 6 MONTH), INTERVAL 1 DAY), b.tanggal_keluar)
						WHERE
							b.id_hemxxmh = :id_hemxxmh;
				
						'
			);
			
			// Untuk rekontrak dapat tambahan kompensasi
			if ($is_kompensasi == 1) {
				$qi_htpr = $db
					->raw()
					->bind(':tanggal_keluar', $tanggal_selesai)
					->bind(':id_hemxxmh', $id_hemxxmh)
					->exec(' INSERT INTO hpy_piutang_d
							(
								id_hemxxmh,
								id_hpcxxmh,
								is_approve,
								plus_min,
								tanggal,
								nominal
							)
							SELECT
								' . $id_insert_hemx . ',
								108,
								1,
								"Penambah",
								a.tanggal_keluar,
								ROUND(
									( 
									TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) / 12
									) *
									(ifnull(nominal_gp, 0) + ifnull(nominal_t_jab, 0))
								, 0
								) AS nominal
							FROM hemjbmh AS a
							LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
							-- gaji pokok
							LEFT JOIN (
							SELECT
								id_hemxxmh,
								tanggal_efektif,
								IFNULL(nominal, 0) AS nominal_gp
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
									AND tanggal_efektif < :tanggal_keluar
									AND is_active = 1
							) AS subquery
							WHERE row_num = 1
							) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh

							-- t jabatan
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
									AND tanggal_efektif < :tanggal_keluar
									AND is_active = 1
							) AS subquery
							WHERE row_num = 1
							) t_jabatan ON t_jabatan.id_hevxxmh = a.id_hevxxmh
							WHERE a.id_hemxxmh = :id_hemxxmh
							'
							);
			}
			// INSERT BULAN INI KE BPJS AGAR TIDAK KENA POTONGAN
			$qi_bpjs_tk_first = $db
				->raw()
				->bind(':tanggal_selesai', $tanggal_selesai)
				->exec('INSERT INTO bpjs_tk_exclude
						(
							id_hemxxmh,
							tanggal
						)
						SELECT
						' . $id_insert_hemx . ',
						DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 0 MONTH), "%Y-%m-01")
						;
						'
						);

			$qi_bpjs_kes_first = $db
				->raw()
				->bind(':tanggal_selesai', $tanggal_selesai)
				->exec('INSERT INTO bpjs_kes_exclude
						(
							id_hemxxmh,
							tanggal
						)
						SELECT
						' . $id_insert_hemx . ',
						DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 0 MONTH), "%Y-%m-01")
						;
						'
						);

			// insert ke BPJS Kesehatan
			$qi_bpjs_kes = $db
			->raw()
			->bind(':tanggal_selesai', $tanggal_selesai)
			->exec('INSERT INTO bpjs_kes_exclude
					(
						id_hemxxmh,
						tanggal
					)
					SELECT
					' . $id_insert_hemx . ',
						CASE
							WHEN DAY(:tanggal_selesai) > 20 THEN DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 2 MONTH), "%Y-%m-01")
							ELSE DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 1 MONTH), "%Y-%m-01")
						END AS Result;
					'
					);
					
			// insert ke BPJS TK
			$qi_bpjs_tk = $db
			->raw()
			->bind(':tanggal_selesai', $tanggal_selesai)
			->exec('INSERT INTO bpjs_tk_exclude
					(
						id_hemxxmh,
						tanggal
					)
					SELECT
					' . $id_insert_hemx . ',
						CASE
							WHEN DAY(:tanggal_selesai) > 20 THEN DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 1 MONTH), "%Y-%m-01")
							ELSE NULL
						END AS Result;
					'
					);

			// insert ke BPJS TK Old id_hemxxmh (NIK LAMA)
			$qi_bpjs_tk_old = $db
			->raw()
			->bind(':tanggal_selesai', $tanggal_selesai)
			->exec('INSERT INTO bpjs_tk_exclude
					(
						id_hemxxmh,
						tanggal
					)
					SELECT
					' . $id_hemxxmh . ',
						CASE
							WHEN DAY(:tanggal_selesai) <= 20 THEN DATE_FORMAT(DATE_ADD(:tanggal_selesai, INTERVAL 0 MONTH), "%Y-%m-01")
							ELSE NULL
						END AS Result;
					'
					);


		} else {
			$id_har = 3;
			
			$qi_hemjbrd = $db
			->query('insert', 'hemjbrd')
			->set('kode', $kode_hesxxtd)
			->set('id_harxxmh',$id_har)
			->set('is_email_status',1)
			->set('id_hemxxmh',$id_hemxxmh)
			->set('id_hesxxmh',$id_hesxxmh)
			->set('id_gcpxxmh_awal',$rs_hemjbmh['id_gcpxxmh'])
			->set('id_gcpxxmh_akhir',$rs_hemjbmh['id_gcpxxmh'])
			->set('id_gbrxxmh_awal',$rs_hemjbmh['id_gbrxxmh'])
			->set('id_gbrxxmh_akhir',$rs_hemjbmh['id_gbrxxmh'])
			->set('id_holxxmh_awal',$rs_hemjbmh['id_holxxmh'])
			->set('id_holxxmh_akhir',$rs_hemjbmh['id_holxxmh'])
			->set('id_hovxxmh_awal',$rs_hemjbmh['id_hovxxmh'])
			->set('id_hovxxmh_akhir',$rs_hemjbmh['id_hovxxmh'])
			->set('id_hodxxmh_awal',$rs_hemjbmh['id_hodxxmh'])
			->set('id_hodxxmh_akhir',$rs_hemjbmh['id_hodxxmh'])
			->set('id_hosxxmh_awal',$rs_hemjbmh['id_hosxxmh'])
			->set('id_hosxxmh_akhir',$rs_hemjbmh['id_hosxxmh'])
			->set('id_hobxxmh_awal',$rs_hemjbmh['id_hobxxmh'])
			->set('id_hobxxmh_akhir',$rs_hemjbmh['id_hobxxmh'])
			->set('id_hevxxmh_awal',$rs_hemjbmh['id_hevxxmh'])
			->set('id_hevxxmh_akhir',$rs_hemjbmh['id_hevxxmh'])
			->set('id_hetxxmh_awal',$rs_hemjbmh['id_hetxxmh'])
			->set('id_hetxxmh_akhir',$rs_hemjbmh['id_hetxxmh'])
			->set('grup_hk',$rs_hemjbmh['grup_hk'])
			->set('jumlah_grup',$rs_hemjbmh['jumlah_grup'])
			->set('tanggal_awal',$tanggal_mulai)
			->set('tanggal_akhir',$tanggal_selesai)
			->exec();

			$qu_hemxxmh = $db
				->query('update', 'hemjbmh')
				->set('tanggal_keluar', $tanggal_selesai)
				->where('id_hemxxmh', $id_hemxxmh )
				->exec();
		}

	}else {
		$qd_hemxxmh = $db
			->query('delete', 'hemxxmh')
			->where('kode', $nik_baru )
			->exec();

	}

	

?>