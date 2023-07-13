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
	$show_inactive_status = $_POST['show_inactive_status_htlxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'htlxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'htlxxmh.id' ),
			Field::inst( 'htlxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htlxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htlxxmh.keterangan' ),
			Field::inst( 'htlxxmh.is_active' ),
			Field::inst( 'htlxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htlxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htlxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htlxxmh.is_approve' ),
			Field::inst( 'htlxxmh.is_defaultprogram' ),
			Field::inst( 'htlxxmh.is_self' ),
			Field::inst( 'htlxxmh.is_potongupah' ),
			Field::inst( 'htlxxmh.is_potongcuti' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htlxxmh.is_active', 1);
	}
	
	include( "htlxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>