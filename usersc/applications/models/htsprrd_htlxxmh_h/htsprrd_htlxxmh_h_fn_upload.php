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
		$id_htsprrd_htlxxmh_h = $_POST['id_htsprrd_htlxxmh_h'];

		foreach ($sheetNames as $sheetIndex => $sheetName) {
			$sheetData = $spreadsheet->getSheet($sheetIndex)->toArray();
			
			if ($sheetData[1][0] == "no_induk") {
				for ($i = 3; $i < count($sheetData); $i++) {
					$kode      = strtoupper($sheetData[$i][0]);
					$nama      = strtoupper($sheetData[$i][1]);
					$al  = strtoupper($sheetData[$i][2]);
					$s2   = strtoupper($sheetData[$i][3]);
					$s3   = strtoupper($sheetData[$i][4]);
					$it   = strtoupper($sheetData[$i][5]);
					$ip_tdk_pot     = strtoupper($sheetData[$i][6]);
					$ip_pot     = strtoupper($sheetData[$i][7]);

					if (in_array($sheetName, ['TETAP', 'KONTRAK'])) {
						$lb = strtoupper($sheetData[$i][8]); 
					} else {
						$lb = strtoupper($sheetData[$i][9]); 
					}

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

					$qi_htsprrd_htlxxmh_d = $db
						->query('insert', 'htsprrd_htlxxmh_d')
						->set('id_htsprrd_htlxxmh_h',$id_htsprrd_htlxxmh_h)
						->set('id_hemxxmh',$id_hemxxmh)
						->set('kode',$kode)
						->set('nama',$nama)
						->set('al',$al)
						->set('s2',$s2)
						->set('s3',$s3)
						->set('it',$it)
						->set('ip_tdk_pot',$ip_tdk_pot)
						->set('ip_pot',$ip_pot)
						->set('lb',$lb)
						->exec();
				}

				$data = [
					"message" => "Upload berhasil!",
					"type_message" => "success",
				];
			} else {
				$data = array(
					"message" => "Template file salah!",
					"type_message" => "danger"
				);
			}
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