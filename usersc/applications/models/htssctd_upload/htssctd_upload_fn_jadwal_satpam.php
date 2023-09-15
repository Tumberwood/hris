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
				$kode 		= $sheetData[$i]['0'] ;
				
				$tanggal_excel = new Carbon($sheetData[$i]['2']); //gunakan carbon untuk ambil data tanggal
				$tanggal = $tanggal_excel->format('Y-m-d'); //format jadi 2023-09-12
				
				$shift 		= $sheetData[$i]['3'] ;
				if ($shift == "1") {
					$id_htsxxmh = 6;
				} else if ($shift == "2") {
					$id_htsxxmh = 17;
				} else if ($shift == "3") {
					$id_htsxxmh = 23;
				} else {
					$id_htsxxmh = 1;
				}

                // print_r($id_htsxxmh);
				//cari NIK
				$qs_hemxxmh = $db
					->query('select', 'hemxxmh' )
					->get(['id as id_hemxxmh'] )
					->get(['count(id) as id'] )
					->where('kode', $kode)
					->exec();
				$rs_hemxxmh = $qs_hemxxmh->fetch();	
				$id_hemxxmh = $rs_hemxxmh['id_hemxxmh'];

				if ($rs_hemxxmh['id'] == 0) {
					$emptyPeg[] = array(
						'rowIndex' => $i + 1
					);
				}
				$qs_htssctd = $db
					->query('select', 'htssctd' )
					->get([
						'htssctd.id as id_htssctd'
					] )
					->where('htssctd.id_hemxxmh', $id_hemxxmh )
					->where('htssctd.id_htsxxmh', $id_htsxxmh )
					->where('htssctd.tanggal', $tanggal )
					->exec();
				$rs_htssctd = $qs_htssctd->fetchAll();
				$c_rs_htssctd = count($rs_htssctd);
				
				if($c_rs_htssctd == 0){
					
                // Begin insert pengaju
                $qr_htssctd_pengaju = $db
					->raw()
					->bind(':id_htsxxmh', $id_htsxxmh)
					->bind(':id_hemxxmh', $id_hemxxmh)
					->bind(':tanggal', $tanggal)
					->exec('
						INSERT INTO htssctd
						(
							id_hemxxmh,
							id_htsxxmh,
							keterangan,
							tanggal,
							jam_awal,
							jam_akhir,
							jam_awal_istirahat,
							jam_akhir_istirahat,
							menit_toleransi_awal_in,
							menit_toleransi_akhir_in,
							menit_toleransi_awal_out,
							menit_toleransi_akhir_out,

							tanggaljam_awal_t1,
							tanggaljam_awal,
							tanggaljam_awal_t2,
							tanggaljam_akhir_t1,
							tanggaljam_akhir,
							tanggaljam_akhir_t2,
							tanggaljam_awal_istirahat,
							tanggaljam_akhir_istirahat
						)
						SELECT
							:id_hemxxmh,
							:id_htsxxmh,
							"Upload Jadwal Satpam",
							:tanggal,
							htsxxmh.jam_awal,
							htsxxmh.jam_akhir,
							htsxxmh.jam_awal_istirahat,
							htsxxmh.jam_akhir_istirahat,
							htsxxmh.menit_toleransi_awal_in,
							htsxxmh.menit_toleransi_akhir_in,
							htsxxmh.menit_toleransi_awal_out,
							htsxxmh.menit_toleransi_akhir_out,
							CONCAT(:tanggal, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
							CONCAT(:tanggal, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
							CONCAT(
								CASE
									WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal, INTERVAL 1 DAY)
									ELSE :tanggal
								END,
								" ",
								TIME(
									CASE
										WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
											TIMEDIFF(DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE), "24:00:00")
										ELSE
											DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE)
									END
								)
							) AS tanggaljam_awal_t2,

							CASE
								WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
								THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
								ELSE CONCAT(:tanggal, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
							END AS tanggaljam_akhir_t1,
							CASE
								WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
								THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
								ELSE CONCAT(:tanggal, " ", htsxxmh.jam_akhir)
							END AS tanggaljam_akhir,
							CONCAT(
								CASE
									WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
										OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal, INTERVAL 1 DAY)
									ELSE :tanggal
								END,
								" ",
								TIME(
									CASE
										WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
											TIMEDIFF(DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE), "24:00:00")
										ELSE
											DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)
									END
								)
							) AS tanggaljam_akhir_t2,
							CONCAT(:tanggal, " ", htsxxmh.jam_awal_istirahat) AS tanggaljam_awal_istirahat,
							CONCAT(:tanggal, " ", htsxxmh.jam_akhir_istirahat) AS tanggaljam_akhir_istirahat
						FROM htsxxmh
						WHERE 
							id = :id_htsxxmh
					');

                // END insert pengaju

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
					"message" => "No Akun tidak sesuai pada Baris " . $errorMessage,
					"type_message" => "danger"
				);
			} else {
				$data = array(
					"message" => "Upload Jadwal Satpam Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
					"type_message" => "success",
					"debug" => $emptyPeg,
					"hem" => $rs_hemxxmh
				);
			}

		
		$db->commit();
		}catch (PDOException $e){
			$db->rollback();
			$data = array(
				"message" => "Upload Jadwal Satpam gagal," . $e,
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Upload Jadwal Satpam gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>