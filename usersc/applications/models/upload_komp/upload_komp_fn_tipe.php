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
             *                      
             * 0: Nama Tipe   : 
             * 1: Id Komponen	: id_hpcxxmh              
             * 2: Tanggal             
             * 3: Nominal
             */
			for($i = 1;$i < count($sheetData);$i++){
				$nama_tipe 		= $sheetData[$i]['0'] ;
				$id_hpcxxmh 		= $sheetData[$i]['1'] ;
				$nominal 		= $sheetData[$i]['3'] ;
				
				$tanggal_excel = new Carbon($sheetData[$i]['2']); //gunakan carbon untuk ambil data tanggal
				$tanggal_efektif = $tanggal_excel->format('Y-m-d'); //format jadi 2023-09-12

                // print_r($id_htsxxmh);
				//cari NIK
				$qs_heyxxmh = $db
					->query('select', 'heyxxmh' )
					->get(['id as id_heyxxmh'] )
					->get(['count(id) as id'] )
					->where('nama', $nama_tipe)
					->exec();
				$rs_heyxxmh = $qs_heyxxmh->fetch();	
				$id_heyxxmh = $rs_heyxxmh['id_heyxxmh'];

				if ($rs_heyxxmh['id'] == 0) {
					$emptyPeg[] = array(
						'rowIndex' => $i + 1
					);
				}

				// Nonaktifkan Data Lama
				$qu_htpr_heyxxmh = $db
					->query('update', 'htpr_heyxxmh' )
					->set('is_active',0)
					->where('htpr_heyxxmh.id_heyxxmh', $id_heyxxmh )
					->where('htpr_heyxxmh.id_hpcxxmh', $id_hpcxxmh )
					->where('htpr_heyxxmh.tanggal_efektif', $tanggal_efektif )
					->where('htpr_heyxxmh.is_active', 1 )
					->exec();

				// Cek apakah ada data yang sama
				$qs_htpr_heyxxmh = $db
					->query('select', 'htpr_heyxxmh' )
					->get([
						'htpr_heyxxmh.id as id_htpr_heyxxmh'
					] )
					->where('htpr_heyxxmh.id_heyxxmh', $id_heyxxmh )
					->where('htpr_heyxxmh.id_hpcxxmh', $id_hpcxxmh )
					->where('htpr_heyxxmh.tanggal_efektif', $tanggal_efektif )
					->where('htpr_heyxxmh.is_active', 1 )
					->exec();
				$rs_htpr_heyxxmh = $qs_htpr_heyxxmh->fetchAll();
				
				$c_rs_htpr_heyxxmh = count($rs_htpr_heyxxmh);
				
				// Jika tidak ada data kembar maka di insert
				if($c_rs_htpr_heyxxmh == 0){
					$qi_htpr_heyxxmh = $db
						->query('insert', 'htpr_heyxxmh')
						->set('id_heyxxmh',$id_heyxxmh)
						->set('id_hpcxxmh',$id_hpcxxmh)
						->set('nominal',$nominal)
						->set('tanggal_efektif',$tanggal_efektif)
						->set('keterangan', 'Komponen per Tipe (Outsourcing/Organik)')
						->exec();

					$dataupload = $dataupload + 1;
					
				}else{
					$datakembar = $datakembar + 1;
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
					"message" => "Nama Tipe tidak sesuai pada Baris " . $errorMessage,
					"type_message" => "danger"
				);
			} else {
				$data = array(
					"message" => "Upload Komponen per Tipe Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
					"type_message" => "success"
				);
			}

		
		$db->commit();
		}catch (PDOException $e){
			$db->rollback();
			$data = array(
				"message" => "Upload Komponen per Tipe gagal," . $e,
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Komponen per Tipe gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>