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
					$nik_ktp       = $sheetData[$i]['0']; // Kolom NIK KTP (gabung)
					$kode_finger   = $sheetData[$i]['1']; // Kolom KODE FINGER
					$nama          = $sheetData[$i]['2']; // Kolom NAMA
					$stat          = $sheetData[$i]['3']; // Kolom STAT
					$bagian        = $sheetData[$i]['4']; // Kolom BAGIAN
					$jenis_kelamin = $sheetData[$i]['5']; // Kolom JENIS KELAMIN

					// Mengolah Tanggal Lahir (Carbon)
					$tgl_lahir_excel = new Carbon($sheetData[$i]['6']); // Kolom TANGGAL LAHIR
					$tanggal_lahir   = $tgl_lahir_excel->format('Y-m-d');

					// Mengolah Tanggal Join (Carbon)
					$tgl_join_excel  = new Carbon($sheetData[$i]['7']); // Kolom TANGGAL JOIN
					$tanggal_join    = $tgl_join_excel->format('Y-m-d');

					$no_bpjs_tk    = $sheetData[$i]['8']; // Kolom NO. BPJS TK
					$alamat        = $sheetData[$i]['9']; // Kolom ALAMAT

					$qs_hobxxmh = $db
						->query('select', 'hobxxmh' )
						->get(['id as id_hobxxmh'] )
						->where('nama', $bagian)
						->exec();
					$rs_hobxxmh = $qs_hobxxmh->fetch();	
					$id_hobxxmh = $rs_hobxxmh['id_hobxxmh'];
	
					if ($rs_hobxxmh['id'] == 0) {
						$emptyPeg[] = array(
							'rowIndex' => $i + 1
						);
					}

					$qs_hosxxmh = $db
						->query('select', 'hosxxmh' )
						->get(['id as id_hosxxmh'] )
						->where('nama', $bagian)
						->exec();
					$rs_hosxxmh = $qs_hosxxmh->fetch();	
					$id_hosxxmh = $rs_hosxxmh['id_hosxxmh'];
	
					if ($rs_hosxxmh['id'] == 0) {
						$emptyPeg[] = array(
							'rowIndex' => $i + 1
						);
					}
	
					$qs_rencana_makan = $db
						->query('select', '	' )
						->get([
							'rencana_makan.id as id_rencana_makan'
						] )
						->where('rencana_makan.id_hemxxmh', $id_hemxxmh )
						->where('rencana_makan.tanggal', $tanggal )
						->where('rencana_makan.is_active', 1 )
						->exec();
					$rs_rencana_makan = $qs_rencana_makan->fetchAll();
					
					$c_rs_rencana_makan = count($rs_rencana_makan);
					
					if($c_rs_rencana_makan == 0){
						
						$qr_rencana_makan = $db
							->raw()
							->bind(':id_hemxxmh', $id_hemxxmh)
							->bind(':tanggal', $tanggal)
							->bind(':shift', $shift)
							->bind(':keterangan', $keterangan)
							->exec('INSERT INTO rencana_makan
								(
									id_hemxxmh,
									keterangan,
									shift,
									tanggal
								)
								SELECT
									:id_hemxxmh,
									:keterangan,
									:shift,
									:tanggal
							');
	
						$dataupload = $dataupload + 1;
						
					}else{
						$datakembar = $datakembar + 1;
					}
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
					"hem" => $rs_hemxxmh
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