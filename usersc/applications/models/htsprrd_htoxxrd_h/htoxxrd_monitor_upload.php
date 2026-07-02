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
			
			if ($sheetData[0][3] == "Lembur Jam") {
				for ($i = 1; $i < count($sheetData); $i++) {
					$kode      			= strtoupper($sheetData[$i][0]);
					$nama      			= strtoupper($sheetData[$i][1]);
					$tanggal   			= strtoupper($sheetData[$i][2]);
					$durasi_lembur_jam	= strtoupper($sheetData[$i][3]);
					$lembur15  			= strtoupper($sheetData[$i][4]);
					$lembur2   			= strtoupper($sheetData[$i][5]);
					$lembur3   			= strtoupper($sheetData[$i][6]);
					$keterangan			= strtoupper($sheetData[$i][7]);

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

					$qd_htoxxrd_monitor = $db
						->query('delete', 'htoxxrd_monitor')
						->where('id_hemxxmh', $id_hemxxmh)
						->where('tanggal', $tanggal)
						->exec();

					$qi_htoxxrd_monitor = $db
						->query('insert', 'htoxxrd_monitor')
						->set('id_hemxxmh',$id_hemxxmh)
						->set('kode',$kode)
						->set('nama',$nama)
						->set('lembur15',$lembur15)
						->set('lembur2',$lembur2)
						->set('lembur3',$lembur3)
						->set('keterangan',$keterangan)
						->set('tanggal',$tanggal)
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