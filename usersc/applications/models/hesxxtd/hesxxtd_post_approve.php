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

	if($state == 1){

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
				'hemxxmh.perkawinan as perkawinan'
			] )
			->where('hemxxmh.id', $id_hemxxmh )
			->exec();
		$rs_hemxxmh = $qs_hemxxmh->fetch();

		$qs_hemdcmh = $db
			->query('select', 'hemxxmh' )
			->get([
				'hemdcmh.ktp_no as ktp_no',
			] )
			->join('hemdcmh','hemdcmh.id_hemxxmh = hemxxmh.id','LEFT' )
			->where('hemxxmh.id', $id_hemxxmh )
			->exec();
		$rs_hemdcmh = $qs_hemdcmh->fetch();

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
				'hemjbmh.id_hbnxxmh as id_hbnxxmh',
				'hemjbmh.is_checkclock as is_checkclock',
				'hemjbmh.email_perusahaan as email_perusahaan',
				'hemjbmh.jenis_lembur as jenis_lembur',
				'hemjbmh.rekening_no as rekening_no',
				'hemjbmh.rekening_nama as rekening_nama',
				'hemjbmh.bpjskes_no as bpjskes_no',
				'hemjbmh.bpjstk_no as bpjstk_no',
				'hemjbmh.grup_hk as grup_hk',
			] )
			->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
			->where('hemxxmh.id', $id_hemxxmh )
			->exec();
		$rs_hemjbmh = $qs_hemjbmh->fetch();
		
		// $tanggal_masuk = $rs_hemjbmh['tanggal_masuk'];
		// $tanggal_keluar = date('Y-m-d', strtotime($tanggal_masuk . ' +6 months'));
		// print_r($tanggal_masuk);
		// print_r($tanggal_keluar);

		//update non aktif hemxxmh
		// $qu_hemxxmh = $db
		// 	->query('update', 'hemxxmh')
		// 	->set('is_active', 0)
		// 	->where('id', $id_hemxxmh )
		// 	->exec();

		if ($keputusan != 'Terminasi') {
			$is_htpr_no_hemxxmh = 0;
			if ($keputusan == 'Rekontrak') {
				$id_hesxxmh = 2;
			} else if ($keputusan == 'Kontrak') {
				$id_hesxxmh = 2;
				$is_htpr_no_hemxxmh = 1;

			} else if ($keputusan == 'Perpanjangan Pelatihan') {
				$id_hesxxmh = 3;
			} else if ($keputusan == 'Tetap') {
				$id_hesxxmh = $id_hesxxmh_tetap;
			}
			
			$qi_hemxxmh = $db
				->query('insert', 'hemxxmh')
				->set($rs_hemxxmh)
				->set('kode', $nik_baru )
				->exec();
			$id_insert_hemx = $qi_hemxxmh->insertId();

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

			
			$qi_hemjbmh = $db
			->query('insert', 'hemjbmh')
			->set('id_hemxxmh',$id_insert_hemx)
			->set('id_hesxxmh',$id_hesxxmh)
			->set($rs_hemjbmh)
			->set('tanggal_masuk',$tanggal_mulai)
			->set('tanggal_keluar',$tanggal_selesai)
			->exec();

			$qi_hemdcmh = $db
			->query('insert', 'hemdcmh')
			->set('id_hemxxmh',$id_insert_hemx)
			->set('ktp_no',$ktp_no)
			->exec();
		
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

			$qi_pola_shift = $db
				->query('insert', 'htsemtd')
				->set('id_hemxxmh',$id_insert_hemx)
				->set($rs_pola_shift)
				->exec();
			
		} else {
			if ($keputusan == 'Terminasi') {
				$id_har = 3;
			}  else if ($keputusan == 'Rekontrak') {
				$id_hesxxmh = 2;
				$id_har = 2;
			}
			
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
			->set('tanggal_awal',$tanggal_mulai)
			->set('tanggal_akhir',$tanggal_selesai)
			->exec();
		}

	}elseif($state == 2){
		$qd_hemxxmh = $db
			->query('delete', 'hemxxmh')
			->where('kode', $nik_baru )
			->exec();

	}elseif($state == -9){
		$qd_hemxxmh = $db
			->query('delete', 'hemxxmh')
			->where('kode', $nik_baru )
			->exec();
	}

	

?>