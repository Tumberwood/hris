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
	
	$id_htpxxth = $_POST['id_transaksi_h'];
	$state = $_POST['state'];

	if($state == 1){

		$qs_htpxxth = $db
			->query('select', 'htpxxth' )
			->get([
				'htpxxth.id as id_transaksi',
				'htpxxth.id_hemxxmh as id_hemxxmh',
				'htpxxth.id_htpxxmh as id_htpxxmh',
				'htpxxth.kode as kode',
				'htpxxth.tanggal as tanggal',
				'htpxxth.keterangan as keterangan',
				'htpxxth.jam_awal as jam_awal',
				'htpxxth.jam_akhir as jam_akhir',
				'htpxxmh.kode as htpxxmh_kode',
				'htlgrmh.id as id_htlgrmh',
				'htlgrmh.kode as htlgrmh_kode'
			] )
			->join('hemxxmh','hemxxmh.id = htpxxth.id_hemxxmh','LEFT' )
			->join('htpxxmh','htpxxmh.id = htpxxth.id_htpxxmh','LEFT' )
			->join('htlgrmh','htlgrmh.id = htpxxmh.id_htlgrmh','LEFT' )
			->where('htpxxth.id', $id_htpxxth )
			->exec();

		$rs_htpxxth = $qs_htpxxth->fetch();
		
		// $tanggal  = Carbon::parse($rs_htpxxth['tanggal']);
			
		$qi = $db
			->query('insert', 'htlxxrh')
			->set('id_transaksi', $rs_htpxxth['id_transaksi'] )
			->set('id_htlgrmh', $rs_htpxxth['id_htlgrmh'] )
			->set('id_htlxxmh', $rs_htpxxth['id_htpxxmh'] )
			->set('id_hemxxmh', $rs_htpxxth['id_hemxxmh'] )
			->set('kode', $rs_htpxxth['kode'] )
			->set('is_approve', $state )
			// ->set('tanggal', $tanggal->format('Y-m-d') )
			->set('tanggal', $rs_htpxxth['tanggal'] )
			->set('keterangan', $rs_htpxxth['keterangan'] )
			->set('jenis', 2 )
			->set('htlxxmh_kode', $rs_htpxxth['htpxxmh_kode'] )
			->set('htlgrmh_kode', $rs_htpxxth['htlgrmh_kode'] )
			->set('jumlah', 1 )
			->set('jam_awal', $rs_htpxxth['jam_awal'] )
			->set('jam_akhir', $rs_htpxxth['jam_akhir'] )
			->exec();
	}elseif($state == 2){
		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htpxxth )
			->exec();

	}elseif($state == -9){
		$qu_htlxxrh = $db
			->query('update', 'htlxxrh')
			->set('is_approve', $state)
			->where('id_transaksi', $id_htpxxth )
			->exec();
	}

	

?>