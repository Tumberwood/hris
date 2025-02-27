<?php
	require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
	require_once( "../../../../usersc/vendor/autoload.php" );
	use Carbon\Carbon;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Reader\Csv;
	use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

	// BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

	$file_mimes = array(
		'application/octet-stream', 
		'application/vnd.ms-excel', 
		'application/x-csv', 
		'text/x-csv', 
		'text/csv', 
		'application/csv', 
		'application/excel', 
		'application/vnd.msexcel', 
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
	);
	
	if(isset($_FILES['filename']['name']) && in_array($_FILES['filename']['type'], $file_mimes)) {
		
		$arr_file = explode('.', $_FILES['filename']['name']);
		$extension = end($arr_file);

        if('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} elseif('xls' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}
 
		$spreadsheet = $reader->load($_FILES['filename']['tmp_name']);
     
		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		
		
		$datakembar = 0;
		$dataupload = 0;
		$emptyPeg = array();

		$start_date_post =  new Carbon($_POST['start_date']);
		$start_date = $start_date_post->format('Y-m-d');

		$end_date_post =  new Carbon($_POST['end_date']);
		$end_date = $end_date_post->format('Y-m-d');

		if ($sheetData[1][0] == "no_induk") {
			$dataRows = [];
			for ($i = 3; $i < count($sheetData); $i++) {
				$dataRows[] = [
					"kode"      => strtoupper($sheetData[$i][0]),
					"nama"      => strtoupper($sheetData[$i][1]),
					"lembur15"  => strtoupper($sheetData[$i][2]),
					"lembur2"   => strtoupper($sheetData[$i][3]),
					"lembur3"   => strtoupper($sheetData[$i][4]),
					"lembur4"   => strtoupper($sheetData[$i][5]),
					"makan"     => strtoupper($sheetData[$i][6])
				];
			}

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

				return [
					"kode"        => $kode,
					"nama"        => $row['nama'],
					"lembur15_xl" => $row['lembur15'],
					"lembur15_db" => $sqlRow['sum_lembur15'],  
					"lembur2_xl"  => $row['lembur2'],
					"lembur2_db"  => $sqlRow['sum_lembur2'],   
					"lembur3_xl"  => $row['lembur3'],
					"lembur3_db"  => $sqlRow['sum_lembur3'],   
					"lembur4_xl"  => $row['lembur4'],
					"lembur4_db"  => $sqlRow['sum_lembur4'],   
					"makan_xl"    => $row['makan'],
					"makan_db"    => $sqlRow['sum_makan'],     
				
					"total_xl"    => $row['lembur15'] + $row['lembur2'] + $row['lembur3'] + $row['lembur4'],
					"total_db"    => $sqlRow['sum_lembur15'] + $sqlRow['sum_lembur2'] + $sqlRow['sum_lembur3'] + $sqlRow['sum_lembur4']
				];
				
			}, $dataRows);

			$data = [
				"message" => "Upload berhasil!",
				"type_message" => "success",
				"lembur" => $pivotData  
			];
		} else {
			$data = array(
				"message" => "Template file salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Format file salah!",
			"type_message" => "danger"
		);
	}
	
	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>