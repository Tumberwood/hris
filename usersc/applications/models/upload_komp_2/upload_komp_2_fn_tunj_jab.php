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
		
		if ($sheetData[0][5] == 'Tunjangan Jabatan') {
			try{
				$db->transaction();
				
				$datakembar = 0;
				$dataupload = 0;
				$emptyPeg = array();
				
				for($i = 1; $i < count($sheetData); $i++){

					$jabatan = $sheetData[$i][0];
					
					if ($jabatan == '') {
						break;
					} else {
						// lanjut proses
					}

					$bagian = $sheetData[$i][1];
					$skala_upah = $sheetData[$i][2];
					$sub_tipe = $sheetData[$i][3];
					$status = $sheetData[$i][4];

					// mapping komponen
					$komponen = [
						['id_hpcxxmh' => 32,   'nominal' => $sheetData[$i][5]],
					];

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

					// cari jabatan
					$qs_hevgrmh = $db->query('select','hevgrmh')
						->get(['id as id_hevgrmh'])
						->where('nama',$jabatan)
						->exec();

					$rs_hevgrmh = $qs_hevgrmh->fetch();
					if ($rs_hevgrmh) {
						# code...
						$id_hevgrmh = $rs_hevgrmh['id_hevgrmh'];
					} else {

						echo $i;
						echo $jabatan;
					}

					if ($rs_hevgrmh['id_hevgrmh'] == 0) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					// cari bagian
					$qs_hobxxmh = $db->query('select','hobxxmh')
						->get(['id as id_hobxxmh'])
						->where('nama',$bagian)
						->exec();

					$rs_hobxxmh = $qs_hobxxmh->fetch();
					$id_hobxxmh = $rs_hobxxmh['id_hobxxmh'] ?? 0;

					// cari skala_upah
					$qs_hevxxmh = $db->query('select','hevxxmh')
						->get(['id as id_hevxxmh'])
						->where('nama',$skala_upah)
						->exec();

					$rs_hevxxmh = $qs_hevxxmh->fetch();
					$id_hevxxmh = $rs_hevxxmh['id_hevxxmh'];

					if ($rs_hevxxmh['id_hevxxmh'] == 0) {
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

					$qs_hemxxmh = $db
						->query('select', 'hemxxmh' )
						->get(['hemxxmh.id as id_hemxxmh'] )
						->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
						->where('hemjbmh.id_hevgrmh', $id_hevgrmh )
						->where('hemjbmh.id_hobxxmh', $id_hobxxmh )
						->where('hemjbmh.id_hevxxmh', $id_hevxxmh )
						->where('hemjbmh.id_heyxxmd', $id_heyxxmd )
						->where('hemjbmh.id_hesxxmh', $id_hesxxmh )
						->where('hemxxmh.is_active', 1 )
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
						"message" => "Upload Komponen per Skala Upah Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Upload Komponen per Skala Upah gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Skala Upah salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen per Skala Upah gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>