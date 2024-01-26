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
	$show_inactive_status = $_POST['show_inactive_status_hpcatmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpcatmh' )
		->debug(true)
		->fields(
			Field::inst( 'hpcatmh.id' ),
			Field::inst( 'hpcatmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpcatmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpcatmh.keterangan' ),
			Field::inst( 'hpcatmh.is_active' ),
			Field::inst( 'hpcatmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpcatmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpcatmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpcatmh.is_approve' ),
			Field::inst( 'hpcatmh.is_defaultprogram' ),
			Field::inst( 'hpcatmh.nominal_awal' ),
			Field::inst( 'hpcatmh.nominal_akhir' ),
			Field::inst( 'hpcatmh.persen' ),
			Field::inst( 'hpcatmh.kategori' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpcatmh.is_active', 1);
	}
	
	include( "hpcatmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>