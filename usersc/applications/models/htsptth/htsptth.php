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
	$show_inactive_status = $_POST['show_inactive_status_htsptth'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsptth' )
		->debug(true)
		->fields(
			Field::inst( 'htsptth.id' ),
			Field::inst( 'htsptth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsptth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsptth.keterangan' ),
			Field::inst( 'htsptth.is_active' ),
			Field::inst( 'htsptth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsptth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth.is_approve' ),
			Field::inst( 'htsptth.is_defaultprogram' ),
			Field::inst( 'htsptth.jumlah_grup' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsptth.is_active', 1);
	}
	
	include( "htsptth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>