<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );

	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	// ----------- do not erase
	$show_inactive_status = $_POST['show_inactive_status_hpyemtd'];
	// -----------
	
	if ( ! isset($_POST['id_hpyxxth']) || ! is_numeric($_POST['id_hpyxxth']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hpyemtd' )
			->debug(true)
			->fields(
				Field::inst( 'hpyemtd.id' ),
				Field::inst( 'hpyemtd.jam_lembur_final' ),
				Field::inst( 'hpyemtd.id_hpyxxth' ),
				Field::inst( 'hpyemtd.id_hemxxmh' ),
				Field::inst( 'hpyemtd.id_heyxxmh' ),
				Field::inst( 'hpyemtd.kode' ),
				Field::inst( 'hpyemtd.nama' ),
				Field::inst( 'hpyemtd.keterangan' ),
				Field::inst( 'hpyemtd.is_active' ),
				Field::inst( 'hpyemtd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hpyemtd.total_nominal' ),
				Field::inst( 'hpyemtd.gp' ),
				Field::inst( 'hpyemtd.t_jab' ),
				Field::inst( 'hpyemtd.premi_abs' ),
				Field::inst( 'hpyemtd.lembur15' ),
				Field::inst( 'hpyemtd.lembur2' ),
				Field::inst( 'hpyemtd.lembur3' ),
				Field::inst( 'hpyemtd.rp_lembur15' ),
				Field::inst( 'hpyemtd.rp_lembur2' ),
				Field::inst( 'hpyemtd.rp_lembur3' ),
				Field::inst( 'hpyemtd.jam_lembur' ),
				Field::inst( 'hpyemtd.lemburbersih' ),
				Field::inst( 'hpyemtd.pot_makan' ),
				Field::inst( 'hpyemtd.var_cost' ), 
				Field::inst( 'hpyemtd.fix_cost' ), //masa kerja
				Field::inst( 'hpyemtd.jkk' ), 
				Field::inst( 'hpyemtd.jkm' ), 
				Field::inst( 'hpyemtd.trm_jkkjkm' ), 
				Field::inst( 'hpyemtd.pendapatan_lain' ), 
				Field::inst( 'hpyemtd.pot_jkkjkm' ), 
				Field::inst( 'hpyemtd.pot_jht' ), 
				Field::inst( 'hpyemtd.pot_upah' ), 
				Field::inst( 'hpyemtd.pot_bpjs' ), 
				Field::inst( 'hpyemtd.pot_psiun' ), 
				Field::inst( 'hpyemtd.pot_pinjaman' ), 
				Field::inst( 'hpyemtd.pot_klaim' ), 
				Field::inst( 'hpyemtd.pot_denda_apd' ), 
				Field::inst( 'hpyemtd.pot_pph21' ), 
				Field::inst( 'hpyemtd.gaji_bersih' ), 
				Field::inst( 'hpyemtd.bulat' ), 
				Field::inst( 'hpyemtd.gaji_terima' ), 
				Field::inst( 'hpyemtd.pot_jam' ), 
				Field::inst( 'hpyemtd.pph21_back' ), 
				Field::inst( 'hpyemtd.kompensasi_ak' ),  
				Field::inst( 'hpyemtd.koreksi_lembur' ),
				Field::inst( 'hpyemtd.koreksi_status' ),
				Field::inst( 'hpyemtd.overtime_susulan' ),
				Field::inst( 'hpyemtd.sisa_cuti' ),
				Field::inst( 'hpyemtd.pot_lain' ),
				
				// IDENTITAS TAMBAHAN
				Field::inst( 'hpyemtd.nrp' ),
				Field::inst( 'hpyemtd.departemen' ),
				Field::inst( 'hpyemtd.jabatan' ),
				Field::inst( 'hpyemtd.tipe' ),
				Field::inst( 'hpyemtd.sub_tipe' ),
				Field::inst( 'hpyemtd.status_peg' ),

				Field::inst( 'hpyemtd.ptkp' ),
				Field::inst( 'hpyemtd.no_rekening' ),
				Field::inst( 'hpyemtd.ktp' ),
				Field::inst( 'hpyemtd.npwp' ),

				// TAMBAHAN GAJI
				Field::inst( 'hpyemtd.terima_lain' ),

				Field::inst( 'hpyemtd.lembur15_final' ),
				Field::inst( 'hpyemtd.lembur2_final' ),
				Field::inst( 'hpyemtd.lembur3_final' ),

				Field::inst( 'hpyemtd.total_lembur_jam' ),
				Field::inst( 'hpyemtd.total_lembur_jam_final' ),
				Field::inst( 'hpyemtd.total_rp_lembur' ),

				Field::inst( 'hpyemtd.komp_rekontrak' ),
				
				Field::inst( 'hpyemtd.komp_sisa_cuti' ),
				Field::inst( 'hpyemtd.cuti_tahunan' ),
				Field::inst( 'hpyemtd.cuti_bersama' ),
				Field::inst( 'hpyemtd.sisa_cuti_hari' ),
				Field::inst( 'hpyemtd.c_pot_upah' ),
				Field::inst( 'hpyemtd.c_pot_jam' ),
				
				Field::inst( 'hpyemtd.c_pot_resign' ),
				Field::inst( 'hpyemtd.pot_resign' ),

				Field::inst( 'hpyemtd.thr' ),

				// BEFORE PPH
				Field::inst( 'hpyemtd.pendapatan_lain_before_pph' ),
				Field::inst( 'hpyemtd.pot_lain_before_pph' ),

				// BPJS PERUSAHAAN
				Field::inst( 'hpyemtd.bpjs_kes_perusahaan' ),

				// TER / PAJAK
				Field::inst( 'hpyemtd.kategori_kelas' ),
				Field::inst( 'hpyemtd.persen_ter' ),
				Field::inst( 'hpyemtd.after_pph21' ),

				// BPJS DETAIL
				Field::inst( 'hpyemtd.jht_perusahaan' ),
				Field::inst( 'hpyemtd.jp_perusahaan' ),

				Field::inst( 'hpyemtd.pot_jht_karyawan' ),
				Field::inst( 'hpyemtd.pot_jp_karyawan' ),
				Field::inst( 'hpyemtd.bpjs_kes_karyawan' ),

				// POTONGAN TAMBAHAN
				Field::inst( 'hpyemtd.pot_piutang' ),
				Field::inst( 'hpyemtd.denda_apd' ),
				Field::inst( 'hpyemtd.iuran_spsi' ),

				Field::inst( 'hpyemtd.pendapatan_lain_after_pph' ),
				Field::inst( 'hpyemtd.pot_lain_after_pph' ),
				Field::inst( 'hpyemtd.tj_khusus' ),

				// BRUTO (WAJIB ADA)
				Field::inst( 'hpyemtd.bruto' ),

				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hevxxmh.nama' ),
				Field::inst( 'heyxxmh.nama' ),
				Field::inst( 'heyxxmd.nama' ),
				Field::inst( 'hesxxmh.nama' ),
				
				Field::inst( 'hemxxmh.kode as kode' ),
				Field::inst( 'hemxxmh.nama as nama' ),
				Field::inst( 'hemxxmh.gender' ),
				
				Field::inst( 'hevgrmh.nama' ),
				Field::inst( 'hobxxmh.nama' ),
				Field::inst( 'hovxxmh.nama' ),
				Field::inst( 'hosxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hpyemtd.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
			->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
			->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
			->leftJoin( 'hevgrmh','hevgrmh.id','=','hemjbmh.id_hevgrmh' )
			->leftJoin( 'hobxxmh','hobxxmh.id','=','hemjbmh.id_hobxxmh' )
			->leftJoin( 'hovxxmh','hovxxmh.id','=','hemjbmh.id_hovxxmh' )
			->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
			
			->where('hpyemtd.id_hpyxxth',$_POST['id_hpyxxth'])
			->where('heyxxmd.id', 1)
			->where('hesxxmh.id', 4)
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hpyemtd.is_active', 1);
		}
		
		include( "hpyemtd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>