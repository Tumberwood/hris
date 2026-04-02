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
		
		if ($sheetData[0][0] == 'Tipe') {
			try{
				$db->transaction();
				
				$datakembar = 0;
				$dataupload = 0;
				$emptyPeg = array();
				
				for($i = 1; $i < count($sheetData); $i++){

					$tipe = $sheetData[$i][0];
					$sub_tipe = $sheetData[$i][1];
					$status = $sheetData[$i][2];
					$hari = $sheetData[$i][3];
					$nominal_lembur = $sheetData[$i][4];
					$nominal_pot_absen = $sheetData[$i][5];

					if ($hari == '5') {
						$grup_hk = 1;
					} elseif ($hari == '6') {
						$grup_hk = 2;
					} else {
						$grup_hk = 0;
					}

					// ===== FIX TANGGAL =====
					$raw = trim($sheetData[$i][6]);

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
					
					$keterangan = $sheetData[$i][7];

					// cari tipe
					$qs_tipe = $db->query('select','heyxxmh')
						->get(['id as id_heyxxmh'])
						->get(['count(id) as id'])
						->where('nama',$tipe)
						->exec();

					$rs_tipe = $qs_tipe->fetch();
					$id_heyxxmh = $rs_tipe['id_heyxxmh'];

					// cari sub_tipe
					$qs_sub_tipe = $db->query('select','heyxxmd')
						->get(['id as id_heyxxmd'])
						->get(['count(id) as id'])
						->where('nama',$sub_tipe)
						->exec();

					$rs_sub_tipe = $qs_sub_tipe->fetch();
					$id_heyxxmd = $rs_sub_tipe['id_heyxxmd'];
					
					// cari status
					$qs_status = $db->query('select','hesxxmh')
						->get(['id as id_hesxxmh'])
						->get(['count(id) as id'])
						->where('nama',$status)
						->exec();

					$rs_status = $qs_status->fetch();
					$id_hesxxmh = $rs_status['id_hesxxmh'];

					if ($rs_tipe['id'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					if ($rs_sub_tipe['id'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					if ($rs_status['id'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}
					
					$qs_check = $db->query('select','htpr_sub_tipe')
						->get(['id'])
						->where('id_heyxxmh',$id_heyxxmh)
						->where('id_heyxxmd',$id_heyxxmd)
						->where('id_hesxxmh',$id_hesxxmh)
						->where('tanggal_efektif',$tanggal_efektif)
						->where('grup_hk',$grup_hk)
						->where('nominal_lembur',$nominal_lembur)
						->where('nominal_pot_absen',$nominal_pot_absen)
						->where('is_active',1)
						->exec();

					$rs_check = $qs_check->fetch();

					if(!$rs_check){

						$db->query('insert','htpr_sub_tipe')
							->set('kode', 'Upload')
							->set('created_by', $_SESSION['user'])
							->set('id_heyxxmh',$id_heyxxmh)
							->set('id_heyxxmd',$id_heyxxmd)
							->set('id_hesxxmh',$id_hesxxmh)
							->set('nominal_lembur',$nominal_lembur)
							->set('nominal_pot_absen',$nominal_pot_absen)
							->set('tanggal_efektif',$tanggal_efektif)
							->set('grup_hk',$grup_hk)
							->set('keterangan',$keterangan)
							->exec();

						$dataupload++;

					} else {
						$datakembar++;
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
						"message" => "Upload Komponen per Sub Tipe Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Upload Komponen per Sub Tipe gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Sub Tipe salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen per Sub Tipe gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>