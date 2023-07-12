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
	$show_inactive_status = $_POST['show_inactive_status_heyxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'heyxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'heyxxmh.id' ),
			Field::inst( 'heyxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'heyxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'heyxxmh.keterangan' ),
			Field::inst( 'heyxxmh.is_active' ),
			Field::inst( 'heyxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'heyxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'heyxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'heyxxmh.is_approve' ),
			Field::inst( 'heyxxmh.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'heyxxmh.is_active', 1);
	}
	
	include( "htpr_heyxxmh_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>