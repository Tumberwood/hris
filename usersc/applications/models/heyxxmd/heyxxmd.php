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
	$show_inactive_status = $_POST['show_inactive_status_heyxxmd'];
	// -----------
	
	$editor = Editor::inst( $db, 'heyxxmd' )
		->debug(true)
		->fields(
			Field::inst( 'heyxxmd.id' ),
			Field::inst( 'heyxxmd.id_heyxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'heyxxmd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'heyxxmd.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'heyxxmd.keterangan' ),
			Field::inst( 'heyxxmd.is_active' ),
			Field::inst( 'heyxxmd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'heyxxmd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'heyxxmd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'heyxxmd.is_approve' ),
			Field::inst( 'heyxxmd.is_defaultprogram' ),
			Field::inst( 'heyxxmh.nama' )
		)
		->leftJoin( 'heyxxmh','heyxxmh.id','=','heyxxmd.id_heyxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'heyxxmd.is_active', 1);
	}
	
	include( "heyxxmd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>