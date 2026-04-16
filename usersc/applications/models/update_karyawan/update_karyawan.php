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
		
		if ($sheetData[0][1] == 'NIK') {
			try{
				$db->transaction();
				
				$datakembar = 0;
				$dataupload = 0;
				$emptyPeg = array();
				function getId($db, $table, $nama, $alias) {

    $nama = trim($nama);

    // 🔥 decode HTML entity (penting banget)
    $nama = html_entity_decode($nama, ENT_QUOTES | ENT_HTML5);

    // 🔥 handle double encode (kayak R&AMP;AMP;D)
    $nama = html_entity_decode($nama, ENT_QUOTES | ENT_HTML5);

    $qs = $db->query('select', $table)
        ->get(["id as $alias"])
        ->where('LOWER(nama)', strtolower($nama))
        ->exec();

    $rs = $qs->fetch();

    if (!$rs || !isset($rs[$alias])) {
        return 0;
    }

    return $rs[$alias];
}
				
				for($i = 1; $i < count($sheetData); $i++){
					
					$id = $sheetData[$i][0];
					$nik = $sheetData[$i][1];
					$kode_finger = $sheetData[$i][2];
					$no_ktp = $sheetData[$i][3];
					$nama = $sheetData[$i][4];
					$divisi = $sheetData[$i][5]; //hovxxmh
					$department = $sheetData[$i][6]; //hodxxmh
					$bagian = $sheetData[$i][7]; //hobxxmh
					$unit_kerja = $sheetData[$i][8]; //hosxxmh
					$grup_jabatan = $sheetData[$i][9]; //hevgrmh
					$jabatan = $sheetData[$i][10]; //hetxxmh
					$area_kerja = $sheetData[$i][11]; //holxxmd_2
					$tipe = $sheetData[$i][12]; //heyxxmh
					$sub_tipe = $sheetData[$i][13]; //heyxxmd
					$status = $sheetData[$i][14]; //hesxxmh
					$grade = $sheetData[$i][15]; //hevxxmh
					// $tanggal_join = $sheetData[$i][16];
					// $tanggal_akhir_kontrak = $sheetData[$i][17];
					$grup_hk_raw = $sheetData[$i][18];

					if ($grup_hk_raw == 1) {
						$grup_hk = 5;
					} elseif ($grup_hk_raw == 2) {
						$grup_hk = 6;
					} else {
						$grup_hk = 0;
					}
					$gender = $sheetData[$i][19];
					//$harian_lepas = $sheetData[$i][20];
					$ptkp = $sheetData[$i][21];
					
					// cari nik
					$qs_hemxxmh = $db->query('select','hemxxmh')
						->get(['id as id_hemxxmh'])
						->where('kode',$nik)
						->exec();

					$rs_hemxxmh = $qs_hemxxmh->fetch();

					if (!$rs_hemxxmh || !isset($rs_hemxxmh['id_hemxxmh'])) {
						$emptyPeg[] = ['rowIndex' => $i + 1];
						continue;
					}

					$id_hemxxmh = $rs_hemxxmh['id_hemxxmh'];

					// mapping hemjbmh
					$id_hovxxmh = getId($db, 'hovxxmh', $divisi, 'id_hovxxmh');
					$id_hodxxmh = getId($db, 'hodxxmh', $department, 'id_hodxxmh');
					$id_hobxxmh = getId($db, 'hobxxmh', $bagian, 'id_hobxxmh');
					$id_hosxxmh = getId($db, 'hosxxmh', $unit_kerja, 'id_hosxxmh');
					$id_hevgrmh = getId($db, 'hevgrmh', $grup_jabatan, 'id_hevgrmh');
					$id_hetxxmh = getId($db, 'hetxxmh', $jabatan, 'id_hetxxmh');
					$id_holxxmd_2 = getId($db, 'holxxmd_2', $area_kerja, 'id_holxxmd_2');
					$id_heyxxmh = getId($db, 'heyxxmh', $tipe, 'id_heyxxmh');
					$id_heyxxmd = getId($db, 'heyxxmd', $sub_tipe, 'id_heyxxmd');
					$id_hesxxmh = getId($db, 'hesxxmh', $status, 'id_hesxxmh');
					$id_hevxxmh = getId($db, 'hevxxmh', $grade, 'id_hevxxmh');

					//hemdcmh
					// cari ptkp
					$qs_gtxpkmh = $db->query('select','gtxpkmh')
						->get(['id as id_gtxpkmh'])
						->where('kode',$ptkp)
						->exec();

					$rs_gtxpkmh = $qs_gtxpkmh->fetch();
					$id_gtxpkmh = ($rs_gtxpkmh && isset($rs_gtxpkmh['id_gtxpkmh']))
						? $rs_gtxpkmh['id_gtxpkmh']
						: 0;

					//Job
					$qu_hemjbmh = $db
						->raw()
						->bind(':id_hovxxmh', $id_hovxxmh)
						->bind(':id_hodxxmh', $id_hodxxmh)
						->bind(':id_hobxxmh', $id_hobxxmh)
						->bind(':id_hosxxmh', $id_hosxxmh)
						->bind(':id_hevgrmh', $id_hevgrmh)
						->bind(':id_hetxxmh', $id_hetxxmh)
						->bind(':id_holxxmd_2', $id_holxxmd_2)
						->bind(':id_heyxxmh', $id_heyxxmh)
						->bind(':id_heyxxmd', $id_heyxxmd)
						->bind(':id_hesxxmh', $id_hesxxmh)
						->bind(':id_hevxxmh', $id_hevxxmh)
						->bind(':grup_hk', $grup_hk)
						->bind(':id_hemxxmh', $id_hemxxmh)
						->exec("
							UPDATE hemjbmh a
							SET
								a.id_hovxxmh     = COALESCE(NULLIF(:id_hovxxmh, 0), 0),
								a.id_hodxxmh     = COALESCE(NULLIF(:id_hodxxmh, 0), 0),
								a.id_hobxxmh     = COALESCE(NULLIF(:id_hobxxmh, 0), 0),
								a.id_hosxxmh     = COALESCE(NULLIF(:id_hosxxmh, 0), 0),
								a.id_hevgrmh     = COALESCE(NULLIF(:id_hevgrmh, 0), 0),
								a.id_hetxxmh     = COALESCE(NULLIF(:id_hetxxmh, 0), 0),
								a.id_holxxmd_2   = COALESCE(NULLIF(:id_holxxmd_2, 0), 0),
								a.id_heyxxmh     = COALESCE(NULLIF(:id_heyxxmh, 0), 0),
								a.id_heyxxmd     = COALESCE(NULLIF(:id_heyxxmd, 0), 0),
								a.id_hesxxmh     = COALESCE(NULLIF(:id_hesxxmh, 0), 0),
								a.id_hevxxmh     = COALESCE(NULLIF(:id_hevxxmh, 0), 25),
								a.grup_hk        = COALESCE(NULLIF(:grup_hk, 0), a.grup_hk)
							WHERE a.id_hemxxmh = :id_hemxxmh
						");

						$qu_hemdcmh = $db
							->raw()
							->bind(':id_gtxpkmh', $id_gtxpkmh)
							->bind(':id_hemxxmh', $id_hemxxmh)
							->exec("
								UPDATE hemdcmh
								SET id_gtxpkmh = COALESCE(NULLIF(:id_gtxpkmh, 0), id_gtxpkmh)
								WHERE id_hemxxmh = :id_hemxxmh
							");

						if ($qu_hemjbmh && $qu_hemdcmh) {
							$dataupload++;
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
						"message" => "NIK tidak sesuai pada Baris " . $errorMessage,
						"type_message" => "danger"
					);
				} else {
					$data = array(
						"message" => "Update Data Karyawan Berhasil.</br>" .$dataupload. " data berhasil di import.</br>" . $datakembar. " data kembar TIDAK di import.",
						"type_message" => "success"
					);
				}

			
			$db->commit();
			}catch (PDOException $e){
				$db->rollback();
				$data = array(
					"message" => "Update Data Karyawan gagal," . $e,
					"type_message" => "danger"
				);
			}
		} else {
			$data = array(
				"message" => "Template Upload Komponen Grade salah!",
				"type_message" => "danger"
			);
		}
	}else{
		$data = array(
			"message" => "Update Data Karyawan gagal, format file salah!",
			"type_message" => "danger"
		);
	}

	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>