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
	$show_inactive_status = $_POST['show_inactive_status_service_request'];
	// -----------
	
	$editor = Editor::inst( $db, 'service_request' )
		->debug(true)
		->fields(
			Field::inst( 'service_request.id' ),
			Field::inst( 'service_request.id_pekerjaan_m' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'service_request.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'service_request.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'service_request.keterangan' ),
			Field::inst( 'service_request.is_active' ),
			Field::inst( 'service_request.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'service_request.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'service_request.is_approve' ),
			Field::inst( 'service_request.is_defaultprogram' ),
			Field::inst( 'service_request.tglrequest' )
				->getFormatter( 'Format::datetime', array(
					'from' => 'Y-m-d H:i:s',
					'to' =>   'd M Y H:i'
				) )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y H:i',
					'to' =>   'Y-m-d H:i:s'
				) ),
			Field::inst( 'pekerjaan_m.nama' ),
		)
		->leftJoin( 'pekerjaan_m','pekerjaan_m.id','=','service_request.id_pekerjaan_m' )
		->where( 'service_request.created_by', $_SESSION['user'])
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'service_request.is_active', 1);
	}
	
	include( "service_request_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>