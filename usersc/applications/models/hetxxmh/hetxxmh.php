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
	$show_inactive_status = $_POST['show_inactive_status_hetxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'hetxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hetxxmh.id' ),
			Field::inst( 'hetxxmh.id_hetxxmh_al' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hetxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hetxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hetxxmh.keterangan' ),
			Field::inst( 'hetxxmh.is_active' ),
			Field::inst( 'hetxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hetxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hetxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hetxxmh.is_approve' ),
			Field::inst( 'hetxxmh.is_defaultprogram' ),
			Field::inst( 'hetxxmh_al.nama' )
		)
		->leftJoin( 'hetxxmh as hetxxmh_al','hetxxmh_al.id','=','hetxxmh.id_hetxxmh_al' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hetxxmh.is_active', 1);
	}
	
	include( "hetxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>