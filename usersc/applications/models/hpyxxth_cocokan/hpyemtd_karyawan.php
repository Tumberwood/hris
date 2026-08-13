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
		$editor = Editor::inst( $db, 'hpyemtd_cocokan' )
			->debug(true)
			->fields(
				Field::inst( 'hpyemtd_cocokan.id' ),
				Field::inst( 'hpyemtd_cocokan.jam_lembur_final' ),
				Field::inst( 'hpyemtd_cocokan.id_hpyxxth' ),
				Field::inst( 'hpyemtd_cocokan.id_hemxxmh' ),
				Field::inst( 'hpyemtd_cocokan.id_heyxxmh' ),
				Field::inst( 'hpyemtd_cocokan.kode' ),
				Field::inst( 'hpyemtd_cocokan.nama' ),
				Field::inst( 'hpyemtd_cocokan.keterangan' ),
				Field::inst( 'hpyemtd_cocokan.is_active' ),
				Field::inst( 'hpyemtd_cocokan.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_cocokan.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_cocokan.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hpyemtd_cocokan.total_nominal' ),
				
				Field::inst( 'hpyemtd_cocokan.gp' ),
				Field::inst( 'hpyemtd_cocokan.t_jab' ),
				Field::inst( 'hpyemtd_cocokan.premi_abs' ),
				Field::inst( 'hpyemtd_cocokan.lembur15' ),
				Field::inst( 'hpyemtd_cocokan.lembur2' ),
				Field::inst( 'hpyemtd_cocokan.lembur3' ),
				Field::inst( 'hpyemtd_cocokan.rp_lembur15' ),
				Field::inst( 'hpyemtd_cocokan.rp_lembur2' ),
				Field::inst( 'hpyemtd_cocokan.rp_lembur3' ),
				Field::inst( 'hpyemtd_cocokan.jam_lembur' ),
				Field::inst( 'hpyemtd_cocokan.lemburbersih' ),
				Field::inst( 'hpyemtd_cocokan.pot_makan' ),
				Field::inst( 'hpyemtd_cocokan.var_cost' ), 
				Field::inst( 'hpyemtd_cocokan.fix_cost' ), //masa kerja
				Field::inst( 'hpyemtd_cocokan.jkk' ), 
				Field::inst( 'hpyemtd_cocokan.jkm' ), 
				Field::inst( 'hpyemtd_cocokan.trm_jkkjkm' ), 
				Field::inst( 'hpyemtd_cocokan.pendapatan_lain' ), 
				Field::inst( 'hpyemtd_cocokan.pot_jkkjkm' ), 
				Field::inst( 'hpyemtd_cocokan.pot_jht' ), 
				Field::inst( 'hpyemtd_cocokan.pot_upah' ), 
				Field::inst( 'hpyemtd_cocokan.pot_resign' ), 
				Field::inst( 'hpyemtd_cocokan.pot_bpjs' ), 
				Field::inst( 'hpyemtd_cocokan.pot_psiun' ), 
				Field::inst( 'hpyemtd_cocokan.pot_pinjaman' ), 
				Field::inst( 'hpyemtd_cocokan.pot_klaim' ), 
				Field::inst( 'hpyemtd_cocokan.pot_denda_apd' ), 
				Field::inst( 'hpyemtd_cocokan.pot_pph21' ), 
				Field::inst( 'hpyemtd_cocokan.gaji_bersih' ), 
				Field::inst( 'hpyemtd_cocokan.bulat' ), 
				Field::inst( 'hpyemtd_cocokan.gaji_terima' ), 
				Field::inst( 'hpyemtd_cocokan.pot_jam' ), 
				Field::inst( 'hpyemtd_cocokan.pph21_back' ), 
				Field::inst( 'hpyemtd_cocokan.kompensasi_ak' ),  
				Field::inst( 'hpyemtd_cocokan.koreksi_lembur' ),
				Field::inst( 'hpyemtd_cocokan.koreksi_status' ),
				Field::inst( 'hpyemtd_cocokan.overtime_susulan' ),
				Field::inst( 'hpyemtd_cocokan.sisa_cuti' ),
				Field::inst( 'hpyemtd_cocokan.pot_lain' ),
				
				// IDENTITAS TAMBAHAN
				Field::inst( 'hpyemtd_cocokan.nrp' ),
				Field::inst( 'hpyemtd_cocokan.departemen' ),
				Field::inst( 'hpyemtd_cocokan.jabatan' ),
				Field::inst( 'hpyemtd_cocokan.tipe' ),
				Field::inst( 'hpyemtd_cocokan.sub_tipe' ),
				Field::inst( 'hpyemtd_cocokan.status_peg' ),

				Field::inst( 'hpyemtd_cocokan.ptkp' ),
				Field::inst( 'hpyemtd_cocokan.no_rekening' ),
				Field::inst( 'hpyemtd_cocokan.ktp' ),
				Field::inst( 'hpyemtd_cocokan.npwp' ),

				// TAMBAHAN GAJI
				Field::inst( 'hpyemtd_cocokan.terima_lain' ),

				Field::inst( 'hpyemtd_cocokan.lembur15_final' ),
				Field::inst( 'hpyemtd_cocokan.lembur2_final' ),
				Field::inst( 'hpyemtd_cocokan.lembur3_final' ),

				Field::inst( 'hpyemtd_cocokan.total_lembur_jam' ),
				Field::inst( 'hpyemtd_cocokan.total_lembur_jam_final' ),
				Field::inst( 'hpyemtd_cocokan.total_rp_lembur' ),

				Field::inst( 'hpyemtd_cocokan.komp_rekontrak' ),

				Field::inst( 'hpyemtd_cocokan.komp_sisa_cuti' ),
				Field::inst( 'hpyemtd_cocokan.cuti_tahunan' ),
				Field::inst( 'hpyemtd_cocokan.cuti_bersama' ),
				Field::inst( 'hpyemtd_cocokan.sisa_cuti_hari' ),
				Field::inst( 'hpyemtd_cocokan.c_pot_upah' ),
				Field::inst( 'hpyemtd_cocokan.c_pot_resign' ),
				Field::inst( 'hpyemtd_cocokan.c_pot_jam' ),

				Field::inst( 'hpyemtd_cocokan.thr' ),

				// BEFORE PPH
				Field::inst( 'hpyemtd_cocokan.pendapatan_lain_before_pph' ),
				Field::inst( 'hpyemtd_cocokan.pot_lain_before_pph' ),

				// BPJS PERUSAHAAN
				Field::inst( 'hpyemtd_cocokan.bpjs_kes_perusahaan' ),

				// TER / PAJAK
				Field::inst( 'hpyemtd_cocokan.kategori_kelas' ),
				Field::inst( 'hpyemtd_cocokan.persen_ter' ),
				Field::inst( 'hpyemtd_cocokan.after_pph21' ),

				// BPJS DETAIL
				Field::inst( 'hpyemtd_cocokan.jht_perusahaan' ),
				Field::inst( 'hpyemtd_cocokan.jp_perusahaan' ),

				Field::inst( 'hpyemtd_cocokan.pot_jht_karyawan' ),
				Field::inst( 'hpyemtd_cocokan.pot_jp_karyawan' ),
				Field::inst( 'hpyemtd_cocokan.bpjs_kes_karyawan' ),

				// POTONGAN TAMBAHAN
				Field::inst( 'hpyemtd_cocokan.pot_piutang' ),
				Field::inst( 'hpyemtd_cocokan.denda_apd' ),
				Field::inst( 'hpyemtd_cocokan.iuran_spsi' ),

				Field::inst( 'hpyemtd_cocokan.pendapatan_lain_after_pph' ),
				Field::inst( 'hpyemtd_cocokan.pot_lain_after_pph' ),
				Field::inst( 'hpyemtd_cocokan.tj_khusus' ),

				// BRUTO (WAJIB ADA)
				Field::inst( 'hpyemtd_cocokan.bruto' ),

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
				Field::inst( 'hpyemtd.pot_resign' ), 
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
				//Field::inst( 'hpyemtd.premi_abs - hpyemtd_cocokan.total_rp_lembur + hpyemtd.total_rp_lembur + hpyemtd_cocokan.pot_upah - hpyemtd.pot_jam - hpyemtd.pot_lain_before_pph AS hpyemtd.terima_lain' ),
				
				// Field::inst( 'IF( IFNULL(hpyemtd.premi_abs, 0) - IFNULL(hpyemtd_cocokan.premi_abs, 0) = 0, 0, IFNULL(hpyemtd.premi_abs, 0) ) - hpyemtd_cocokan.total_rp_lembur + hpyemtd.total_rp_lembur + hpyemtd_cocokan.pot_upah - hpyemtd.pot_jam - hpyemtd.pot_lain_before_pph AS hpyemtd.terima_lain' ),

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
				Field::inst( 'hpyemtd.c_pot_resign' ),
				Field::inst( 'hpyemtd.c_pot_jam' ),

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
				Field::inst( 'gtxpkmh.nama' ),
				Field::inst( 'hemdcmh.ktp_no' ),
				Field::inst( 'hemdcmh.npwp_no' ),
				
				Field::inst( 'hemxxmh.kode as kode' ),
				Field::inst( 'hemxxmh.nama as nama' ),
				Field::inst( 'hemxxmh.gender' ),

				Field::inst( 
    '( 
        IF( IFNULL(hpyemtd.premi_abs, 0) - IFNULL(hpyemtd_cocokan.premi_abs, 0) = 0, 0, IFNULL(hpyemtd.premi_abs, 0) ) 
        - IFNULL(hpyemtd_cocokan.total_rp_lembur, 0) 
        + IFNULL(hpyemtd.total_rp_lembur, 0) 
        + IF(ABS(IFNULL(hpyemtd_cocokan.pot_upah, 0) - IFNULL(hpyemtd.pot_upah, 0)) > 2, IFNULL(hpyemtd_cocokan.pot_upah, 0), 0) 
        - IFNULL(hpyemtd.pot_jam, 0) 
        - IFNULL(hpyemtd.pot_lain_before_pph, 0)
    ) AS terima_lain' 
)
->set( false ),

				Field::inst( 'hevgrmh.nama' ),
				Field::inst( 'hobxxmh.nama' ),
				Field::inst( 'hovxxmh.nama' ),
				Field::inst( 'hosxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hpyemtd_cocokan.id_hemxxmh' )
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
			
			->leftJoin( 'hemdcmh','hemdcmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'gtxpkmh','gtxpkmh.id','=','hemdcmh.id_gtxpkmh' )

			// ->leftJoin( 'hpyemtd','hpyemtd.id_hpyxxth = hpyemtd_cocokan.id_hpyxxth AND hpyemtd.id_hemxxmh = hpyemtd_cocokan.id_hemxxmh','','' )
			->leftJoin( 'hpyemtd','hpyemtd.id_hpyxxth = hpyemtd_cocokan.id_hpyxxth AND hpyemtd.id_hemxxmh = hpyemtd_cocokan.id_hemxxmh','','' )

			->where('hpyemtd_cocokan.id_hpyxxth',$_POST['id_hpyxxth'])
			->where('heyxxmd.id', 3)
			->where('hesxxmh.id', 1) //tetap
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hpyemtd_cocokan.is_active', 1);
		}
		
		include( "hpyemtd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>