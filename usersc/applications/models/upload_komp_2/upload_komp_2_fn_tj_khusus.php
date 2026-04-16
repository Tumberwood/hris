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
		
		if ($sheetData[0][0] == 'NIK Pegawai') {
			try{
				$db->transaction();
				
				$datakembar = 0;
				$dataupload = 0;
				$emptyPeg = array();
				/**
				 *                      
				 * 0: NIK           : kode
				 * 1: Id Komponen	: id_hpcxxmh              
				 * 2: Tanggal             
				 * 3: Nominal
				 */
				for($i = 1; $i < count($sheetData); $i++){

					$kode = $sheetData[$i][0];

					// ===== FIX TANGGAL =====
					$raw = trim($sheetData[$i][3]);

					$formats = [
						'd/m/Y',
						'd/m/y',
						'Y-m-d',
						'j M Y',
						'j F Y',
					];

					$tanggal_efektif = null;

					foreach($formats as $f){
						try{
							$dt = Carbon::createFromFormat($f, $raw);
							$tanggal_efektif = $dt->format('Y-m-d');
							break;
						}catch(Exception $e){}
					}

					// fallback terakhir (biar gak error)
					if(!$tanggal_efektif){
						try{
							$tanggal_efektif = Carbon::parse($raw)->format('Y-m-d');
						}catch(Exception $e){
							$tanggal_efektif = null; // atau skip row
						}
					}
					
					$keterangan = $sheetData[$i][4];

					// mapping komponen
					$komponen = [
						['id_hpcxxmh' => 133,   'nominal' => $sheetData[$i][2]],
					];

					// cari karyawan
					$qs = $db->query('select','hemxxmh')
						->get(['id as id_hemxxmh'])
						->get(['count(id) as id'])
						->where('kode',$kode)
						->exec();

					$rs = $qs->fetch();
					$id_hemxxmh = $rs['id_hemxxmh'];

					if ($rs['id'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					foreach($komponen as $k){

						$id_hpcxxmh = $k['id_hpcxxmh'];
						$nominal    = $k['nominal'];

						if($nominal === null || $nominal === '') continue;

						$qs_check = $db->query('select','htpr_hemxxmh')
							->get(['id'])
							->where('id_hemxxmh',$id_hemxxmh)
							->where('id_hpcxxmh',$id_hpcxxmh)
							->where('tanggal_efektif',$tanggal_efektif)
							->where('is_active',1)
							->exec();

						$rs_check = $qs_check->fetch();

						if(!$rs_check){

							$db->query('insert','htpr_hemxxmh')
								->set('kode', 'Upload')
								->set('created_by', $_SESSION['user'])
								->set('id_hemxxmh',$id_hemxxmh)
								->set('id_hpcxxmh',$id_hpcxxmh)
								->set('nominal',$nominal)
								->set('tanggal_efektif',$tanggal_efektif)
								->set('keterangan',$keterangan)
								->exec();

							$dataupload++;

						} else {
							$datakembar++;
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
						"message" => "No Akun tidak sesuai pada Baris " . $errorMessage,
						"type_message" => "danger"
					);
				} else {
					$data = array(
						"message" => "Upload Komponen per Karyawan Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Upload Komponen per Karyawan gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Karyawan salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen per Karyawan gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>