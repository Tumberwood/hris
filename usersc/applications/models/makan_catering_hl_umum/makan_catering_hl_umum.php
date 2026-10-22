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
	$show_inactive_status = $_POST['show_inactive_status_makan_catering_hl_umum'];
	// -----------
	
	$editor = Editor::inst( $db, 'makan_catering_hl_umum' )
		->debug(true)
		->fields(
			Field::inst( 'makan_catering_hl_umum.id' ),
			Field::inst( 'makan_catering_hl_umum.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'makan_catering_hl_umum.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'makan_catering_hl_umum.keterangan' ),
			Field::inst( 'makan_catering_hl_umum.is_active' ),
			Field::inst( 'makan_catering_hl_umum.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_catering_hl_umum.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'makan_catering_hl_umum.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_catering_hl_umum.is_approve' ),
			Field::inst( 'makan_catering_hl_umum.is_defaultprogram' ),
			Field::inst( 'makan_catering_hl_umum.shift' ),
			Field::inst( 'makan_catering_hl_umum.jumlah' ),
			Field::inst( 'makan_catering_hl_umum.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) ),
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'makan_catering_hl_umum.is_active', 1);
	}
	
	include( "makan_catering_hl_umum_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>