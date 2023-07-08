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
	$show_inactive_status = $_POST['show_inactive_status_hesxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hesxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hesxxmh.id' ),
			Field::inst( 'hesxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hesxxmh.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hesxxmh.keterangan' ),
			Field::inst( 'hesxxmh.is_active' ),
			Field::inst( 'hesxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hesxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxmh.is_approve' ),
			Field::inst( 'hesxxmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hesxxmh.is_active', 1);
	}
	
	include( "hesxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>