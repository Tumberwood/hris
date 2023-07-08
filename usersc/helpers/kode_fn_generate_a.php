<?php
    /*
        05 Dec 2022
        Ganti nama table ke standarisai nama table baru

        31 May 2022
        ini dipanggil dari tombol approval
        kode di generate saat apporval, menandakan dokumen sudah ready.
        
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
?>

<?php    
	require_once( "../../users/init.php" );
	require_once( "../../usersc/lib/DataTables.php" );
	require_once( "datatables_fn_debug.php" );
	
	// BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

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


	// BEGIN ambil data dari variable approval
	$state 			= $_POST['state'];
	$nama_tabel 	= $_POST['nama_tabel'];
	$id_transaksi 	= $_POST['id_transaksi'];
	// END ambil data dari variable approval
	
	if($state == 1){
		// BEGIN ambil data tambahan dari variable approval
		$kategori_dokumen 		= $_POST['kategori_dokumen'];
		$kategori_dokumen_value = $_POST['kategori_dokumen_value'];
		// END ambil data tambahan dari variable approval

		// BEGIN cek parameter kategori_dokumen
		// jika blank		: tidak ada where kategori_dokumen
		// jika tidak blank	: tambahkan where kategori_dokumen dan kategori_dokumen_value
		if ($_POST['kategori_dokumen'] == ''){
			$_and_where_kategori_dokumen = '';
		}else{
			$_and_where_kategori_dokumen = " AND kategori_dokumen = '" . $_POST['kategori_dokumen'] . "' AND kategori_dokumen_value = " . $_POST['kategori_dokumen_value'];
		}
		// END cek parameter kategori_dokumen

		// BEGIN ambil data dari table gdnxxsh
        $qs_gdnxxsh = "
			SELECT
				*
			FROM 
				gdnxxsh
			WHERE 
				nama_tabel = '$nama_tabel' $_and_where_kategori_dokumen
		";
		$r_gdnxxsh              = $db->sql($qs_gdnxxsh)->fetch();
		$mode 					= $r_gdnxxsh['mode'];
		$prefix                 = $r_gdnxxsh['prefix'];
		$suffix                 = $r_gdnxxsh['suffix'];
		$total_digit            = $r_gdnxxsh['total_digit'];
		$is_romawi_bulan        = $r_gdnxxsh['is_romawi_bulan'];
		$reset_by               = $r_gdnxxsh['reset_by'];
		$digit_tahun            = $r_gdnxxsh['digit_tahun'];
		$separator              = $r_gdnxxsh['separator'];
		$nama_field_tanggal     = $r_gdnxxsh['field_tanggal'];
		$by_cabang 				= $r_gdnxxsh['by_cabang'];
		// END ambil data dari table gdnxxsh

		// BEGIN ambil data dari table gdnxxsh
		$qs_transaksi = "
			SELECT
				*
			FROM 
				$nama_tabel
			WHERE 
				id = $id_transaksi
		";
		$r_transaksi = $db->sql($qs_transaksi)->fetch();
		// END ambil data dari table transaksi

		// BEGIN check field tanggal yang digunakan
		// jika null	: gunakan today *** FRONT END HARUS STRING ***
		// jika tidak	: gunakan field tanggal
		if ( is_null($nama_field_tanggal) || $nama_field_tanggal == '' ){
			$nama_field_tanggal = 'created_on';
			$field_tanggal 		= date('Y-m-d');
		}else{
			$field_tanggal_transaksi = $r_transaksi[$nama_field_tanggal];
			$field_tanggal 			 = date_create($field_tanggal_transaksi);
			$field_tanggal 			 = date_format($field_tanggal,"Y-m-d");
		}
		$field_tanggal_dt	= strtotime($field_tanggal);
		// END check field tanggal yang digunakan

		// BEGIN check format tahun 2 digit atau 4 digit
		// menggunakan format string, bukan date
		if($digit_tahun == 2){
			$tahun = substr($field_tanggal,2,2);
		}else if ($digit_tahun == 4){
			$tahun = substr($field_tanggal,0,4);
		}
		// END check format tahun 2 digit atau 4 digit

		/* *** FITUR BULAN ROMAWI DITIADAKAN DULU *** */
		// check apakah menggunakan bulan dalam angka romawi
		// if ($is_romawi_bulan == 1){
			// $bulan = integerToRoman(date_format ($field_tanggal, 'm'));
		// } else{
			// $bulan = date_format ($field_tanggal, 'm');
		// }
		$bulan = substr($field_tanggal,5,2);

		// BEGIN generate WHERE
		$where = " WHERE is_active = 1 AND is_approve <> 0";
		$tanggal = $field_tanggal;
	
		// BEGIN check nomor dokumen direset per Tahun, Bulan atau tanpa reset
		if ($reset_by == "Tahun"){
			$where = $where . " AND YEAR(".$nama_field_tanggal.") = YEAR('" . $tanggal . "') ";
		}else if ($reset_by == "Bulan"){
			$where = $where . " AND YEAR(".$nama_field_tanggal.") = YEAR('" . $tanggal . "') AND MONTH(".$nama_field_tanggal.") = MONTH('" . $tanggal . "') ";
		}
		// END check nomor dokumen direset per Tahun, Bulan atau tanpa reset

		// BEGIN check last kode berdasarkan kategori_dokumen
		if ($kategori_dokumen !== ""){
			$where = $where . " AND " . $kategori_dokumen ." = " . $kategori_dokumen_value;
		}
		// END check last kode berdasarkan kategori_dokumen

		// BEGIN ambil id
		$qs_last_kode = "
			SELECT 
				id
			FROM " . $nama_tabel .
			$where
		; 
		$r_last_kode = $db->sql($qs_last_kode);
		$count                  = $r_last_kode->count();
		$len                    = strlen($count);	// cek length digit number baru

		/* BEGIN looping generate angka 0 sesuai setting digit */
		$zero = "";
		for ($x = 0; $x < ($total_digit - $len); $x++) {
			$zero = $zero . "0";
		} 
		$new_number = $zero . ($count);
		/* END looping generate angka 0 sesuai setting digit */

		/* generate document number by mode */
		if ($mode == 1){
			// mode 1	: 
			// 	contoh reset bulan: PO/2201/0123	
			// 	contoh reset tahun: PO/22/0123
			
			// parameter:
			// - $reset_by
			// - $prefix
			// - $separator
			// - $tahun
			if ($reset_by == "Tahun"){
				// prefix/tahun + nomorurut
				$formatted_kode = $prefix . $separator . $tahun . $new_number;
			}else if ($reset_by == "Bulan"){
				// prefix/tahunbulan/nomorurut
				$formatted_kode = $prefix . $separator . $tahun . $bulan . $separator . $new_number;
			}
		}else if ($mode == 2){
			// mode 2: 
			//	contoh: P00001

			// parameter:
			// - $prefix
			$formatted_kode = $prefix . $new_number;
		}else if ($mode == 3){
			// mode 3: 
			// 	contoh: 391/SPL/PNW/VIII/2019

			// parameter:
			// - $reset_by
			// - $prefix
			// - $suffix
			// - $bulan
			// - $separator
			// - $tahun
			$formatted_kode = $new_number . $separator . $prefix . $separator . $suffix . $separator . $bulan . $separator. $tahun ;
		}else if ($mode == 4){
			/*
				mode 4: 
				Kode Cabang/Prefix/Tahun/Bulan/nomorurut
			*/
			$formatted_kode = $_SESSION['kodemhcabang'] . $separator . $prefix . $separator . $tahun . $separator . $bulan . $separator . $new_number;
		}

		// BEGIN update field kode
		$result = $db
			->query('update', $nama_tabel)
			->set('kode',$formatted_kode)
			->where('id',$id_transaksi)
			->exec();
		// END update field kode

		if ($result){
			$message      = 'Generate Kode Berhasil';
			$type_message = 'success';
		}else{
			$message      = 'Generate Kode Gagal!';
			$type_message = 'danger';
		} 
		
	}elseif($state == 2){
		// cancel Approve
		// no dokumen dihapus
		// cek apakah beda bulan, jika beda bulan, tidak bisa dilakukan
		$message 		= '';
		$type_message 	= '';
		$formatted_kode = '';
		
	}elseif($state == -9){
		// void
		// void tidak mengubah nomor dokumen
		// nomor dokumen yang sudah di generate saat approve, tetap dipakai, 
		// tetapi status dokumen menjadi void dan transaksi tidak dapat digunakan lagi
		$message 		= '';
		$type_message 	= '';
		$formatted_kode = '';
	}
	
	$data = array(
		'message'      	=> $message,
		'type_message' 	=> $type_message,
		'kode' 			=> $formatted_kode
	);

	// tampilkan results
    require_once( "fn_ajax_results.php" );



?>