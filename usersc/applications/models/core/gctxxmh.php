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
	$show_inactive_status = $_POST['show_inactive_status_gctxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gctxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'gctxxmh.id' ),
			Field::inst( 'gctxxmh.id_gpvxxmh' )
                ->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'gctxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gctxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'gctxxmh.keterangan' ),
			Field::inst( 'gctxxmh.is_active' ),
			Field::inst( 'gctxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gctxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gctxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gctxxmh.is_approve' ),
			Field::inst( 'gctxxmh.is_defaultprogram' ),

			Field::inst( 'gpvxxmh.nama' )
		)
        ->leftJoin( 'gpvxxmh','gpvxxmh.id','=','gctxxmh.id_gpvxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gctxxmh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>