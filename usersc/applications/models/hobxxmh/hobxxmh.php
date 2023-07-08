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
	$show_inactive_status = $_POST['show_inactive_status_hobxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hobxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hobxxmh.id' ),
			Field::inst( 'hobxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hobxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hobxxmh.keterangan' ),
			Field::inst( 'hobxxmh.is_active' ),
			Field::inst( 'hobxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hobxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hobxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hobxxmh.is_approve' ),
			Field::inst( 'hobxxmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hobxxmh.is_active', 1);
	}
	
	include( "hobxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>