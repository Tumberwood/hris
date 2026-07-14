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
	$show_inactive_status = $_POST['show_inactive_status_tutorial_menu_h'];
	// -----------
	
	$editor = Editor::inst( $db, 'tutorial_menu_h' )
		->debug(true)
		->fields(
			Field::inst( 'tutorial_menu_h.id' ),
			Field::inst( 'tutorial_menu_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'tutorial_menu_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'tutorial_menu_h.keterangan' ),
			Field::inst( 'tutorial_menu_h.is_active' ),
			Field::inst( 'tutorial_menu_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'tutorial_menu_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'tutorial_menu_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'tutorial_menu_h.is_approve' ),
			Field::inst( 'tutorial_menu_h.is_defaultprogram' ),
			
		)
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'tutorial_menu_h.is_active', 1);
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>