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
	$show_inactive_status = $_POST['show_inactive_status_pekerjaan_m'];
	// -----------
	
	$editor = Editor::inst( $db, 'pekerjaan_m' )
		->debug(true)
		->fields(
			Field::inst( 'pekerjaan_m.id' ),
			Field::inst( 'pekerjaan_m.id_grup_pekerjaan_m' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'pekerjaan_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'pekerjaan_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'pekerjaan_m.keterangan' ),
			Field::inst( 'pekerjaan_m.is_active' ),
			Field::inst( 'pekerjaan_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'pekerjaan_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'pekerjaan_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'pekerjaan_m.is_approve' ),
			Field::inst( 'pekerjaan_m.is_defaultprogram' ),
			Field::inst( 'grup_pekerjaan_m.nama' ),
		)
		->leftJoin( 'grup_pekerjaan_m','grup_pekerjaan_m.id','=','pekerjaan_m.id_grup_pekerjaan_m' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'pekerjaan_m.is_active', 1);
	}
	
	include( "pekerjaan_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>