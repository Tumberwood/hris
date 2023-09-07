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
	$show_inactive_status = $_POST['show_inactive_status_gtxpkmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gtxpkmh' )
		->debug(true)
		->fields(
			Field::inst( 'gtxpkmh.id' ),
			Field::inst( 'gtxpkmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gtxpkmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'gtxpkmh.keterangan' ),
			Field::inst( 'gtxpkmh.is_active' ),
			Field::inst( 'gtxpkmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gtxpkmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gtxpkmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gtxpkmh.is_approve' ),
			Field::inst( 'gtxpkmh.is_defaultprogram' ),
			Field::inst( 'gtxpkmh.amount' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gtxpkmh.is_active', 1);
	}
	
	include( "gtxpkmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>