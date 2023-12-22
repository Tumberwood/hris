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
		->query('select', 'hesxxtd_hk' )
		->get([
			'hesxxtd_hk.kode as kode_hesxxtd',
			'hesxxtd_hk.id_hemxxmh as id_hemxxmh',
			'hesxxtd_hk.is_perubahan_hk as is_perubahan_hk',
			'hesxxtd_hk.grup_hk as grup_hk',
			'hesxxtd_hk.jumlah_grup as jumlah_grup',
			'hesxxtd_hk.tanggal_awal as tanggal_awal',
		] )
		->where('hesxxtd_hk.id', $id_hesxxtd )
		->join('hemxxmh','hemxxmh.id = hesxxtd_hk.id_hemxxmh','LEFT' )
		->exec();

	$rs_hesxxtd = $qs_hesxxtd->fetch();

	$kode_hesxxtd = $rs_hesxxtd['kode_hesxxtd'];
	$id_hemxxmh = $rs_hesxxtd['id_hemxxmh'];
	$is_perubahan_hk = $rs_hesxxtd['is_perubahan_hk'];
	$jumlah_grup = $rs_hesxxtd['jumlah_grup'];
	$tanggal_awal = $rs_hesxxtd['tanggal_awal']; 
	$tanggal_awal_min = date("Y-m-d", strtotime($tanggal_awal . " -1 day")); 
	
	if ($is_perubahan_hk == 1) {
		if ($rs_hesxxtd['grup_hk'] == 1) { //HK Inputan
			$grup_hk = 2; //HK OLD
			$terbilang = 5; //HK BARU
		} else {
			$grup_hk = 1;
			$terbilang = 6;
		}
	
		// ini old
		if ($grup_hk == 1) { 
			$dibaca = 5; // HK LAMA
		} else {
			$dibaca = 6;
		}
	} else {
		// ambil jumlah_grup OLD
		if ($rs_hesxxtd['jumlah_grup'] == 1) { //jumlah_grup Inputan
			$jumlah_grup = 2; //jumlah_grup OLD
			$terbilang = 3; //jumlah_grup BARU
		} else {
			$jumlah_grup = 1;
			$terbilang = 4;
		}
	
		// ini old
		if ($jumlah_grup == 1) { 
			$dibaca = 3; // jumlah_grup LAMA
		} else {
			$dibaca = 4;
		}
	}

	if($state == 1){
		// JIKA PERUBAHAN HK
		if ($is_perubahan_hk == 1) {
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

		} else {
			// JIKA PERUBAHAN JUMLAH GRUP
			$qi_hemjbrd = $db
				->query('insert', 'hemjbrd')
				->set('kode', $kode_hesxxtd)
				->set('id_harxxmh',5)
				->set('id_hemxxmh',$id_hemxxmh)
				->set('tanggal_awal',$tanggal_awal)
				->set('is_perubahan_jumlah_grup', 1)
				->set('jumlah_grup',$rs_hesxxtd['jumlah_grup']) // Grup Baru
				->set('Keterangan', "Dari " . $dibaca . " Grup Menjadi " . $terbilang . " Grup")
				->exec();
		
			$qu_hemxxmh = $db
				->query('update', 'hemjbmh')
				->set('jumlah_grup', $rs_hesxxtd['jumlah_grup'] ) //update Grup Baru ke hemjbmh
				->where('id_hemxxmh', $id_hemxxmh ) //update Grup Baru ke hemjbmh
				->exec();

		}
	} else if($state == 2) {
		if ($is_perubahan_hk == 1) {
			$qd_ = $db
				->query('delete', 'hemjbrd')
				->where('id_hemxxmh',$id_hemxxmh)
				->where('kode',$kode_hesxxtd)
				->where('is_from_hk', 1)
				->exec();
	
			$qu_hemxxmh = $db
				->query('update', 'hemjbmh')
				->set('grup_hk', $grup_hk ) //update HK Baru ke hemjbmh
				->where('id_hemxxmh', $id_hemxxmh ) //update HK Baru ke hemjbmh
				->exec();
		} else {
			$qd_ = $db
				->query('delete', 'hemjbrd')
				->where('id_hemxxmh',$id_hemxxmh)
				->where('kode',$kode_hesxxtd)
				->exec();
	
			$qu_hemxxmh = $db
				->query('update', 'hemjbmh')
				->set('jumlah_grup', $jumlah_grup ) //update HK Baru ke hemjbmh
				->where('id_hemxxmh', $id_hemxxmh ) //update HK Baru ke hemjbmh
				->exec();
		}
	}

	

?>