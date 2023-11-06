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

				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hevxxmh.nama' ),
				Field::inst( 'heyxxmh.nama' ),
				Field::inst( 'heyxxmd.nama' ),
				Field::inst( 'hesxxmh.nama' ),
				
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hpyemtd.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
			->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
			->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
			->where('hpyemtd.id_hpyxxth',$_POST['id_hpyxxth'])
			->where('heyxxmd.id', 4);
		
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