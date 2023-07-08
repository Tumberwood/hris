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
	$show_inactive_status = $_POST['show_inactive_status_gpvxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gpvxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'gpvxxmh.id' ),
			Field::inst( 'gpvxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gpvxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'gpvxxmh.keterangan' ),
			Field::inst( 'gpvxxmh.is_active' ),
			Field::inst( 'gpvxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gpvxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gpvxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gpvxxmh.is_approve' ),
			Field::inst( 'gpvxxmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gpvxxmh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>