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

					// =========================================================
					// DATA EXCEL
					// =========================================================
					$ktp_no        = $sheetData[$i]['0']; // NIK KTP
					$kode_finger   = $sheetData[$i]['1']; // KODE FINGER
					$nama          = $sheetData[$i]['2']; // NAMA
					$bagian        = $sheetData[$i]['4']; // BAGIAN
					$gender        = $sheetData[$i]['5']; // JENIS KELAMIN

					// Parsing Tanggal
					$tanggal_lahir = !empty($sheetData[$i]['6'])
						? Carbon::parse($sheetData[$i]['6'])->format('Y-m-d')
						: null;

					$tanggal_masuk = !empty($sheetData[$i]['7'])
						? Carbon::parse($sheetData[$i]['7'])->format('Y-m-d')
						: null;

					$no_bpjs_tk    = $sheetData[$i]['8']; // NO. BPJS TK
					$alamat        = $sheetData[$i]['9']; // ALAMAT

					// Default
					$is_harian_lepas = 0;

					// Variable untuk tracking proses insert
					$currentProcess = 'Persiapan data';

					try {

						// =========================================================
						// 1. AMBIL ID BAGIAN
						// =========================================================
						$currentProcess = 'Mencari Master Bagian (hobxxmh)';

						$qs_hobxxmh = $db
							->query('select', 'hobxxmh')
							->get(['id as id_hobxxmh'])
							->where('nama', $bagian)
							->exec();

						$rs_hobxxmh = $qs_hobxxmh->fetch();

						$id_hobxxmh = isset($rs_hobxxmh['id_hobxxmh'])
							? $rs_hobxxmh['id_hobxxmh']
							: 0;

						if ($id_hobxxmh == 0) {
							$emptyPeg[] = array(
								'rowIndex' => $i + 1,
								'field'    => 'Bagian tidak ditemukan: ' . $bagian
							);
						}

						// =========================================================
						// 2. CEK DUPLIKASI KODE FINGER
						// =========================================================
						$currentProcess = 'Cek duplikasi kode_finger';

						$qs_hem = $db
							->query('select', 'hemxxmh')
							->get(['id'])
							->where('kode_finger', $kode_finger)
							->exec();

						$rs_hem = $qs_hem->fetch();

						if(!$rs_hem){

							// =====================================================
							// 3. INSERT hemxxmh
							// =====================================================
							$currentProcess = 'Insert hemxxmh';

							$insert_query = $db->raw()
								->bind(':kode_finger', $kode_finger)
								->bind(':kode', $kode_finger)
								->bind(':nama', $nama)
								->bind(':gender', $gender)
								->exec('
									INSERT INTO hemxxmh (
										kode_finger,
										kode,
										nama,
										gender
									) VALUES (
										:kode_finger,
										:kode,
										:nama,
										:gender
									)
								');

							// Ambil ID hasil insert
							$id_hemxxmh = $insert_query->insertId();

							// =====================================================
							// VALIDASI INSERT hemxxmh
							// =====================================================
							if (!$id_hemxxmh) {
								throw new Exception(
									'Insert hemxxmh berhasil dijalankan tetapi insertId tidak ditemukan.'
								);
							}

							// =====================================================
							// 4. INSERT hemdcmh
							// =====================================================
							$currentProcess = 'Insert hemdcmh';

							$db->raw()
								->bind(':id_hemxxmh', $id_hemxxmh)
								->bind(':ktp_no', $ktp_no)
								->bind(':no_bpjs_tk', $no_bpjs_tk)
								->bind(':alamat', $alamat)
								->exec('
									INSERT INTO hemdcmh (
										id_hemxxmh,
										ktp_no,
										no_bpjs_tk,
										alamat
									) VALUES (
										:id_hemxxmh,
										:ktp_no,
										:no_bpjs_tk,
										:alamat
									)
								');

							// =====================================================
							// 5. INSERT hemjbmh
							// =====================================================
							$currentProcess = 'Insert hemjbmh';

							$db->raw()
								->bind(':id_hemxxmh', $id_hemxxmh)
								->bind(':id_hobxxmh', $id_hobxxmh)
								->bind(':tanggal_masuk', $tanggal_masuk)
								->bind(':is_harian_lepas', $is_harian_lepas)
								->exec('
									INSERT INTO hemjbmh (
										id_hemxxmh,
										id_hobxxmh,
										tanggal_masuk,
										is_harian_lepas
									) VALUES (
										:id_hemxxmh,
										:id_hobxxmh,
										:tanggal_masuk,
										:is_harian_lepas
									)
								');

							// =====================================================
							// 6. INSERT hemfmmd
							// =====================================================
							$currentProcess = 'Insert hemfmmd';

							$db->raw()
								->bind(':id_hemxxmh', $id_hemxxmh)
								->bind(':tanggal_lahir', $tanggal_lahir)
								->exec('
									INSERT INTO hemfmmd (
										id_hemxxmh,
										tanggal_lahir
									) VALUES (
										:id_hemxxmh,
										:tanggal_lahir
									)
								');

							// =====================================================
							// 7. INSERT hemjbrd
							// =====================================================
							$currentProcess = 'Insert hemjbrd';

							$db->raw()
								->bind(':id_hemxxmh', $id_hemxxmh)
								->bind(':id_harxxmh', 1)
								->bind(':is_email_status', 1)
								->bind(':tanggal_awal', $tanggal_masuk)
								->exec('
									INSERT INTO hemjbrd (
										id_hemxxmh,
										id_harxxmh,
										is_email_status,
										tanggal_awal
									) VALUES (
										:id_hemxxmh,
										:id_harxxmh,
										:is_email_status,
										:tanggal_awal
									)
								');

							$dataupload++;

						} else {

							$datakembar++;
						}

					} catch (Throwable $e) {

						// =========================================================
						// DETAIL ERROR INSERT
						// =========================================================

						$db->rollback();

						$errorDetail =
							"Upload gagal pada baris Excel " . ($i + 1) . ".<br>" .
							"<b>Proses:</b> " . htmlspecialchars($currentProcess) . "<br>" .
							"<b>NIK KTP:</b> " . htmlspecialchars($ktp_no) . "<br>" .
							"<b>Kode Finger:</b> " . htmlspecialchars($kode_finger) . "<br>" .
							"<b>Nama:</b> " . htmlspecialchars($nama) . "<br>" .
							"<b>Bagian:</b> " . htmlspecialchars($bagian) . "<br>" .
							"<b>Error:</b> " . htmlspecialchars($e->getMessage());

						$data = array(
							"message" => $errorDetail,
							"type_message" => "danger"
						);

						require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
						exit();
					}
				}
			}
			
			// =========================================================
			// VALIDASI MASTER BAGIAN
			// =========================================================
			if (count($emptyPeg) >= 1) {

				$errorMessage = "";

				foreach ($emptyPeg as $index => $emptyPegawai) {

					$rowIndex = $emptyPegawai['rowIndex'];

					$errorMessage .=
						"Baris " .
						$rowIndex .
						" (" .
						$emptyPegawai['field'] .
						")";

					if ($index < count($emptyPeg) - 1) {
						$errorMessage .= ", ";
					}
				}

				$data = array(
					"message" =>
						"Master Bagian tidak ditemukan: " .
						$errorMessage,
					"type_message" => "danger"
				);

			} else {

				$data = array(
					"message" =>
						"Upload Data Berhasil.</br>" .
						$dataupload .
						" data berhasil diimport.</br>" .
						$datakembar .
						" data kembar TIDAK diimport.",
					"type_message" => "success",
					"debug" => $emptyPeg
				);
			}

			$db->commit();

		} catch (Throwable $e) {

			// =========================================================
			// ERROR TRANSACTION
			// =========================================================

			try {
				$db->rollback();
			} catch (Throwable $rollbackError) {
				// Abaikan error rollback
			}

			$data = array(
				"message" =>
					"Upload gagal.<br>" .
					"<b>Error:</b> " .
					htmlspecialchars($e->getMessage()),
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