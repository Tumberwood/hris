<?php
	/**
	 * 
	 */

	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
	
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$qs_htoemtd = $db
		->query('select', 'htoemtd' )
		->get([
			'hemxxmh.kode_finger as hemxxmh_kode_finger',
			'concat(hemxxmh.kode, " - ",hemxxmh.nama) as hemxxmh_data',
			'hodxxmh.nama as hodxxmh_nama',
			'hetxxmh.nama as hetxxmh_nama',
			'htoemtd.id_hemxxmh as id_hemxxmh',
			'htotpmh.kode as htotpmh_kode',
			'htoxxth.tanggal as tanggal',
			'htoemtd.jam_awal as jam_awal',
			'htoemtd.jam_akhir as jam_akhir'
		] )
		->join('htoxxth','htoxxth.id = htoemtd.id_htoxxth','LEFT JOIN' )
		->join('htotpmh','htotpmh.id = htoemtd.id_htotpmh','LEFT JOIN' )
		->join('hemxxmh','hemxxmh.id = htoemtd.id_hemxxmh','LEFT JOIN' )
		->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT JOIN' )
		->join('hetxxmh','hetxxmh.id = hemjbmh.id_hetxxmh','LEFT JOIN' )
		->join('hodxxmh','hodxxmh.id = hemjbmh.id_hodxxmh','LEFT JOIN' )
		->where('htoxxth.tanggal', $start_date, '>=' )
		->where('htoxxth.tanggal', $end_date, '<=' )
		->order('htoxxth.tanggal')
		->exec();
	
	$rs_htoemtd = $qs_htoemtd->fetchAll();
	
	if( count($rs_htoemtd) > 0){

		// declare variable
		$data = array();
		$columns = array();

		// BEGIN loop $rs_htoemtd
		foreach ($rs_htoemtd as $row) {
			$id_hemxxmh   = $row['id_hemxxmh'];
			$kode_finger  = $row['hemxxmh_kode_finger'];
			$hemxxmh_data = $row['hemxxmh_data'];
			$hodxxmh_nama = $row['hodxxmh_nama'];
			$hetxxmh_nama = $row['hetxxmh_nama'];
			$hetxxmh_nama = $row['hetxxmh_nama'];
			$htotpmh_kode = $row['htotpmh_kode'];
			$tanggal_in   = 'i__' . $row['tanggal'];
			$tanggal_out  = 'o__' . $row['tanggal'];

			$qs_htoemtd_sum_in = $db
				->query('select', 'htoemtd' )
				->get([
					'htoemtd.jam_awal as jam_awal',
					'COUNT(htoemtd.jam_awal) as c_jam_awal'
				] )
				->join('htoxxth','htoxxth.id = htoemtd.id_htoxxth','LEFT JOIN' )
				->where('htoemtd.id_hemxxmh', $id_hemxxmh )
				->where('htoxxth.tanggal', $start_date, '>=' )
				->where('htoxxth.tanggal', $end_date, '<=' )
				->group_by('htoemtd.jam_awal')
				->exec();
			
			$rs_htoemtd_sum_in = $qs_htoemtd_sum_in->fetchAll();
			
			$in_hl = 0;

			foreach ($rs_htoemtd_sum_in as $row_htoemtd_sum_in) {
				switch ($row_htoemtd_sum_in['jam_awal']) {
					case 'HL':
						$in_hl = $row_htoemtd_sum_in['c_jam_awal'];
						break;
					}	
			}

			$qs_htoemtd_sum_out = $db
				->query('select', 'htoemtd' )
				->get([
					'htoemtd.jam_akhir as jam_akhir',
					'COUNT(htoemtd.jam_akhir) as c_jam_akhir'
				] )
				->join('htoxxth','htoxxth.id = htoemtd.id_htoxxth','LEFT JOIN' )
				->where('htoemtd.id_hemxxmh', $id_hemxxmh )
				->where('htoxxth.tanggal', $start_date, '>=' )
				->where('htoxxth.tanggal', $end_date, '<=' )
				->group_by('htoemtd.jam_akhir')
				->exec();

			$rs_htoemtd_sum_out = $qs_htoemtd_sum_out->fetchAll();

			$out_hl = 0; 

			foreach ($rs_htoemtd_sum_out as $row_htoemtd_sum_out) {
				switch ($row_htoemtd_sum_out['jam_akhir']) {
					case 'HL':
						$out_hl = $row_htoemtd_sum_out['c_jam_akhir'];
						break;
					}	
			}

			$sum_hl = ($in_hl  +  $out_hl) / 2;		

			// Add kode and nama to data array if not already present
			if (!isset($data[$id_hemxxmh])) {
				$data[$id_hemxxmh] = array(
					'hemxxmh_kode_finger'   => $kode_finger,
					'hemxxmh_data'          => $hemxxmh_data,
					'hodxxmh_nama'          => $hodxxmh_nama,
					'hetxxmh_nama'          => $hetxxmh_nama,
					'htotpmh_kode'          => $htotpmh_kode,
					'HL'        		    => $sum_hl
				);
			}
			
			// Add status_presensi to data array under corresponding tanggal key
			$data[$id_hemxxmh][$tanggal_in] = $row['jam_awal'];
			$data[$id_hemxxmh][$tanggal_out] = $row['jam_akhir'];
		
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
			}else{
				$columns[] = array(
					'data' => '',
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
			}else{
				$columns[] = array(
					'data' => '',
					'name' => 'o__' . date('d M Y', strtotime($row['tanggal']))
				);
			}

		}
		
		// Result yang dikirim ke ajax
		$results = array(
			'data' => array_values($data),
			'columns' => array_merge(
				array(
					array('data' => 'hemxxmh_kode_finger', 'name' => 'kode_finger'),
					array('data' => 'hemxxmh_data', 'name' => 'hemxxmh_data'),
					array('data' => 'hodxxmh_nama', 'name' => 'hodxxmh_nama'),
					array('data' => 'hetxxmh_nama', 'name' => 'hetxxmh_nama'),
					// array('data' => 'htotpmh_kode', 'name' => 'htotpmh_kode'),
					array('data' => 'HL', 'name' => 'HL')
				),
				$columns
			),
			'debug' => $debug
		);

	}else{
		$results = array(
			'data' => [],
			'columns' => [],
			'debug' => $debug
		);
	}

	echo json_encode($results);
?>