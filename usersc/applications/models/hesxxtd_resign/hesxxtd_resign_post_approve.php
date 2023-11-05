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
			->query('select', 'hesxxtd_resign' )
			->get([
				'hesxxtd_resign.kode as kode_hesxxtd',
				'hesxxtd_resign.id_hemxxmh as id_hemxxmh',
				'hesxxtd_resign.tanggal_selesai as tanggal_selesai',
			] )
			->where('hesxxtd_resign.id', $id_hesxxtd )
			->join('hemxxmh','hemxxmh.id = hesxxtd_resign.id_hemxxmh','LEFT' )
			->exec();

		$rs_hesxxtd = $qs_hesxxtd->fetch();

		$kode_hesxxtd = $rs_hesxxtd['kode_hesxxtd'];
		$id_hemxxmh = $rs_hesxxtd['id_hemxxmh'];
		$tanggal_selesai = $rs_hesxxtd['tanggal_selesai'];

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
		
		$qi_hemjbrd = $db
		->query('insert', 'hemjbrd')
		->set('kode', $kode_hesxxtd)
		->set('id_harxxmh',4)
		->set('is_email_status',1)
		->set('id_hemxxmh',$id_hemxxmh)
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
		->set('tanggal_akhir',$tanggal_selesai)
		->exec();

		$qu_hemxxmh = $db
			->query('update', 'hemjbmh')
			->set('tanggal_keluar', $tanggal_selesai)
			->where('id_hemxxmh', $id_hemxxmh )
			->exec();

	}

	

?>