<?php
	require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );
	require_once( "../../../../usersc/vendor/autoload.php" );
	
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
			/**
             *                      : nama
             * 0: no akun           : kode
             * 1: Nama              
             * 2: Waktu             : slip jadi tanggal dan jam
             * 3: Kondisi
             * 4: Kondisi Baru
             * 5: Status
             * 6: Operasi
             */
			for($i = 1;$i < count($sheetData);$i++){
				
				$nama  = 'makan';
                if(strlen($sheetData[$i]['0']) == 1){
                    $kode 		= '000' . $sheetData[$i]['0'] ;
                }elseif(strlen($sheetData[$i]['0']) == 2){
                    $kode 		= '00' . $sheetData[$i]['0'] ;
                }elseif(strlen($sheetData[$i]['0']) == 3){
                    $kode 		= '0' . $sheetData[$i]['0'] ;
                }elseif(strlen($sheetData[$i]['0']) == 4){
                    $kode 		= $sheetData[$i]['0'] ;
                }
                

				$dt = explode(" ",$sheetData[$i]['2']);
                
                foreach ($dt as $key => $value) {
                    $str_tanggal = $dt[0];
                    $tahun = substr($str_tanggal,6,4);
                    $bulan = substr($str_tanggal,3,2);
                    $tgl = substr($str_tanggal,0,2);
                    $tanggal = $tahun .'-'.$bulan.'-'.$tgl;
                    $jam     = $dt[1];
                }
				
				$qs_htsprtd = $db
					->query('select', 'htsprtd' )
					->get([
						'htsprtd.id as id_htsprtd'
					] )
					->where('htsprtd.kode', $kode )
					->where('htsprtd.nama', $nama )
					->where('htsprtd.tanggal', $tanggal )
					->where('htsprtd.jam', $jam )
					->exec();
				$rs_htsprtd = $qs_htsprtd->fetchAll();
				$c_rs_htsprtd = count($rs_htsprtd);
				
				if($c_rs_htsprtd == 0){
					$qi_htsprtd = $db
						->query('insert', 'htsprtd')
						->set('kode',$kode)
						->set('nama',$nama)
						->set('tanggal',$tanggal)
						->set('jam',$jam)
						->exec();
					$dataupload = $dataupload + 1;
					
				}else{
					$datakembar = $datakembar + 1;
				}
					
			}
			$db->commit();
			$data = array(
				"message" => "Upload Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
				"type_message" => "success"
			);
			
		}catch (PDOException $e){
			$db->rollback();
			$data = array(
				"message" => "Upload gagal," . $e,
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>