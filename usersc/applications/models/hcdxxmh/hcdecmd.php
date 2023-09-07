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
	$show_inactive_status = $_POST['show_inactive_status_hcdecmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdecmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdecmd.id' ),
				Field::inst( 'hcdecmd.id_hcdxxmh' ),
				Field::inst( 'hcdecmd.kode' ),
				Field::inst( 'hcdecmd.nama' ),
				Field::inst( 'hcdecmd.alamat' ),
				Field::inst( 'hcdecmd.no_hp' ),
				Field::inst( 'hcdecmd.hubungan' ),
				Field::inst( 'hcdecmd.keterangan' ),
				Field::inst( 'hcdecmd.is_active' ),
				Field::inst( 'hcdecmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdecmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdecmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hcdecmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdecmd.is_active', 1);
		}
		
		include( "hcdecmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>