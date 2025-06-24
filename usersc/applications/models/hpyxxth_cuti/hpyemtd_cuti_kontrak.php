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
	$show_inactive_status = $_POST['show_inactive_status_hpyemtd_cuti'];
	// -----------
	
	if ( ! isset($_POST['id_hpyxxth_cuti']) || ! is_numeric($_POST['id_hpyxxth_cuti']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hpyemtd_cuti' )
			->debug(true)
			->fields(
				Field::inst( 'hpyemtd_cuti.id' ),
				Field::inst( 'hpyemtd_cuti.id_hpyxxth_cuti' ),
				Field::inst( 'hpyemtd_cuti.id_hemxxmh' ),
				Field::inst( 'hpyemtd_cuti.id_heyxxmh' ),
				Field::inst( 'hpyemtd_cuti.kode' ),
				Field::inst( 'hpyemtd_cuti.nama' ),
				Field::inst( 'hpyemtd_cuti.keterangan' ),
				Field::inst( 'hpyemtd_cuti.is_active' ),
				Field::inst( 'hpyemtd_cuti.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_cuti.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hpyemtd_cuti.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hpyemtd_cuti.sisa_cuti' ),
				Field::inst( 'hpyemtd_cuti.kompensasi_cuti' ),
				Field::inst( 'hpyemtd_cuti.nominal' ),

				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hevxxmh.nama' ),
				Field::inst( 'heyxxmh.nama' ),
				Field::inst( 'heyxxmd.nama' ),
				Field::inst( 'hesxxmh.nama' ),
				
				Field::inst( 'hemxxmh.kode as kode' ),
				Field::inst( 'hemxxmh.nama as nama' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hpyemtd_cuti.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
			->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
			->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
			->where('hpyemtd_cuti.id_hpyxxth_cuti',$_POST['id_hpyxxth_cuti'])
			->where('heyxxmd.id', 3)
			->where('hesxxmh.id', 2) //kontrak
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hpyemtd_cuti.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>