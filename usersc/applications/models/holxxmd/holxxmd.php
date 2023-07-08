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
	$show_inactive_status = $_POST['show_inactive_status_holxxmd'];
	// -----------
	
	$editor = Editor::inst( $db, 'holxxmd' )
		->debug(true)
		->fields(
			Field::inst( 'holxxmd.id' ),
			Field::inst( 'holxxmd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'holxxmd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'holxxmd.keterangan' ),
			Field::inst( 'holxxmd.is_active' ),
			Field::inst( 'holxxmd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'holxxmd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'holxxmd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'holxxmd.is_approve' ),
			Field::inst( 'holxxmd.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'holxxmd.is_active', 1);
	}
	
	include( "holxxmd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>