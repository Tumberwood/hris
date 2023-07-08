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
	$show_inactive_status = $_POST['show_inactive_status_gbrxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gbrxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'gbrxxmh.id' ),
			Field::inst( 'gbrxxmh.id_gcpxxmh' )
                ->setFormatter( Format::ifEmpty( 0 ) ),
            Field::inst( 'gbrxxmh.id_gctxxmh' )
                ->setFormatter( Format::ifEmpty( 0 ) ),
            Field::inst( 'gbrxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gbrxxmh.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gbrxxmh.keterangan' ),
			Field::inst( 'gbrxxmh.is_active' ),
			Field::inst( 'gbrxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gbrxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gbrxxmh.is_approve' ),
			Field::inst( 'gbrxxmh.is_defaultprogram' ),
			Field::inst( 'gbrxxmh.alamat' ),
			Field::inst( 'gbrxxmh.lat' ),
			Field::inst( 'gbrxxmh.lng' ),
			
            Field::inst( 'gcpxxmh.nama' ),
			Field::inst( 'gctxxmh.nama' )
		)
        ->leftJoin( 'gcpxxmh','gcpxxmh.id','=','gbrxxmh.id_gcpxxmh' )
        ->leftJoin( 'gctxxmh','gctxxmh.id','=','gbrxxmh.id_gctxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gbrxxmh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>