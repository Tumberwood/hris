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
	$show_inactive_status = $_POST['show_inactive_status_hadxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hadxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hadxxmh.id' ),
			Field::inst( 'hadxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hadxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hadxxmh.keterangan' ),
			Field::inst( 'hadxxmh.is_active' ),
			Field::inst( 'hadxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hadxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hadxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hadxxmh.is_approve' ),
			Field::inst( 'hadxxmh.is_defaultprogram' ),

			Field::inst( 'hadxxmh.masa_berlaku_hari' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hadxxmh.is_active', 1);
	}
	
	include( "hadxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>