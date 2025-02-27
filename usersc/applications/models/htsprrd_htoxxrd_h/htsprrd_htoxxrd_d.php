<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	if (isset($_POST['id_htsprrd_htoxxrd_h']) && $_POST['id_htsprrd_htoxxrd_h'] > 0) {
		$id_htsprrd_htoxxrd_h = $_POST['id_htsprrd_htoxxrd_h'];
	
		$qs_htsprrd_htoxxrd_h = $db
			->query('select', 'htsprrd_htoxxrd_h' )
			->get([
				'tanggal_awal as start_date',
				'tanggal_akhir as end_date'
			] )
			->where('id', $id_htsprrd_htoxxrd_h )
			->exec();
		$rs_htsprrd_htoxxrd_h = $qs_htsprrd_htoxxrd_h->fetch();
	
		$start_date = $rs_htsprrd_htoxxrd_h['start_date'];
		$end_date = $rs_htsprrd_htoxxrd_h['end_date'];
		
		$qs_detail_upload = $db
			->raw()
			->bind(':id_htsprrd_htoxxrd_h', $id_htsprrd_htoxxrd_h)
			->exec('SELECT
						a.id,
						a.id_hemxxmh,
						id_htsprrd_htoxxrd_h,
						a.kode,
						a.nama,
						a.lembur15,
						a.lembur2,
						a.lembur3,
						a.lembur4,
						a.makan
					FROM htsprrd_htoxxrd_d a
					WHERE a.id_htsprrd_htoxxrd_h = :id_htsprrd_htoxxrd_h
		');
		$dataRows = $qs_detail_upload->fetchAll();
		
		$qs_data_sql = $db
			->raw()
			->bind(':start_date', $start_date)
			->bind(':end_date', $end_date)
			->exec('SELECT
						a.id_hemxxmh,
						b.kode,
						b.nama,
						SUM(IFNULL(lembur15,0)) AS sum_lembur15,
						SUM(IFNULL(lembur2,0)) AS sum_lembur2,
						SUM(IFNULL(lembur3,0)) AS sum_lembur3,
						SUM(IFNULL(lembur4,0)) AS sum_lembur4,
						SUM(IFNULL(a.is_makan,0)) AS sum_makan
					FROM htsprrd a
					LEFT JOIN hemxxmh b ON b.id = a.id_hemxxmh
					WHERE a.tanggal BETWEEN :start_date AND :end_date AND a.is_active = 1
					GROUP BY a.id_hemxxmh
		');
		$rs_data_sql = $qs_data_sql->fetchAll();
	
		$sqlData = [];
		foreach ($rs_data_sql as $row) {
			$sqlData[$row['kode']] = $row;
		}
	
		$pivotData = array_map(function ($row) use ($sqlData) {
			$kode = $row['kode'];
			$sqlRow = $sqlData[$kode] ?? ["sum_lembur15" => 0, "sum_lembur2" => 0, "sum_lembur3" => 0, "sum_lembur4" => 0, "sum_makan" => 0];
			$total_xl = $row['lembur15'] + $row['lembur2'] + $row['lembur3'] + $row['lembur4'];
			$total_db = $sqlRow['sum_lembur15'] + $sqlRow['sum_lembur2'] + $sqlRow['sum_lembur3'] + $sqlRow['sum_lembur4'];
	
			if ($total_xl == $total_db) {
				$is_tidak_sesuai = 0;
			} else {
				$is_tidak_sesuai = 1;
			}
			
			return [
				"id"					=> $row['id'],
				"id_htsprrd_htoxxrd_h"	=> $row['id_htsprrd_htoxxrd_h'],
				"kode"        			=> $kode,
				"nama"        			=> $row['nama'],
				"lembur15_xl" 			=> $row['lembur15'],
				"lembur15_db" 			=> $sqlRow['sum_lembur15'],  
				"lembur2_xl"  			=> $row['lembur2'],
				"lembur2_db"  			=> $sqlRow['sum_lembur2'],   
				"lembur3_xl"  			=> $row['lembur3'],
				"lembur3_db"  			=> $sqlRow['sum_lembur3'],   
				"lembur4_xl"  			=> $row['lembur4'],
				"lembur4_db"  			=> $sqlRow['sum_lembur4'],   
				"makan_xl"    			=> $row['makan'],
				"makan_db"    			=> $sqlRow['sum_makan'],     

				"total_xl"    			=> $total_xl,
				"total_db"    			=> $total_db,
				"is_tidak_sesuai"    	=> $is_tidak_sesuai
			];
			
		}, $dataRows);
	} else {
		$pivotData = [];
	}
	

	$data = [
		"message" => "Upload berhasil!",
		"type_message" => "success",
		"lembur" => $pivotData  
	];
	require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>