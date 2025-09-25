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
	$show_inactive_status = $_POST['show_inactive_status_hpyemtd_2'];
	// -----------
	
	if ( ! isset($_POST['id_hpyxxth_2']) || ! is_numeric($_POST['id_hpyxxth_2']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hpyemtd_2' )
			->debug(true)
			->fields(
				Field::inst( 'hpyemtd_2.id' ),
				Field::inst( 'hpyemtd_2.jam_lembur_final' ),
				Field::inst( 'hpyemtd_2.id_hpyxxth_2' ),
				Field::inst( 'hpyemtd_2.id_hemxxmh' ),
				Field::inst( 'hpyemtd_2.id_heyxxmh' ),
				Field::inst( 'hpyemtd_2.kode' ),
				Field::inst( 'hpyemtd_2.nama' ),
				Field::inst( 'hpyemtd_2.keterangan' ),
				Field::inst( 'hpyemtd_2.is_active' ),
				Field::inst( 'hpyemtd_2.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_2.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_2.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hpyemtd_2.total_nominal' ),
				Field::inst( 'hpyemtd_2.gp' ),
				Field::inst( 'hpyemtd_2.t_jab' ),
				Field::inst( 'hpyemtd_2.premi_abs' ),
				Field::inst( 'hpyemtd_2.lembur15' ),
				Field::inst( 'hpyemtd_2.lembur2' ),
				Field::inst( 'hpyemtd_2.lembur3' ),
				Field::inst( 'hpyemtd_2.rp_lembur15' ),
				Field::inst( 'hpyemtd_2.rp_lembur2' ),
				Field::inst( 'hpyemtd_2.rp_lembur3' ),
				Field::inst( 'hpyemtd_2.jam_lembur' ),
				Field::inst( 'hpyemtd_2.lemburbersih' ),
				Field::inst( 'hpyemtd_2.pot_makan' ),
				Field::inst( 'hpyemtd_2.var_cost' ), 
				Field::inst( 'hpyemtd_2.fix_cost' ), //masa kerja
				Field::inst( 'hpyemtd_2.jkk' ), 
				Field::inst( 'hpyemtd_2.jkm' ), 
				Field::inst( 'hpyemtd_2.trm_jkkjkm' ), 
				Field::inst( 'hpyemtd_2.pendapatan_lain' ), 
				Field::inst( 'hpyemtd_2.pot_jkkjkm' ), 
				Field::inst( 'hpyemtd_2.pot_jht' ), 
				Field::inst( 'hpyemtd_2.pot_upah' ), 
				Field::inst( 'hpyemtd_2.pot_bpjs' ), 
				Field::inst( 'hpyemtd_2.pot_psiun' ), 
				Field::inst( 'hpyemtd_2.pot_pinjaman' ), 
				Field::inst( 'hpyemtd_2.pot_klaim' ), 
				Field::inst( 'hpyemtd_2.pot_denda_apd' ), 
				Field::inst( 'hpyemtd_2.pot_pph21' ), 
				Field::inst( 'hpyemtd_2.gaji_bersih' ), 
				Field::inst( 'hpyemtd_2.bulat' ), 
				Field::inst( 'hpyemtd_2.gaji_terima' ), 
				Field::inst( 'hpyemtd_2.pot_jam' ), 
				Field::inst( 'hpyemtd_2.pph21_back' ), 
				Field::inst( 'hpyemtd_2.kompensasi_ak' ),  
				Field::inst( 'hpyemtd_2.koreksi_lembur' ),
				Field::inst( 'hpyemtd_2.koreksi_status' ),
				Field::inst( 'hpyemtd_2.overtime_susulan' ),
				Field::inst( 'hpyemtd_2.sisa_cuti' ),
				Field::inst( 'hpyemtd_2.pot_lain' ),

				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hevxxmh.nama' ),
				Field::inst( 'heyxxmh.nama' ),
				Field::inst( 'heyxxmd.nama' ),
				Field::inst( 'hesxxmh.nama' ),
				
				Field::inst( 'hemxxmh.kode as kode' ),
				Field::inst( 'hemxxmh.nama as nama' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hpyemtd_2.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
			->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
			->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
			->where('hpyemtd_2.id_hpyxxth_2',$_POST['id_hpyxxth_2'])
			->where('heyxxmd.id', 3)
			->where('hesxxmh.id', 2) //kontrak
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hpyemtd_2.is_active', 1);
		}
		
		include( "hpyemtd_2_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>