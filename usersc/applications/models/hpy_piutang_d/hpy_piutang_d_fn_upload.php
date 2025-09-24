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
		$sheetNames = $spreadsheet->getSheetNames(); // Ambil semua nama sheet

		foreach ($sheetNames as $sheetIndex => $sheetName) {
			$sheetData = $spreadsheet->getSheet($sheetIndex)->toArray();
			
			if ($sheetData[0][0] == "NRP") {
				for ($i = 1; $i < count($sheetData); $i++) {
					$kode      = strtoupper($sheetData[$i][0]);
					$tanggal_excel = new Carbon($sheetData[$i]['1']); //gunakan carbon untuk ambil data tanggal
					$tanggal = $tanggal_excel->format('Y-m-d'); //format jadi 2023-09-12
					$nominal  = strtoupper($sheetData[$i][2]);
					$komponen   = strtoupper($sheetData[$i][3]);
					$penambah_pengurang   = ucwords($sheetData[$i][4]);
					
					$qs_hemxxmh = $db
						->query('select', 'hemxxmh' )
						->get(['id'] )
						->where('kode', $kode )
						->exec();
					$rs_hemxxmh = $qs_hemxxmh->fetch();
					if (empty($rs_hemxxmh)) {
						$id_hemxxmh = 0;
					} else {
						$id_hemxxmh = $rs_hemxxmh['id'];
					}

					$qs_hpcxxmh = $db
						->query('select', 'hpcxxmh' )
						->get(['id'] )
						->where('nama', $komponen )
						->exec();
					$rs_hpcxxmh = $qs_hpcxxmh->fetch();
					if (empty($rs_hpcxxmh)) {
						$id_hpcxxmh = 0;
					} else {
						$id_hpcxxmh = $rs_hpcxxmh['id'];
					}

					$qi_hpy_piutang_d = $db
						->query('insert', 'hpy_piutang_d')
						->set('id_hemxxmh',$id_hemxxmh)
						->set('id_hpcxxmh',$id_hpcxxmh)
						->set('nominal',$nominal)
						->set('tanggal',$tanggal)
						->exec();
				}

				$data = [
					"message" => "Upload berhasil!",
					"type_message" => "success",
				];
			} else {
				$data = array(
					"message" => "Template file Salah!",
					"type_message" => "danger"
				);
			}
		}
	}else{
		$data = array(
			"message" => "Format file Salah!",
			"type_message" => "danger"
		);
	}
	
	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>