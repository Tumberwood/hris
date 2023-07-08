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
	$show_inactive_status = $_POST['show_inactive_status_htpxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'htpxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'htpxxmh.id' ),
			Field::inst( 'htpxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htpxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htpxxmh.keterangan' ),
			Field::inst( 'htpxxmh.is_active' ),
			Field::inst( 'htpxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htpxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htpxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htpxxmh.is_approve' ),
			Field::inst( 'htpxxmh.is_defaultprogram' ),
			Field::inst( 'htpxxmh.is_potong_gaji' ),
			Field::inst( 'htpxxmh.is_potong_premi' ),
			Field::inst( 'htpxxmh.jenis_jam' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htpxxmh.is_active', 1);
	}
	
	include( "htpxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>