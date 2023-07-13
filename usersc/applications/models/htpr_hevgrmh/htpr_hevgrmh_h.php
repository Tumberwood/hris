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
	$show_inactive_status = $_POST['show_inactive_status_hevgrmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hevgrmh' )
		->debug(true)
		->fields(
			Field::inst( 'hevgrmh.id' ),
			Field::inst( 'hevgrmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hevgrmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hevgrmh.keterangan' ),
			Field::inst( 'hevgrmh.is_active' ),
			Field::inst( 'hevgrmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hevgrmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hevgrmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hevgrmh.is_approve' ),
			Field::inst( 'hevgrmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hevgrmh.is_active', 1);
	}
	
	include( "htpr_hevgrmh_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>