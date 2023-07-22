<?php
	/**
	 * Kolom :
	 * 	- dari htlgrmh, dan 
	 * 	- HK		: Hari Kerja
	 * 	- OFF		: Jadwal Off
	 * 	- NJ		: Jadwal Belum Dibuat
	 */

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$start_date = $_POST['start_date'];
	$end_date 	= $_POST['end_date'];

	$awal 			= new Carbon($_POST['start_date']);
	$akhir 			= new Carbon($_POST['end_date']);
	$jumlah_hari 	= $awal->diffInDays($akhir) + 1;
	

	$qs_htpxxth = $db
		->query('select', 'htpxxth' )
		->get([
			'hemxxmh.kode_finger as hemxxmh_kode_finger',
			'concat(hemxxmh.kode, " - ",hemxxmh.nama) as hemxxmh_data',
			'hodxxmh.nama as hodxxmh_nama',
			'hetxxmh.nama as hetxxmh_nama',
			'htpxxth.id_hemxxmh as id_hemxxmh',
			'htpxxth.tanggal as tanggal',
			'IF(htpxxth.jam_awal IS NULL,htpxxth.jam_awal,"") as status_presensi_in',
			'IF(htpxxth.jam_akhir IS NULL,htpxxth.jam_akhir,"") as status_presensi_out'
		] )
		->join('hemxxmh','hemxxmh.id = htpxxth.id_hemxxmh','LEFT' )
		->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
		->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT' )
		->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT' )
		->where('htpxxth.tanggal', $start_date, '>=' )
		->where('htpxxth.tanggal', $end_date, '<=' )
		->order('htpxxth.tanggal')
		->exec();
	$rs_htpxxth = $qs_htpxxth->fetchAll();

	$qs_htsprrd = $db
		->query('select', 'htsprrd' )
		->get([
			'hemxxmh.kode_finger as hemxxmh_kode_finger',
			'concat(hemxxmh.kode, " - ",hemxxmh.nama) as hemxxmh_data',
			'hodxxmh.nama as hodxxmh_nama',
			'hetxxmh.nama as hetxxmh_nama',
			'htsprrd.id_hemxxmh as id_hemxxmh',
			'htsprrd.tanggal as tanggal',
			'IF(htsprrd.status_presensi_in = "HK",TIME(htsprrd.clock_in),htsprrd.status_presensi_in) as status_presensi_in',
			'IF(htsprrd.status_presensi_out = "HK",TIME(htsprrd.clock_out),htsprrd.status_presensi_out) as status_presensi_out'
		] )
		->join('hemxxmh','hemxxmh.id = htsprrd.id_hemxxmh','LEFT' )
		->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
		->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT' )
		->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT' )
		->where('htsprrd.tanggal', $start_date, '>=' )
		->where('htsprrd.tanggal', $end_date, '<=' )
		->order('htsprrd.tanggal')
		->exec();
	$rs_htsprrd_presensi = $qs_htsprrd->fetchAll();

	$rs_htsprrd = array_merge($rs_htpxxth, $rs_htsprrd_presensi);
	
	// if( count($rs_htsprrd) > 0){
	if( !empty($rs_htsprrd) ){

		// Initialize variables to hold transformed data
		$data = array();
		$columns = array();

		// Loop through original array and transform data
		foreach ($rs_htsprrd as $row) {
			$id_hemxxmh   = $row['id_hemxxmh'];
			$kode_finger  = $row['hemxxmh_kode_finger'];
			$hemxxmh_data = $row['hemxxmh_data'];
			$hodxxmh_nama = $row['hodxxmh_nama'];
			$hetxxmh_nama = $row['hetxxmh_nama'];
			$tanggal_in   = 'i__' . $row['tanggal'];
			$tanggal_out  = 'o__' . $row['tanggal'];

			$qs_htsprrd_sum_in = $db
				->query('select', 'htsprrd' )
				->get([
					'htsprrd.status_presensi_in as status_presensi_in',
					'COUNT(htsprrd.status_presensi_in) as c_status_presensi_in'
				] )
				->where('htsprrd.id_hemxxmh', $id_hemxxmh )
				->where('htsprrd.tanggal', $start_date, '>=' )
				->where('htsprrd.tanggal', $end_date, '<=' )
				->group_by('htsprrd.status_presensi_in')
				->exec();
			
			$rs_htsprrd_sum_in = $qs_htsprrd_sum_in->fetchAll();
			
			$in_hk = 0;
			$in_hl = 0;
			$in_cb = 0;
			$in_ct = 0;
			$in_ik = 0;
			$in_al = 0;
			$in_sd = 0;
			$in_sk = 0;
			$in_ip = 0;
			$in_off = 0;
			$in_nj = 0;

			$in_l 	= 0;
			$in_pa 	= 0;

			foreach ($rs_htsprrd_sum_in as $row_htsprrd_sum_in) {
				switch ($row_htsprrd_sum_in['status_presensi_in']) {
					case 'HK':
						$in_hk = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'HL':
						$in_hl = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'CB':
						$in_cb = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'CT':
						$in_ct = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'CK':
						$in_ik = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'AL':
						$in_al = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'SD':
						$in_sd = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'SK':
						$in_sk = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'IP':
						$in_ip = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;

					case 'OFF':
						$in_off = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					case 'NJ':
						$in_nj = $row_htsprrd_sum_in['c_status_presensi_in'];
						break;
					
					}	
			}

			$qs_htsprrd_sum_out = $db
				->query('select', 'htsprrd' )
				->get([
					'htsprrd.status_presensi_out as status_presensi_out',
					'COUNT(htsprrd.status_presensi_out) as c_status_presensi_out'
				] )
				->where('htsprrd.id_hemxxmh', $id_hemxxmh )
				->where('htsprrd.tanggal', $start_date, '>=' )
				->where('htsprrd.tanggal', $end_date, '<=' )
				->group_by('htsprrd.status_presensi_out')
				->exec();

			$rs_htsprrd_sum_out = $qs_htsprrd_sum_out->fetchAll();

			$out_hk = 0;
			$out_hl = 0; 
			$out_cb = 0;
			$out_ct = 0;
			$out_ik = 0;
			$out_al = 0;
			$out_sd = 0;
			$out_sk = 0;
			$out_ip = 0;
			$out_off = 0;
			$out_nj = 0;

			$out_l 	= 0;
			$out_pa	= 0;

			foreach ($rs_htsprrd_sum_out as $row_htsprrd_sum_out) {
				switch ($row_htsprrd_sum_out['status_presensi_out']) {
					case 'HK':
						$out_hk = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'HL':
						$out_hl = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'CB':
						$out_cb = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'CT':
						$out_ct = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'CK':
						$out_ik = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'AL':
						$out_al = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'SD':
						$out_sd = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'SK':
						$out_sk = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'IP':
						$out_ip = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;

					case 'OFF':
						$out_off = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					case 'NJ':
						$out_nj = $row_htsprrd_sum_out['c_status_presensi_out'];
						break;
					
					}	
			}

			$sum_hk = ($in_hk  +  $out_hk) / 2;
			$sum_hl = ($in_hl  +  $out_hl) / 2;
			$sum_cb = ($in_cb  +  $out_cb) / 2;
			$sum_ct = ($in_ct  +  $out_ct) / 2;
			$sum_ik = ($in_ik  +  $out_ik) / 2;
			$sum_al = ($in_al  +  $out_al) / 2;
			$sum_sd = ($in_sd  +  $out_sd) / 2;
			$sum_sk = ($in_sk  +  $out_sk) / 2;
			$sum_ip = ($in_ip  +  $out_ip) / 2;

			$sum_off = ($in_off  +  $out_off) / 2;
			$sum_nj = ($in_nj  +  $out_nj) / 2;
			
			$sum_calc = $sum_hk + $sum_hl + $sum_cb + $sum_ct + $sum_ik + $sum_al + $sum_sd + $sum_sk + $sum_ip + $sum_off;
			//$sum_nj
			
			$cek = $jumlah_hari - $sum_calc;

			// Add kode and nama to data array if not already present
			if (!isset($data[$id_hemxxmh])) {
				$data[$id_hemxxmh] = array(
					'hemxxmh_kode_finger'   => $kode_finger,
					'hemxxmh_data'          => $hemxxmh_data,
					'hodxxmh_nama'          => $hodxxmh_nama,
					'hetxxmh_nama'          => $hetxxmh_nama,
					'HR'          			=> $jumlah_hari,
					'Cek'					=> $cek,
					'HK'        		    => $sum_hk,
					'OFF'        		    => $sum_off,
					'NJ'        		    => $sum_nj,
					'HL'        		    => $sum_hl,
					'CB'        		    => $sum_cb,
					'CT'        		    => $sum_ct,
					'IK'        		    => $sum_ik,
					'SD'        		    => $sum_sd,
					'SK'        		    => $sum_sk,
					'IP'        		    => $sum_ip,
					'AL'        		    => $sum_al
				);
			}
			
			// Add status_presensi to data array under corresponding tanggal key
			$data[$id_hemxxmh][$tanggal_in] = $row['status_presensi_in'];
			$data[$id_hemxxmh][$tanggal_out] = $row['status_presensi_out'];
		
			// Add tanggal to columns array if not already present
			$columnInFound = false;
			foreach ($columns as $column) {
				if ($column['data'] == $tanggal_in) {
					$columnInFound = true;
					break;
				}
			}
			if (!$columnInFound) {
				$columns[] = array(
					'data' => $tanggal_in,
					'name' => 'i__' . date('d M Y', strtotime($row['tanggal']))
				);
			}

			$columnOutFound = false;
			foreach ($columns as $column) {
				if ($column['data'] == $tanggal_out) {
					$columnOutFound = true;
					break;
				}
			}
			if (!$columnOutFound) {
				$columns[] = array(
					'data' => $tanggal_out,
					'name' => 'o__' . date('d M Y', strtotime($row['tanggal']))
				);
			}
		}

		// Sort columns by tanggal key
		// usort($columns, function($a, $b) {
		// 	return strtotime($a['data']) - strtotime($b['data']);
		// });

		// Combine data and columns arrays into final results array
		$results = array(
			'data' => array_values($data),
			'columns' => array_merge(
				array(
					array('data' => 'hemxxmh_kode_finger', 'name' => 'kode_finger'),
					array('data' => 'hemxxmh_data', 'name' => 'hemxxmh_data'),
					array('data' => 'hodxxmh_nama', 'name' => 'hodxxmh_nama'),
					array('data' => 'hetxxmh_nama', 'name' => 'hetxxmh_nama'),
					array('data' => 'HR', 'name' => 'HR'),
					array('data' => 'Cek', 'name' => 'Cek'),
					array('data' => 'NJ', 'name' => 'NJ'),
					array('data' => 'HK', 'name' => 'HK'),
					array('data' => 'OFF', 'name' => 'OFF'),
					array('data' => 'HL', 'name' => 'HL'),
					array('data' => 'CB', 'name' => 'CB'),
					array('data' => 'CT', 'name' => 'CT'),
					array('data' => 'IK', 'name' => 'IK'),
					array('data' => 'SD', 'name' => 'SD'),
					array('data' => 'SK', 'name' => 'SK'),
					array('data' => 'IP', 'name' => 'IP'),
					array('data' => 'AL', 'name' => 'AL')
				),
				$columns
			)
		);

	}else{
		$results = array(
			'data' => [],
			'columns' => []
		);
	}

	echo json_encode($results);
?>