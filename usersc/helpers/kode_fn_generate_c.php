<?php
	/*
		Generate Autonumber untuk pengkodean dokumen
		Settingan ada di table sh_dokumen_number
		parameter dari front end:
		- nama_tabel		: digunakan untuk memilih table mana yang akan dibuat autonumber nya
		- kategori_dokumen	: pembeda kategori di dalam nama_table (jika ada)
							  misalnya: dalam table purchase_order, dibedakan atas PO Bahan Baku, PO Service
							  
		TO DO
		- dibedakan atas mhcompany dan mhcabang
		- pilihan bentuk format dokumen
			prefix/yymm/autonumber -> SPL/1908/0001
			prefix/yymm/autonumber/suffix -> SPL/1908/0001/ID
			
		changes log	:
		200803
			Recode 	: karena format tanggal sering tidak terbaca Tanggal dijadikan string 
			
		200102
			Add: Penambahan field Jumlah Digit Tahun: 2 | 4
		191216 
			Bug fix: Perbaikan pengambilan field tanggal yang salah
			Bug fix: Perbaikan counter angka 9 yang kurang 0 satu
	*/
	
	function integerToRoman($integer){
		// Convert the integer into an integer (just to make sure)
		$integer = intval($integer);
		$result = '';
	 
		// Create a lookup array that contains all of the Roman numerals.
		$lookup = array(
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1);
	 
		foreach($lookup as $roman => $value){
			// Determine the number of matches
			$matches = intval($integer/$value);
	 
			// Add the same number of characters to the string
			$result .= str_repeat($roman,$matches);
	 
			// Set the integer to be the remainder of the integer and the value
			$integer = $integer % $value;
		}
	 
		// The Roman numeral should be built, return it
		return $result;
	}
	
	$editor
		// input activity_log on insert
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$zero 				= "";
			$nama_tabel 		= $values['nama_tabel'];
			$nama_field_tanggal = $values['field_tanggal'];
			
			// cek inputan parameter field_tanggal dari front end
			// jika 'created_on'
			// jika tidak	: gunakan field tanggal
			if ($nama_field_tanggal == 'created_on'){
				$field_tanggal 		= date('Y-m-d');
				$nama_field_tanggal	= 'created_on';
			}else{
				$field_tanggal 	= date_create($values[$nama_tabel][$nama_field_tanggal]);
				$field_tanggal 	= date_format($field_tanggal,"Y-m-d");
			}

			// print_r (date_parse($field_tanggal)); // sudah kebaca format date
			$field_tanggal_dt	= strtotime($field_tanggal);
			 
			// cek parameter kategori_dokumen
			// jika blank		: tidak ada where kategori_dokumen
			// jika tidak blank	: tambahkan where kategori_dokumen dan kategori_dokumen_value
			$kategori_dokumen = $values['kategori_dokumen'];
			if ($values['kategori_dokumen'] == ''){
				$_and_where_kategori_dokumen = '';
			}else{
				$_and_where_kategori_dokumen = " AND kategori_dokumen = '" . $values['kategori_dokumen'] . "' AND kategori_dokumen_value = '" . $values['kategori_dokumen_value'] . "'";
			}
			
			// load prefix dokumen di nomordokumen_s
			$qs_gdnxxsh = "
				SELECT 
					*
				FROM gdnxxsh
				WHERE nama_tabel = '" . $values['nama_tabel'] . "' AND  is_active = 1" . $_and_where_kategori_dokumen;
				
				$r_gdnxxsh          = $editor->db()->sql($qs_gdnxxsh)->fetch();
				$mode 				= $r_gdnxxsh['mode'];
				$prefix             = $r_gdnxxsh['prefix'];
				$suffix             = $r_gdnxxsh['suffix'];
				$total_digit        = $r_gdnxxsh['total_digit'];
				$is_romawi_bulan    = $r_gdnxxsh['is_romawi_bulan'];
				$reset_by           = $r_gdnxxsh['reset_by'];
				$digit_tahun        = $r_gdnxxsh['digit_tahun'];
				$separator          = $r_gdnxxsh['separator'];
				$nama_field_tanggal = $r_gdnxxsh['field_tanggal'];
				$by_cabang 			= $r_gdnxxsh['by_cabang'];
			
			// check format tahun 2 digit atau 4 digit
			// menggunakan format string, bukan date
			if($digit_tahun == 2){
				$tahun = substr($field_tanggal,2,2);
			}else if ($digit_tahun == 4){
				$tahun = substr($field_tanggal,0,4);
			}
			
			/* *** FITUR BULAN ROMAWI DITIADAKAN DULU *** */
			// check apakah menggunakan bulan dalam angka romawi
			// if ($is_romawi_bulan == 1){
				// $bulan = integerToRoman(date_format ($field_tanggal, 'm'));
			// } else{
				// $bulan = date_format ($field_tanggal, 'm');
			// }
			$bulan = substr($field_tanggal,5,2);
			
			/* 
				start generate WHERE
				check dokumen reset per apa
			*/
			
			$where = "";
			$tanggal = $field_tanggal;
			
			if ($reset_by == "Tahun"){
				$where = $where . " WHERE YEAR(".$nama_field_tanggal.") = YEAR('" . $tanggal . "') ";
			}else if ($reset_by == "Bulan"){
				$where = $where . " WHERE YEAR(".$nama_field_tanggal.") = YEAR('" . $tanggal . "') AND MONTH(".$nama_field_tanggal.") = MONTH('" . $tanggal . "') ";
			}
			
			/* 
				check last kode berdasarkan kategori_dokumen
			*/
			if ($kategori_dokumen !== ""){
				$kategori_dokumen_value = $values['kategori_dokumen_value'];
				if ($where !== ""){ // jika sudah ada where sebelumnya (tambahkan AND)
					$where = $where . " AND " . $kategori_dokumen ." = '" . $kategori_dokumen_value . "'";
				}else{
					$where = " WHERE " . $kategori_dokumen ." = '" . $kategori_dokumen_value . "'";
				}
			}
			
			$sql_check_last_kode = "
				SELECT 
					id
				FROM " . $values['nama_tabel'] .
				$where
			;

			$result_check_last_kode = $editor->db()->sql($sql_check_last_kode);
			$count = $result_check_last_kode->count();
			$len = strlen($count);	// cek length digit number baru
			
			/* looping generate angka 0 sesuai setting digit */
			for ($x = 0; $x < ($total_digit - $len); $x++) {
				$zero = $zero . "0";
			} 
			
			$new_number = $zero . ($count);
			
			/* generate document number by mode */
			if ($mode == 1){
				/* mode 1: */
				if ($reset_by == "Tahun"){
					/* prefix/tahun + nomorurut */
					$formatted_kode = $prefix . $separator . $tahun . $new_number;
				}else if ($reset_by == "Bulan"){
					/* prefix/tahunbulan/nomorurut */
					$formatted_kode = $prefix . $separator . $tahun . $bulan . $separator . $new_number;
				}
			}else if ($mode == 2){
				/* 
					mode 2: 
					P00001
				*/
				$formatted_kode = $prefix . $new_number;
			}else if ($mode == 3){
				/* 
					mode 3: 
					391/SPL/PNW/VIII/2019
				*/
				$formatted_kode = $new_number . $separator . $prefix . $separator . $suffix . $separator . $bulan . $separator. $tahun ;
			}else if ($mode == 4){
				/*
					mode 4: 
					Kode Cabang/Prefix/Tahun/Bulan/nomorurut
				*/
				$formatted_kode = $_SESSION['kodemhcabang'] . $separator . $prefix . $separator . $tahun . $separator . $bulan . $separator . $new_number;
			}else if ($mode == 5){
				/* 
					mode 2: 
					Tahun00001
				*/
				$formatted_kode = $tahun . $new_number;
			}
			
			// update field kode
			$editor->db()
				->query('update', $values['nama_tabel'])
				->set('kode',$formatted_kode)
				->where('id',$id)
				->exec();
		});
	
?>