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
			->query('select', 'hesxxtd_hk' )
			->get([
				'hesxxtd_hk.kode as kode_hesxxtd',
				'hesxxtd_hk.id_hemxxmh as id_hemxxmh',
				'hesxxtd_hk.grup_hk as grup_hk',
				'hesxxtd_hk.tanggal_awal as tanggal_awal',
			] )
			->where('hesxxtd_hk.id', $id_hesxxtd )
			->join('hemxxmh','hemxxmh.id = hesxxtd_hk.id_hemxxmh','LEFT' )
			->exec();

		$rs_hesxxtd = $qs_hesxxtd->fetch();

		// ambil HK OLD
		if ($rs_hesxxtd['grup_hk'] == 1) {
			$grup_hk = 2;
			$terbilang = 5;
		} else {
			$grup_hk = 1;
			$terbilang = 6;
		}

		// ini old
		if ($grup_hk == 1) {
			$dibaca = 5;
		} else {
			$dibaca = 6;
		}

		$kode_hesxxtd = $rs_hesxxtd['kode_hesxxtd'];
		$id_hemxxmh = $rs_hesxxtd['id_hemxxmh'];
		$tanggal_awal = $rs_hesxxtd['tanggal_awal']; 
		$tanggal_awal_min = date("Y-m-d", strtotime($tanggal_awal . " -1 day")); 
		
		$qi_hemjbrd = $db
		->query('insert', 'hemjbrd')
		->set('kode', $kode_hesxxtd)
		->set('id_harxxmh',5)
		->set('id_hemxxmh',$id_hemxxmh)
		->set('tanggal_awal',$tanggal_awal)
		->set('is_from_hk', 1)
		->set('grup_hk',$rs_hesxxtd['grup_hk']) // HK Baru
		->set('Keterangan', "Dari " . $dibaca . " HK Menjadi " . $terbilang . " HK")
		->exec();

		$qu_hemxxmh = $db
			->query('update', 'hemjbmh')
			->set('grup_hk', $rs_hesxxtd['grup_hk'] ) //update HK Baru ke hemjbmh
			->where('id_hemxxmh', $id_hemxxmh ) //update HK Baru ke hemjbmh
			->exec();

	}

	

?>