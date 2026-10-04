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
		
		// --- PENGECEKAN TEMPLATE EXCEL ---
		$header_first_col = isset($sheetData[0]['0']) ? trim($sheetData[0]['0']) : '';

		if (strtoupper($header_first_col) !== 'NIK KTP') {
			$data = array(
				"message" => "Format template Excel salah! Header kolom pertama harus 'NIK KTP'.",
				"type_message" => "danger"
			);
			require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
			exit();
		}
		// ----------------------------------

		try{
			$db->transaction();
			
			$datakembar = 0;
			$dataupload = 0;
			$emptyPeg   = array();

			for($i = 1; $i < count($sheetData); $i++){
				if ($sheetData[$i]['0'] != null) {
					$ktp_no        = $sheetData[$i]['0']; // NIK KTP
					$kode_finger   = $sheetData[$i]['1']; // KODE FINGER
					$nama          = $sheetData[$i]['2']; // NAMA
					$bagian        = $sheetData[$i]['4']; // BAGIAN
					$gender        = $sheetData[$i]['5']; // JENIS KELAMIN

					// Parsing Tanggal
					$tanggal_lahir = !empty($sheetData[$i]['6']) ? Carbon::parse($sheetData[$i]['6'])->format('Y-m-d') : null;
					$tanggal_masuk = !empty($sheetData[$i]['7']) ? Carbon::parse($sheetData[$i]['7'])->format('Y-m-d') : null;

					$no_bpjs_tk    = $sheetData[$i]['8']; // NO. BPJS TK
					$alamat        = $sheetData[$i]['9']; // ALAMAT

					// Ambil ID Bagian dari master hobxxmh
					$qs_hobxxmh = $db
						->query('select', 'hobxxmh' )
						->get(['id as id_hobxxmh'] )
						->where('nama', $bagian)
						->exec();
					$rs_hobxxmh = $qs_hobxxmh->fetch();	
					$id_hobxxmh = isset($rs_hobxxmh['id_hobxxmh']) ? $rs_hobxxmh['id_hobxxmh'] : 0;
	
					if ($id_hobxxmh == 0) {
						$emptyPeg[] = array(
							'rowIndex' => $i + 1,
							'field'    => 'Bagian tidak ditemukan: ' . $bagian
						);
					}

					// Cek duplikasi berdasarkan kode_finger
					$qs_hem = $db
						->query('select', 'hemxxmh' )
						->get(['id'] )
						->where('kode_finger', $kode_finger)
						->exec();
					$rs_hem = $qs_hem->fetch();

					if(!$rs_hem){
						// 1. Insert ke hemxxmh
						$insert_query = $db->raw()
							->bind(':kode_finger', $kode_finger)
							->bind(':kode', $kode_finger)
							->bind(':nama', $nama)
							->bind(':gender', $gender)
							->exec('INSERT INTO hemxxmh (kode_finger, kode, nama, gender) 
									VALUES (:kode_finger, :kode, :nama, :gender)');

						// Ambil ID hasil insert
						$id_hemxxmh = $insert_query->insertId();

						// 2. Insert ke hemdcmh (KTP, BPJS TK, Alamat)
						$db->raw()
							->bind(':id_hemxxmh', $id_hemxxmh)
							->bind(':ktp_no', $ktp_no)
							->bind(':no_bpjs_tk', $no_bpjs_tk)
							->bind(':alamat', $alamat)
							->exec('INSERT INTO hemdcmh (id_hemxxmh, ktp_no, no_bpjs_tk, alamat) 
								    VALUES (:id_hemxxmh, :ktp_no, :no_bpjs_tk, :alamat)');

						// 3. Insert ke hemjbmh (Bagian & Tanggal Masuk)
						$db->raw()
							->bind(':id_hemxxmh', $id_hemxxmh)
							->bind(':id_hobxxmh', $id_hobxxmh)
							->bind(':tanggal_masuk', $tanggal_masuk)
							->exec('INSERT INTO hemjbmh (id_hemxxmh, id_hobxxmh, tanggal_masuk) 
								    VALUES (:id_hemxxmh, :id_hobxxmh, :tanggal_masuk)');

						// 4. Insert ke hemfmmd (Tanggal Lahir)
						$db->raw()
							->bind(':id_hemxxmh', $id_hemxxmh)
							->bind(':tanggal_lahir', $tanggal_lahir)
							->exec('INSERT INTO hemfmmd (id_hemxxmh, tanggal_lahir) 
								    VALUES (:id_hemxxmh, :tanggal_lahir)');

						// 5. Insert ke hemjbrd (Riwayat Pekerjaan)
						$db->raw()
							->bind(':id_hemxxmh', $id_hemxxmh)
							->bind(':id_harxxmh', 1)
							->bind(':is_email_status', 1)
							->bind(':tanggal_awal', $tanggal_masuk)
							->exec('INSERT INTO hemjbrd (
										id_hemxxmh,
										id_harxxmh,
										is_email_status,
										tanggal_awal
									) VALUES (
										:id_hemxxmh,
										:id_harxxmh,
										:is_email_status,
										:tanggal_awal
									)');

						$dataupload++;
					} else {
						$datakembar++;
					}
				}
			}
			
			if (count($emptyPeg) >= 1) {
				$errorMessage = "";
				foreach ($emptyPeg as $index => $emptyPegawai) {
					$rowIndex = $emptyPegawai['rowIndex'];
					$errorMessage .= "Baris " . $rowIndex . " (" . $emptyPegawai['field'] . ")";
					if ($index < count($emptyPeg) - 1) {
						$errorMessage .= ", ";
					}
				}
				$data = array(
					"message" => "Master Bagian tidak ditemukan: " . $errorMessage,
					"type_message" => "danger"
				);
			} else {
				$data = array(
					"message" => "Upload Data Berhasil.</br>" . $dataupload . " data berhasil diimport.</br>" . $datakembar . " data kembar TIDAK diimport.",
					"type_message" => "success",
					"debug" => $emptyPeg
				);
			}

			$db->commit();
		} catch (PDOException $e) {
			$db->rollback();
			$data = array(
				"message" => "Upload gagal: " . $e->getMessage(),
				"type_message" => "danger"
			);
		}
	} else {
		$data = array(
			"message" => "Upload gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// Tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>