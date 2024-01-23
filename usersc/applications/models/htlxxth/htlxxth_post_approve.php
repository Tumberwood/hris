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
					'ifnull(htlgrmh.id, 0) as id_htlgrmh',
					'htlxxth.kode as kode',
					'htlxxth.tanggal_awal as tanggal_awal',
					'htlxxth.tanggal_akhir as tanggal_akhir',
					'htlxxth.keterangan as keterangan',
					'htlxxmh.kode as htlxxmh_kode',
					'ifnull(htlgrmh.kode, "") as htlgrmh_kode'
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
			
			$qs_htlxxmh = $db
				->query('select', 'htlxxmh' )
				->get(['is_potongcuti'] )
				->where('id', $rs_htlxxth['id_htlxxmh'] )
				->exec();
			$rs_htlxxmh = $qs_htlxxmh->fetch();

			if ($rs_htlxxmh['is_potongcuti'] == 1) {
				$qi_sisa_saldo = $db
					->raw()
					->bind(':id_hemxxmh', $rs_htlxxth['id_hemxxmh'])
					->bind(':id_transaksi', $rs_htlxxth['id_transaksi'])
					->bind(':tanggal_awal', $tanggal_awal)
					->exec(' INSERT INTO htlxxrh (
								id_transaksi,
								id_hemxxmh,
								nama,
								tanggal,
								saldo
							)
							SELECT
								:id_transaksi,
								a.id_hemxxmh,
								"sisa saldo cuti",
								:tanggal_awal,
								SUM(
									CASE
										WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0) + IFNULL(c_rd,0))
										ELSE 0
									END
								) AS sisa_saldo
							FROM htlxxrh AS a
							-- employee
							LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
							LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = peg.id
							-- Izin yang memotong Cuti
							LEFT JOIN (
								SELECT
									rh.id_hemxxmh,
									COUNT(rh.id) AS c_cb
								FROM htlxxrh AS rh
								LEFT JOIN htlxxmh AS mh ON mh.id = rh.id_htlxxmh
								WHERE YEAR(rh.tanggal) = YEAR(:tanggal_awal) AND rh.jenis = 1 AND mh.is_potongcuti = 1
								GROUP BY rh.id_hemxxmh
							) AS cb ON cb.id_hemxxmh = a.id_hemxxmh
							
							LEFT JOIN (
								SELECT
									id_hemxxmh,
									COUNT(a.id) AS c_rd
								FROM htsprrd AS a
								WHERE YEAR(a.tanggal) = YEAR(:tanggal_awal) AND a.status_presensi_in = "AL"
								GROUP BY id_hemxxmh
							) AS rd ON rd.id_hemxxmh = a.id_hemxxmh
							
							WHERE YEAR(a.tanggal) = YEAR(:tanggal_awal) AND jb.is_checkclock = 1 AND a.nama = "saldo" AND a.id_hemxxmh = :id_hemxxmh
							'
				);
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
			->where('jenis', 1 )
			->exec();

		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htlxxth )
			->where('nama', "sisa saldo cuti" )
			->exec();

	}elseif($state == -9){
		$qu_htlxxrh = $db
			->query('update', 'htlxxrh')
			->set('is_approve', $state)
			->where('id_transaksi', $id_htlxxth )
			->where('jenis', 1 )
			->exec();
		
		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htlxxth )
			->where('jenis', 1 )
			->exec();

		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htlxxth )
			->where('nama', "sisa saldo cuti" )
			->exec();
	}
?>