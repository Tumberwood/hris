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
	$show_inactive_status = $_POST['show_inactive_status_hthkptd'];
	// -----------
	
	if ( ! isset($_POST['id_hthhdth']) || ! is_numeric($_POST['id_hthhdth']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hthkptd' )
			->debug(true)
			->fields(
				Field::inst( 'hthkptd.id' ),
				Field::inst( 'hthkptd.id_hthhdth' ),
				Field::inst( 'hthkptd.id_holxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) )
					,
				Field::inst( 'hthkptd.kode' ),
				Field::inst( 'hthkptd.nama' ),
				Field::inst( 'hthkptd.keterangan' ),
				Field::inst( 'hthkptd.is_active' ),
				Field::inst( 'hthkptd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hthkptd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hthkptd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hthkptd.nominal_kompensasi' ),
				
				Field::inst( 'holxxmh.nama' )
			)
			->leftJoin( 'holxxmh','holxxmh.id','=','hthkptd.id_holxxmh' )
			->where('hthkptd.id_hthhdth',$_POST['id_hthhdth']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hthkptd.is_active', 1);
		}
		
		include( "hthkptd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>