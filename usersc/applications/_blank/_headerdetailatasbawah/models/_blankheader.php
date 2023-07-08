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
	$show_inactive_status = $_POST['show_inactive_status__blankheader'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, '_blankheader' )
		->debug(true)
		->fields(
			Field::inst( '_blankheader.id' ),
			Field::inst( '_blankheader.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( '_blankheader.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( '_blankheader.keterangan' ),
			Field::inst( '_blankheader.is_active' ),
			Field::inst( '_blankheader.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( '_blankheader.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( '_blankheader.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( '_blankheader.is_approve' ),
			Field::inst( '_blankheader.is_defaultprogram' )
		)
		->where( '_blankheader.tanggal', $start_date, '>=' )	// disesuaikan
		->where( '_blankheader.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( '_blankheader.is_active', 1);
	}
	
	include( "_blankheader_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>