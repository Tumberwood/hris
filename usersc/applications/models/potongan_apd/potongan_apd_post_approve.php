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
	
	$id_pot_apd = $_POST['id_transaksi_h'];
	$state = $_POST['state'];

	if($state == 1){
		$qs_piutang = $db
			->raw()
			->bind(':id_pot_apd', $id_pot_apd)
			->exec(' SELECT
						id_hemxxmh,
						id_hpcxxmh,
						a.keterangan,
						tanggal,
						nominal
					FROM potongan_apd AS a
					LEFT JOIN hpcxxmh AS b ON b.id = a.id_hpcxxmh
					WHERE a.id = :id_pot_apd
					'
					);
		$rs_piutang = $qs_piutang->fetch();


		$qi_hpy_piutang_d = $db
			->query('insert', 'hpy_piutang_d')
			->set('id_hemxxmh',$rs_piutang['id_hemxxmh'])
			->set('id_hpcxxmh',$rs_piutang['id_hpcxxmh'])
			->set('keterangan',$rs_piutang['keterangan'])
			->set('nominal',$rs_piutang['nominal'])
			->set('is_approve', 1)
			->set('tanggal',$rs_piutang['tanggal'])
			->exec();
	}

	

?>