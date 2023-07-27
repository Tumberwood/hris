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

	$id_htlxxth = $_POST['id_transaksi_h'];
	$state = $_POST['state'];

	if($state == 1){

		try{
			$db->transaction();
			
			$qs_htlxxth = $db
				->query('select', 'htlxxth' )
				->get([
					'htlxxth.id as id_transaksi',
					'htlxxth.id_hemxxmh as id_hemxxmh',
					'htlxxth.id_htlxxmh as id_htlxxmh',
					'htlgrmh.id as id_htlgrmh',
					'htlxxth.kode as kode',
					'htlxxth.tanggal_awal as tanggal_awal',
					'htlxxth.tanggal_akhir as tanggal_akhir',
					'htlxxth.keterangan as keterangan',
					'htlxxmh.kode as htlxxmh_kode',
					'htlgrmh.kode as htlgrmh_kode'
				] )
				->join('hemxxmh','hemxxmh.id = htlxxth.id_hemxxmh','LEFT' )
				->join('htlxxmh','htlxxmh.id = htlxxth.id_htlxxmh','LEFT' )
				->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
				->where('htlxxth.id', $id_htlxxth )
				->exec();

			$rs_htlxxth = $qs_htlxxth->fetch();
			
			$tanggal_awal  = Carbon::parse($rs_htlxxth['tanggal_awal']);
			$tanggal_akhir = Carbon::parse($rs_htlxxth['tanggal_akhir']);

			$jumlah_hari = $tanggal_awal->diffInDays($tanggal_akhir) + 1;
			
			$tanggal = $tanggal_awal;
			$tt = '';
			for ($x = 0; $x < $jumlah_hari; $x++) {
				$tanggal_ymd = $tanggal;
				$qi = $db
					->query('insert', 'htlxxrh')
					->set('id_transaksi', $rs_htlxxth['id_transaksi'] )
					->set('id_htlgrmh', $rs_htlxxth['id_htlgrmh'] )
					->set('id_htlxxmh', $rs_htlxxth['id_htlxxmh'] )
					->set('id_hemxxmh', $rs_htlxxth['id_hemxxmh'] )
					->set('kode', $rs_htlxxth['kode'] )
					->set('is_approve', $state )
					->set('tanggal', $tanggal_ymd->format('Y-m-d') )
					->set('keterangan', $rs_htlxxth['keterangan'] )
					->set('jenis', 1 )
					->set('htlxxmh_kode', $rs_htlxxth['htlxxmh_kode'] )
					->set('htlgrmh_kode', $rs_htlxxth['htlgrmh_kode'] )
					->set('jumlah', 1 )
					->set('jam_awal', null )
					->set('jam_akhir', null )
					->exec();
				$tt = $tt . ' - ' . $tanggal_ymd->format('Y-m-d');
				$tanggal->add(1, 'days');
			}

			$db->commit();
			$data = array(
				'jumlah_hari'=>$jumlah_hari,
				'tt'=>$tt
			);
		}catch(PDOException $e){
			// rollback on error
			$db->rollback();
		}

	}elseif($state == 2){
		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htlxxth )
			->exec();

	}elseif($state == -9){
		$qu_htlxxrh = $db
			->query('update', 'htlxxrh')
			->set('is_approve', $state)
			->where('id_transaksi', $id_htlxxth )
			->exec();
	}
?>