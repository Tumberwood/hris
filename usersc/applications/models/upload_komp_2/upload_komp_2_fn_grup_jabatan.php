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
		
		if ($sheetData[0][0] == 'Nama Grup Jabatan') {
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
					$sub_tipe = $sheetData[$i][1];
					$status = $sheetData[$i][2];

					// ===== FIX TANGGAL =====
					$raw = trim($sheetData[$i][4]);

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
					
					$tahun_min 		= $sheetData[$i][5];
					$tahun_max 		= $sheetData[$i][6];
					$keterangan 	= $sheetData[$i][7];

					// mapping komponen
					$komponen = [
						['id_hpcxxmh' => 31,   'nominal' => $sheetData[$i][3]],
					];

					// cari Grup Jabatan
					$qs_hevgrmh = $db->query('select','hevgrmh')
						->get(['id as id_hevgrmh'])
						->where('nama',$nama)
						->exec();

					$rs_hevgrmh = $qs_hevgrmh->fetch();
					$id_hevgrmh = $rs_hevgrmh['id_hevgrmh'];

					if ($rs_hevgrmh['id_hevgrmh'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					// cari sub_tipe
					$qs_heyxxmd = $db->query('select','heyxxmd')
						->get(['id as id_heyxxmd'])
						->where('nama',$sub_tipe)
						->exec();

					$rs_heyxxmd = $qs_heyxxmd->fetch();
					$id_heyxxmd = $rs_heyxxmd['id_heyxxmd'];

					if ($rs_heyxxmd['id_heyxxmd'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					// cari status
					$qs_hesxxmh = $db->query('select','hesxxmh')
						->get(['id as id_hesxxmh'])
						->where('nama',$status)
						->exec();

					$rs_hesxxmh = $qs_hesxxmh->fetch();
					$id_hesxxmh = $rs_hesxxmh['id_hesxxmh'];

					if ($rs_hesxxmh['id_hesxxmh'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					foreach($komponen as $k){

						$id_hpcxxmh = $k['id_hpcxxmh'];
						$nominal    = $k['nominal'];

						if($nominal === null || $nominal === '') continue;

						$qs_check = $db->query('select','htpr_hevgrmh_mk')
							->get(['id'])
							->where('id_hevgrmh',$id_hevgrmh)
							->where('id_heyxxmd', $id_heyxxmd)
							->where('id_hesxxmh', $id_hesxxmh)
							->where('id_hpcxxmh',$id_hpcxxmh)
							->where('tahun_min',$tahun_min)
							->where('tahun_max',$tahun_max)
							->where('tanggal_efektif',$tanggal_efektif)
							->where('is_active',1)
							->exec();

						$rs_check = $qs_check->fetch();

						if(!$rs_check){

							$db->query('insert','htpr_hevgrmh_mk')
								->set('kode', 'Upload')
								->set('created_by', $_SESSION['user'])
								->set('id_heyxxmd', $id_heyxxmd)
								->set('id_hesxxmh', $id_hesxxmh)
								->set('id_hevgrmh',$id_hevgrmh)
								->set('id_hpcxxmh',$id_hpcxxmh)
								->set('tahun_min',$tahun_min)
								->set('tahun_max',$tahun_max)
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
						"message" => "Upload Komponen per Grup Jabatan Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Upload Komponen per Grup Jabatan gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Grup Jabatan salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen per Grup Jabatan gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>