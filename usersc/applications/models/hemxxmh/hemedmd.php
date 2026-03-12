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
	$show_inactive_status = $_POST['show_inactive_status_hemedmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemedmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemedmd.id' ),
				Field::inst( 'hemedmd.id_hemxxmh' ),
				Field::inst( 'hemedmd.id_gctxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hemedmd.id_gedxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hemedmd.kode' ),
				Field::inst( 'hemedmd.nama' ),
				Field::inst( 'hemedmd.keterangan' ),
				Field::inst( 'hemedmd.is_active' ),
				Field::inst( 'hemedmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemedmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemedmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hemedmd.tahun_lulus' ),
				Field::inst( 'hemedmd.jurusan' ),
				Field::inst( 'hemedmd.nilai_akhir' ),

				Field::inst( 'gctxxmh.nama' ),
				Field::inst( 'gedxxmh.nama' )
			)
			->leftJoin( 'gctxxmh','gctxxmh.id','=','hemedmd.id_gctxxmh' )
			->leftJoin( 'gedxxmh','gedxxmh.id','=','hemedmd.id_gedxxmh' )
			->where('hemedmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemedmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>