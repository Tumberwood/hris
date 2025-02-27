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
		$id_htsprrd_htoxxrd_h = $_POST['id_htsprrd_htoxxrd_h'];

		foreach ($sheetNames as $sheetIndex => $sheetName) {
			$sheetData = $spreadsheet->getSheet($sheetIndex)->toArray();
			
			if ($sheetData[1][0] == "no_induk") {
				for ($i = 3; $i < count($sheetData); $i++) {
					$kode      = strtoupper($sheetData[$i][0]);
					$nama      = strtoupper($sheetData[$i][1]);
					$lembur15  = strtoupper($sheetData[$i][2]);
					$lembur2   = strtoupper($sheetData[$i][3]);
					$lembur3   = strtoupper($sheetData[$i][4]);
					$lembur4   = strtoupper($sheetData[$i][5]);
					$makan     = strtoupper($sheetData[$i][6]);

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

					$qi_htsprrd_htoxxrd_d = $db
						->query('insert', 'htsprrd_htoxxrd_d')
						->set('id_htsprrd_htoxxrd_h',$id_htsprrd_htoxxrd_h)
						->set('id_hemxxmh',$id_hemxxmh)
						->set('kode',$kode)
						->set('nama',$nama)
						->set('lembur15',$lembur15)
						->set('lembur2',$lembur2)
						->set('lembur3',$lembur3)
						->set('lembur4',$lembur4)
						->set('makan',$makan)
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