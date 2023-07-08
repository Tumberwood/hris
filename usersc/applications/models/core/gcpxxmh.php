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
	$show_inactive_status = $_POST['show_inactive_status_gcpxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gcpxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'gcpxxmh.id' ),
			Field::inst( 'gcpxxmh.id_gbuxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'gcpxxmh.id_gctxxmh' )
                ->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'gcpxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'gcpxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'gcpxxmh.keterangan' ),
			Field::inst( 'gcpxxmh.is_active' ),
			Field::inst( 'gcpxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gcpxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gcpxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gcpxxmh.is_approve' ),
			Field::inst( 'gcpxxmh.is_defaultprogram' ),
			Field::inst( 'gcpxxmh.alamat' ),

            Field::inst( 'gctxxmh.nama' ),
            Field::inst( 'gbuxxmh.kode' )
		)
		->leftJoin( 'gbuxxmh','gbuxxmh.id','=','gcpxxmh.id_gbuxxmh' )
        ->leftJoin( 'gctxxmh','gctxxmh.id','=','gcpxxmh.id_gctxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gcpxxmh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>