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
	$show_inactive_status = $_POST['show_inactive_status_gntxxsh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gntxxsh' )
		->debug(true)
		->fields(
			Field::inst( 'gntxxsh.id' ),
			Field::inst( 'gntxxsh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gntxxsh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'gntxxsh.keterangan' ),
			Field::inst( 'gntxxsh.is_active' ),
			Field::inst( 'gntxxsh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gntxxsh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gntxxsh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gntxxsh.is_approve' ),
			Field::inst( 'gntxxsh.is_defaultprogram' )
		)
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gntxxsh.is_active', 1);
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>