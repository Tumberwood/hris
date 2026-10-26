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
		
		try{
			$db->transaction();
			
			$datakembar = 0;
			$dataupload = 0;
			$emptyPeg = array();
			/**
             *                      : nama
             * 0: NIK           : kode
             * 1: Nama              
             * 2: Tanggal             
             * 3: Shift
             */
			for($i = 1;$i < count($sheetData);$i++){
				if ($sheetData[$i]['0'] != null) {
					
					$tanggal_excel = new Carbon($sheetData[$i]['0']); //gunakan carbon untuk ambil data tanggal
					$tanggal = $tanggal_excel->format('Y-m-d'); //format jadi 2023-09-12
					
					$shift 		= $sheetData[$i]['1'] ;
					$jumlah 		= $sheetData[$i]['2'] ;
					$keterangan = $sheetData[$i]['3'] ;

					$qd_makan_catering_hl_umum = $db
						->query('delete', 'makan_catering_hl_umum')
						->where('tanggal',$tanggal)
						->where('shift',$shift)
						->exec();
					
					$qr_makan_catering_hl_umum = $db
						->raw()
						->bind(':tanggal', $tanggal)
						->bind(':shift', $shift)
						->bind(':jumlah', $jumlah)
						->bind(':keterangan', $keterangan)
						->exec('INSERT INTO makan_catering_hl_umum
							(
								jumlah,
								keterangan,
								shift,
								tanggal
							)
							SELECT
								:jumlah,
								:keterangan,
								:shift,
								:tanggal
						');

					$dataupload = $dataupload + 1;
				}
			}
			
			// print_r(count($emptyPeg));
			if (count($emptyPeg) >= 1) {
				$errorMessage = "";
				foreach ($emptyPeg as $index => $emptyPegawai) {
					$rowIndex = $emptyPegawai['rowIndex'];
					$errorMessage .= $rowIndex;
					if ($index < count($emptyPeg) - 1) {
						$errorMessage .= ", ";
					}
				}
				$data = array(
					"message" => "NIK tidak sesuai pada Baris " . $errorMessage,
					"type_message" => "danger"
				);
			} else {
				$data = array(
					"message" => "Upload Rencana Makan Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
					"type_message" => "success",
					"debug" => $emptyPeg,
				);
			}

		
		$db->commit();
		}catch (PDOException $e){
			$db->rollback();
			$data = array(
				"message" => "Upload Rencana Makan gagal," . $e,
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Rencana Makan gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>