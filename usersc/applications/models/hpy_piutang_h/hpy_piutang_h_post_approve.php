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
	
	$id_hpy_piutang_h = $_POST['id_transaksi_h'];
	$state = $_POST['state'];

	if($state == 1){
		$qs_piutang = $db
			->query('select', 'hpy_piutang_h' )
			->get([
					'id_hemxxmh',
					'id_hpcxxmh',
					'keterangan',
					'cicilan_per_bulan',
					'cicilan_terakhir',
					'tanggal_mulai',
					'tanggal_akhir',
					'tenor'
			] )
			->where('id', $id_hpy_piutang_h )
			->exec();
		$rs_piutang = $qs_piutang->fetch();

		$tanggal_mulai = $rs_piutang['tanggal_mulai'];

		for ($i=0; $i < $rs_piutang['tenor']; $i++) { 
	
			$tanggal = date('Y-m-d', strtotime($tanggal_mulai . ' + ' . $i . ' months'));

			if ($i == ($rs_piutang['tenor'] - 1)) {
				$nominal = $rs_piutang['cicilan_terakhir'];
			} else {
				$nominal = $rs_piutang['cicilan_per_bulan'];
			}

			if ($rs_piutang['tenor'] == 1) {
				$nominal = $rs_piutang['cicilan_per_bulan'];
			} else {
				$nominal = $nominal;
			}

			$qi_hpy_piutang_d = $db
				->query('insert', 'hpy_piutang_d')
				->set('id_hemxxmh',$rs_piutang['id_hemxxmh'])
				->set('id_hpcxxmh',$rs_piutang['id_hpcxxmh'])
				->set('id_hpy_piutang_h',$id_hpy_piutang_h)
				->set('keterangan',$rs_piutang['keterangan'])
				->set('nominal',$nominal)
				->set('is_approve', 1)
				->set('tanggal',$tanggal)
				->exec();
		}
	}
	else {
		$qd_piutang = $db
			->query('delete', 'hpy_piutang_d')
			->where('id_hpy_piutang_h',$id_hpy_piutang_h)
			->exec();
	}
	

?>