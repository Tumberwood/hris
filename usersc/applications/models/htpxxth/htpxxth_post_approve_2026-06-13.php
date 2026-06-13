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

			$qs_hasil_awal_akhir = $db
				->raw()
				->bind(':id_hemxxmh', $rs_htpxxth['id_hemxxmh'])
				->bind(':tanggal', $rs_htpxxth['tanggal'])
				->bind(':jam_awal', $rs_htpxxth['jam_awal'])
				->bind(':jam_akhir', $rs_htpxxth['jam_akhir'])
				->exec('SELECT
							DATE_FORMAT(
								CASE
									WHEN (TIME(:jam_awal) >= "22:00:00" AND TIME(:jam_awal) <= "23:59:59")
										OR (TIME(:jam_awal) >= "00:00:00" AND TIME(:jam_awal) <= "12:00:00" AND b.kode LIKE "malam%")
									THEN DATE_ADD(CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_awal)), INTERVAL 1 HOUR)
									ELSE DATE_ADD(CONCAT(a.tanggal, " ", :jam_awal), INTERVAL 1 HOUR)
								END,
								"%Y-%m-%d %H:%i:%s"
							) AS tanggaljam_awal_t2,
							DATE_FORMAT(DATE_ADD(CONCAT(a.tanggal, " ", :jam_akhir), INTERVAL -2 HOUR), "%Y-%m-%d %H:%i:%s") AS tanggaljam_akhir_t1
							
						FROM htssctd AS a
						LEFT JOIN htsxxmh AS b ON b.id = a.id_htsxxmh
						WHERE a.tanggal = :tanggal 
							AND a.id_hemxxmh = :id_hemxxmh
							AND a.is_active = 1
						'
						);
			$rs_hasil_awal_akhir = $qs_hasil_awal_akhir->fetch();

			if (in_array($rs_htpxxth['id_htpxxmh'], [1, 5])) {
				$qu_jadwal = $db
					->query('update', 'htssctd')
					->set('tanggaljam_awal_t2',$rs_hasil_awal_akhir['tanggaljam_awal_t2'])
					->where('id_hemxxmh',$rs_htpxxth['id_hemxxmh'])
					->where('tanggal',$rs_htpxxth['tanggal'])
				->exec();

			} else if (in_array($rs_htpxxth['id_htpxxmh'], [2, 6])) {
				$qu_jadwal = $db
					->query('update', 'htssctd')
					->set('tanggaljam_akhir_t1',$rs_hasil_awal_akhir['tanggaljam_akhir_t1'])
					->where('id_hemxxmh',$rs_htpxxth['id_hemxxmh'])
					->where('tanggal',$rs_htpxxth['tanggal'])
				->exec();
			}

	}elseif($state == 2){
		$qd_htlxxrh = $db
			->query('delete', 'htlxxrh')
			->where('id_transaksi', $id_htpxxth )
			->where('jenis', 2 )
			->exec();

	}elseif($state == -9){
		$qu_htlxxrh = $db
			->query('update', 'htlxxrh')
			->set('is_approve', $state)
			->where('id_transaksi', $id_htpxxth )
			->where('jenis', 2 )
			->exec();
	}

	

?>