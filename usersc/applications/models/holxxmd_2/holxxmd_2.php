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
	$show_inactive_status = $_POST['show_inactive_status_holxxmd_2'];
	// -----------
	
	$editor = Editor::inst( $db, 'holxxmd_2' )
		->debug(true)
		->fields(
			Field::inst( 'holxxmd_2.id' ),
			Field::inst( 'holxxmd_2.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'holxxmd_2.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'holxxmd_2.keterangan' ),
			Field::inst( 'holxxmd_2.is_active' ),
			Field::inst( 'holxxmd_2.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'holxxmd_2.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'holxxmd_2.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'holxxmd_2.is_approve' ),
			Field::inst( 'holxxmd_2.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'holxxmd_2.is_active', 1);
	}
	
	include( "holxxmd_2_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>