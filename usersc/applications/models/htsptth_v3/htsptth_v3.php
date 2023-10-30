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
	$show_inactive_status = $_POST['show_inactive_status_htsptth_v3'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsptth_v3' )
		->debug(true)
		->fields(
			Field::inst( 'htsptth_v3.id' ),
			Field::inst( 'htsptth_v3.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsptth_v3.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsptth_v3.keterangan' ),
			Field::inst( 'htsptth_v3.is_active' ),
			Field::inst( 'htsptth_v3.is_tukar' ),
			Field::inst( 'htsptth_v3.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth_v3.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsptth_v3.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth_v3.is_approve' ),
			Field::inst( 'htsptth_v3.is_defaultprogram' ),
			Field::inst( 'htsptth_v3.jumlah_grup' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsptth_v3.is_active', 1);
	}
	
	include( "htsptth_v3_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>