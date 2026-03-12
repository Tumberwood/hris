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
	$show_inactive_status = $_POST['show_inactive_status_hemskmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemskmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemskmd.id' ),
				Field::inst( 'hemskmd.id_hemxxmh' ),
				Field::inst( 'hemskmd.kode' ),
				Field::inst( 'hemskmd.nama' ),
				Field::inst( 'hemskmd.keterangan' ),
				Field::inst( 'hemskmd.is_active' ),
				Field::inst( 'hemskmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemskmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemskmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hemskmd.tingkat' )
			)
			->where('hemskmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemskmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>