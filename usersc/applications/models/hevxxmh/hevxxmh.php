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
	$show_inactive_status = $_POST['show_inactive_status_hevxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hevxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hevxxmh.id' ),
			Field::inst( 'hevxxmh.id_hevgrmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hevxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hevxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hevxxmh.keterangan' ),
			Field::inst( 'hevxxmh.is_active' ),
			Field::inst( 'hevxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hevxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hevxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hevxxmh.is_approve' ),
			Field::inst( 'hevxxmh.is_defaultprogram' ),

			Field::inst( 'hevgrmh.nama' )
		)
		->leftJoin( 'hevgrmh','hevgrmh.id','=','hevxxmh.id_hevgrmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hevxxmh.is_active', 1);
	}
	
	include( "hevxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>