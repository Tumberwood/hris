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
	$show_inactive_status = $_POST['show_inactive_status_grup_pekerjaan_m'];
	// -----------
	
	$editor = Editor::inst( $db, 'grup_pekerjaan_m' )
		->debug(true)
		->fields(
			Field::inst( 'grup_pekerjaan_m.id' ),
			Field::inst( 'grup_pekerjaan_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'grup_pekerjaan_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'grup_pekerjaan_m.keterangan' ),
			Field::inst( 'grup_pekerjaan_m.is_active' ),
			Field::inst( 'grup_pekerjaan_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'grup_pekerjaan_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'grup_pekerjaan_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'grup_pekerjaan_m.is_approve' ),
			Field::inst( 'grup_pekerjaan_m.is_defaultprogram' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'grup_pekerjaan_m.is_active', 1);
	}
	
	include( "grup_pekerjaan_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>