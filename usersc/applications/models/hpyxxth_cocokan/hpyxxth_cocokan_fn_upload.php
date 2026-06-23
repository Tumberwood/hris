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
		$sheetNames = $spreadsheet->getSheetNames();

		 // Ambil semua nama sheet
		$id_hpyxxth = $_POST['id_hpyxxth'];
		
		$qd_hpyemtd_cocokan = $db
			->query('delete', 'hpyemtd_cocokan')
			->where('id_hpyxxth', $id_hpyxxth)
			->exec()
		;

		foreach ($sheetNames as $sheetIndex => $sheetName) {
			if (in_array($sheetName, ['TETAP', 'KONTRAK', 'KBM REG'])) {
				$sheetData = $spreadsheet->getSheet($sheetIndex)->toArray();
				
				if ($sheetData[0][1] == "no_induk") {
					for ($i = 2; $i < count($sheetData); $i++) {

						if ($sheetName == 'TETAP') {

							$nourut                 = strtoupper($sheetData[$i][0]);
							$nrp                    = strtoupper($sheetData[$i][1]);
							$nama                   = strtoupper($sheetData[$i][2]);
							$no_rekening            = strtoupper($sheetData[$i][3]);
							$ktp                    = strtoupper($sheetData[$i][4]);
							$npwp                   = strtoupper($sheetData[$i][5]);

							$gp                     = strtoupper($sheetData[$i][6]);
							$tj_khusus              = strtoupper($sheetData[$i][7]);
							$t_jab                  = strtoupper($sheetData[$i][8]);

							$uniform                = strtoupper($sheetData[$i][9]);
							$komp_rekontrak         = strtoupper($sheetData[$i][10]);
							$terima_lain            = strtoupper($sheetData[$i][11]);
							$var_cost               = strtoupper($sheetData[$i][12]);
							$fix_cost               = strtoupper($sheetData[$i][13]);
							$premi_abs              = strtoupper($sheetData[$i][14]);

							$bpjs_kes_perusahaan    = strtoupper($sheetData[$i][15]);

							$lembur15               = strtoupper($sheetData[$i][16]);
							$rp_lembur15            = strtoupper($sheetData[$i][17]);

							$lembur2                = strtoupper($sheetData[$i][18]);
							$rp_lembur2             = strtoupper($sheetData[$i][19]);

							$lembur3                = strtoupper($sheetData[$i][20]);
							$rp_lembur3             = strtoupper($sheetData[$i][21]);

							$lembur4                = strtoupper($sheetData[$i][22]);
							$rp_lembur4             = strtoupper($sheetData[$i][23]);

							$total_lembur_jam_final = strtoupper($sheetData[$i][24]);
							$total_rp_lembur        = strtoupper($sheetData[$i][25]);

							$bulatlembur            = strtoupper($sheetData[$i][26]);
							$lemburbersih           = strtoupper($sheetData[$i][27]);

							$pot_makan              = strtoupper($sheetData[$i][28]);
							$pot_pph21              = strtoupper($sheetData[$i][29]);
							$after_pph21            = strtoupper($sheetData[$i][30]);

							$jkk                    = strtoupper($sheetData[$i][31]);
							$pot_jht_karyawan       = strtoupper($sheetData[$i][32]);

							$pot_lain_after_pph     = strtoupper($sheetData[$i][33]);
							$iuran_spsi             = strtoupper($sheetData[$i][34]);
							$pot_upah               = strtoupper($sheetData[$i][35]);

							$bpjs_kes_karyawan      = strtoupper($sheetData[$i][36]);
							$pot_jp_karyawan        = strtoupper($sheetData[$i][37]);

							$gaji_bersih            = strtoupper($sheetData[$i][38]);
							$bulat                  = strtoupper($sheetData[$i][39]);
							$gaji_terima            = strtoupper($sheetData[$i][40]);

							$cek_nik                = strtoupper($sheetData[$i][41]);
							$cek_nama               = strtoupper($sheetData[$i][42]);
							$komp_sisa_cuti         = strtoupper($sheetData[$i][43]);

						} elseif ($sheetName == 'KONTRAK') {

							$nourut                 = strtoupper($sheetData[$i][0]);
							$nrp                    = strtoupper($sheetData[$i][1]);
							$nama                   = strtoupper($sheetData[$i][2]);
							$no_rekening            = strtoupper($sheetData[$i][3]);
							$ktp                    = strtoupper($sheetData[$i][4]);
							$npwp                   = strtoupper($sheetData[$i][5]);

							$gp                     = strtoupper($sheetData[$i][6]);
							$tj_khusus              = strtoupper($sheetData[$i][7]);
							$t_jab                  = strtoupper($sheetData[$i][8]);

							$uniform                = strtoupper($sheetData[$i][9]);
							$komp_rekontrak         = strtoupper($sheetData[$i][10]);
							$terima_lain            = strtoupper($sheetData[$i][11]);
							$var_cost               = strtoupper($sheetData[$i][12]);
							$fix_cost               = strtoupper($sheetData[$i][13]);
							$premi_abs              = strtoupper($sheetData[$i][14]);

							$bpjs_kes_perusahaan    = strtoupper($sheetData[$i][15]);

							$lembur15               = strtoupper($sheetData[$i][16]);
							$rp_lembur15            = strtoupper($sheetData[$i][17]);

							$lembur2                = strtoupper($sheetData[$i][18]);
							$rp_lembur2             = strtoupper($sheetData[$i][19]);

							$lembur3                = strtoupper($sheetData[$i][20]);
							$rp_lembur3             = strtoupper($sheetData[$i][21]);

							$lembur4                = strtoupper($sheetData[$i][22]);
							$rp_lembur4             = strtoupper($sheetData[$i][23]);

							$total_lembur_jam_final = strtoupper($sheetData[$i][24]);
							$total_rp_lembur        = strtoupper($sheetData[$i][25]);

							$bulatlembur            = strtoupper($sheetData[$i][26]);
							$lemburbersih           = strtoupper($sheetData[$i][27]);

							$pot_makan              = strtoupper($sheetData[$i][28]);
							$pot_pph21              = strtoupper($sheetData[$i][29]);
							$after_pph21            = strtoupper($sheetData[$i][30]);

							$jkk                    = strtoupper($sheetData[$i][31]);
							$pot_jht_karyawan       = strtoupper($sheetData[$i][32]);

							$pot_lain_after_pph     = strtoupper($sheetData[$i][33]);
							$iuran_spsi             = strtoupper($sheetData[$i][34]);
							$pot_upah               = strtoupper($sheetData[$i][35]);

							$bpjs_kes_karyawan      = strtoupper($sheetData[$i][36]);
							$pot_jp_karyawan        = strtoupper($sheetData[$i][37]);

							$gaji_bersih            = strtoupper($sheetData[$i][38]);
							$bulat                  = strtoupper($sheetData[$i][39]);
							$gaji_terima            = strtoupper($sheetData[$i][40]);

							$cek_nik                = strtoupper($sheetData[$i][41]);
							$cek_nama               = strtoupper($sheetData[$i][42]);

							$komp_sisa_cuti         = '';

						} elseif ($sheetName == 'KBM REG') {

							$nourut                 = strtoupper($sheetData[$i][0]);
							$nrp                    = strtoupper($sheetData[$i][1]);
							$nama                   = strtoupper($sheetData[$i][2]);

							$ktp                    = strtoupper($sheetData[$i][3]);
							$npwp                   = strtoupper($sheetData[$i][4]);

							$gp                     = strtoupper($sheetData[$i][5]);
							$tj_khusus              = strtoupper($sheetData[$i][6]);
							$t_jab                  = strtoupper($sheetData[$i][7]);

							$uniform                = strtoupper($sheetData[$i][8]);
							$komp_rekontrak         = strtoupper($sheetData[$i][9]);
							$terima_lain            = strtoupper($sheetData[$i][10]);
							$var_cost               = strtoupper($sheetData[$i][11]);
							$fix_cost               = strtoupper($sheetData[$i][12]);
							$premi_abs              = strtoupper($sheetData[$i][13]);

							$bpjs_kes_perusahaan    = strtoupper($sheetData[$i][14]);

							$lembur15               = strtoupper($sheetData[$i][15]);
							$rp_lembur15            = strtoupper($sheetData[$i][16]);

							$lembur2                = strtoupper($sheetData[$i][17]);
							$rp_lembur2             = strtoupper($sheetData[$i][18]);

							$lembur3                = strtoupper($sheetData[$i][19]);
							$rp_lembur3             = strtoupper($sheetData[$i][20]);

							$lembur4                = strtoupper($sheetData[$i][21]);
							$rp_lembur4             = strtoupper($sheetData[$i][22]);

							$total_lembur_jam_final = strtoupper($sheetData[$i][23]);
							$total_rp_lembur        = strtoupper($sheetData[$i][24]);

							$bulatlembur            = strtoupper($sheetData[$i][25]);
							$lemburbersih           = strtoupper($sheetData[$i][26]);

							$pot_makan              = strtoupper($sheetData[$i][27]);
							$pot_pph21              = strtoupper($sheetData[$i][28]);
							$after_pph21            = strtoupper($sheetData[$i][29]);

							$jkk                    = strtoupper($sheetData[$i][30]);
							$pot_jht_karyawan       = strtoupper($sheetData[$i][31]);

							$pot_lain_after_pph     = strtoupper($sheetData[$i][32]); // pot_kop
							$iuran_spsi             = strtoupper($sheetData[$i][33]);
							$pot_upah               = strtoupper($sheetData[$i][34]);

							$bpjs_kes_karyawan      = strtoupper($sheetData[$i][35]);
							$pot_jp_karyawan        = strtoupper($sheetData[$i][36]);

							$gaji_bersih            = strtoupper($sheetData[$i][37]);
							$bulat                  = strtoupper($sheetData[$i][38]);
							$gaji_terima            = strtoupper($sheetData[$i][39]);

							$cek_nik                = strtoupper($sheetData[$i][40]);
							$cek_nama               = strtoupper($sheetData[$i][41]);

							$no_rekening            = '';
							$komp_sisa_cuti         = '';

						}
						$qs_hemxxmh = $db
							->query('select', 'hemxxmh' )
							->get(['id'] )
							->where('kode', $nrp )
							->exec();
						$rs_hemxxmh = $qs_hemxxmh->fetch();
						if (empty($rs_hemxxmh)) {
							$id_hemxxmh = 0;
						} else {
							$id_hemxxmh = $rs_hemxxmh['id'];
						}
	
						$qi_hpyemtd_cocokan = $db
							->query('insert', 'hpyemtd_cocokan')
							->set('id_hpyxxth', $id_hpyxxth)
							// ->set('id_hpyxxth_cocok', $id_hpyxxth_cocok)

							->set('id_hemxxmh', $id_hemxxmh)
							->set('nrp', $nrp)
							->set('nama', $nama)

							->set('no_rekening', $no_rekening)
							->set('ktp', $ktp)
							->set('npwp', $npwp)

							->set('gp', $gp)
							->set('tj_khusus', $tj_khusus)
							->set('t_jab', $t_jab)

							->set('terima_lain', $terima_lain)
							->set('var_cost', $var_cost)
							->set('fix_cost', $fix_cost)
							->set('premi_abs', $premi_abs)

							->set('bpjs_kes_perusahaan', $bpjs_kes_perusahaan)

							->set('lembur15', $lembur15)
							->set('rp_lembur15', $rp_lembur15)

							->set('lembur2', $lembur2)
							->set('rp_lembur2', $rp_lembur2)

							->set('lembur3', $lembur3)
							->set('rp_lembur3', $rp_lembur3)

							->set('total_lembur_jam_final', $total_lembur_jam_final)
							->set('total_rp_lembur', $total_rp_lembur)

							->set('pot_makan', $pot_makan)
							->set('pot_pph21', $pot_pph21)
							->set('after_pph21', $after_pph21)

							->set('jkk', $jkk)
							->set('pot_jht_karyawan', $pot_jht_karyawan)

							->set('pot_lain_after_pph', $pot_lain_after_pph)
							->set('iuran_spsi', $iuran_spsi)
							->set('pot_upah', $pot_upah)

							->set('bpjs_kes_karyawan', $bpjs_kes_karyawan)
							->set('pot_jp_karyawan', $pot_jp_karyawan)

							->set('gaji_bersih', $gaji_bersih)
							->set('bulat', $bulat)
							->set('gaji_terima', $gaji_terima)

							->set('komp_sisa_cuti', $komp_sisa_cuti)
							->exec()
						;
					}
	
					$data = [
						"message" => "Upload berhasil!",
						"type_message" => "success",
					];
				} else {
					$data = array(
						"message" => "Template file salah!",
						"type_message" => "danger"
					);
				}
			}
		}
	}else{
		$data = array(
			"message" => "Format file salah!",
			"type_message" => "danger"
		);
	}
	
	// tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>