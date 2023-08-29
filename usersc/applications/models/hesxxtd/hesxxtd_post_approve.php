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
				'hesxxtd.id_hemxxmh as id_hemxxmh',
				'hesxxtd.id_hesxxmh as id_hesxxmh',
				'hesxxtd.id_hesxxmh_tetap as id_hesxxmh_tetap',
				'hesxxtd.keterangan as keterangan',
				'hesxxtd.keputusan as keputusan',
				'hesxxtd.nik_baru as nik_baru'
			] )
			->where('hesxxtd.id', $id_hesxxtd )
			->exec();

		$rs_hesxxtd = $qs_hesxxtd->fetch();
		$keputusan = $rs_hesxxtd['keputusan'];
		$id_hemxxmh = $rs_hesxxtd['id_hemxxmh'];
		$id_hesxxmh = $rs_hesxxtd['id_hesxxmh'];
		$id_hesxxmh_tetap = $rs_hesxxtd['id_hesxxmh_tetap'];
		$nik_baru = $rs_hesxxtd['nik_baru'];

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
				'hemjbmh.tanggal_masuk as tanggal_masuk',
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
		
		$tanggal_masuk = $rs_hemjbmh['tanggal_masuk'];
		$tanggal_keluar = date('Y-m-d', strtotime($tanggal_masuk . ' +6 months'));
		// print_r($tanggal_masuk);
		// print_r($tanggal_keluar);

		//update non aktif hemxxmh
		// $qu_hemxxmh = $db
		// 	->query('update', 'hemxxmh')
		// 	->set('is_active', 0)
		// 	->where('id', $id_hemxxmh )
		// 	->exec();

		if ($keputusan != 'Terminasi') {
			//Insert ke Hemxxmh
			$qi_hemxxmh = $db
				->query('insert', 'hemxxmh')
				->set($rs_hemxxmh)
				->set('kode', $nik_baru )
				->exec();

			$id_insert_hemx = $qi_hemxxmh->insertId();

			if ($keputusan == 'Rekontrak') {
				$id_hesxxmh = 2;
			} else if ($keputusan == 'Kontrak') {
				$id_hesxxmh = 2;
			} else if ($keputusan == 'Perpanjangan Pelatihan') {
				$id_hesxxmh = 3;
			} else if ($keputusan == 'Tetap') {
				$id_hesxxmh = $id_hesxxmh_tetap;
			}
		
			$qi_hemxxmh_hemjbmh = $db
			->query('insert', 'hemjbmh')
			->set('id_hemxxmh',$id_insert_hemx)
			->set('id_hesxxmh',$id_hesxxmh)
			->set($rs_hemjbmh)
			->set('tanggal_keluar',$tanggal_keluar)
			->exec();
		
			$qi_hemxxmh_hemjbmh = $db
			->query('insert', 'hemjbrd')
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
			->set('tanggal_awal',$tanggal_masuk)
			->set('tanggal_akhir',$tanggal_keluar)
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