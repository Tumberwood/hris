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
	$show_inactive_status = $_POST['show_inactive_status_hpcxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpcxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hpcxxmh.id' ),
			Field::inst( 'hpcxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpcxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpcxxmh.keterangan' ),
			Field::inst( 'hpcxxmh.is_active' ),
			Field::inst( 'hpcxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpcxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpcxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpcxxmh.is_approve' ),
			Field::inst( 'hpcxxmh.is_defaultprogram' ),

			Field::inst( 'hpcxxmh.jenis' ),
			Field::inst( 'hpcxxmh.periode' ),
			Field::inst( 'hpcxxmh.nominal' ),
			Field::inst( 'hpcxxmh.is_lain' ),
			Field::inst( 'hpcxxmh.is_fix' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpcxxmh.is_active', 1);
	}
	
	include( "hpcxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>