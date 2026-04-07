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
		
		if ($sheetData[0][0] == 'Sub Tipe') {
			try{
				$db->transaction();
				
				$datakembar = 0;
				$dataupload = 0;
				$emptyPeg = array();
				/**
				 *                      
				 * 0: NIK           : nama
				 * 1: Id Komponen	: id_hpcxxmh              
				 * 2: Tanggal             
				 * 3: Nominal
				 */
				for($i = 1; $i < count($sheetData); $i++){

					$nama = $sheetData[$i][0];

					// ===== FIX TANGGAL =====
					$raw = trim($sheetData[$i][2]);

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
					
					$keterangan = $sheetData[$i][3];

					// mapping komponen
					$komponen = [
						['id_hpcxxmh' => 34,   'nominal' => $sheetData[$i][1]],
					];

					// cari Tipe
					$qs = $db->query('select','heyxxmd')
						->get(['id as id_heyxxmd'])
						->get(['count(id) as id'])
						->where('nama',$nama)
						->exec();

					$rs = $qs->fetch();
					$id_heyxxmd = $rs['id_heyxxmd'];

					if ($rs['id'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					$qs_hemxxmh = $db
						->query('select', 'hemxxmh' )
						->get(['hemxxmh.id as id_hemxxmh'] )
						->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
						->where('hemjbmh.id_heyxxmd', $id_heyxxmd )
						->exec();
					$rs_hemxxmh = $qs_hemxxmh->fetchAll();

					foreach ($rs_hemxxmh as $key => $hemxxmh) {
    					$id_hemxxmh = $hemxxmh['id_hemxxmh'];

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
						"message" => "Upload Komponen Potongan Uang Makan Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Upload Komponen Potongan Uang Makan gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Potongan Uang Makan salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen Potongan Uang Makan gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>