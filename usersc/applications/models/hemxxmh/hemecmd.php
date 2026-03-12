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
	$show_inactive_status = $_POST['show_inactive_status_hemecmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemecmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemecmd.id' ),
				Field::inst( 'hemecmd.id_hemxxmh' ),
				Field::inst( 'hemecmd.kode' ),
				Field::inst( 'hemecmd.nama' ),
				Field::inst( 'hemecmd.alamat' ),
				Field::inst( 'hemecmd.no_hp' ),
				Field::inst( 'hemecmd.hubungan' ),
				Field::inst( 'hemecmd.keterangan' ),
				Field::inst( 'hemecmd.is_active' ),
				Field::inst( 'hemecmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemecmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemecmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hemecmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemecmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>