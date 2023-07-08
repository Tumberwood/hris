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
	$show_inactive_status = $_POST['show_inactive_status_hodxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hodxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hodxxmh.id' ),
			Field::inst( 'hodxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hodxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hodxxmh.keterangan' ),
			Field::inst( 'hodxxmh.is_active' ),
			Field::inst( 'hodxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hodxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hodxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hodxxmh.is_approve' ),
			Field::inst( 'hodxxmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hodxxmh.is_active', 1);
	}
	
	include( "hodxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>