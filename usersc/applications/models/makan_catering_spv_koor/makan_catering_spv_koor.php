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
	$show_inactive_status = $_POST['show_inactive_status_makan_catering_spv_koor'];
	// -----------
	
	$editor = Editor::inst( $db, 'makan_catering_spv_koor' )
		->debug(true)
		->fields(
			Field::inst( 'makan_catering_spv_koor.id' ),
			Field::inst( 'makan_catering_spv_koor.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'makan_catering_spv_koor.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'makan_catering_spv_koor.keterangan' ),
			Field::inst( 'makan_catering_spv_koor.is_active' ),
			Field::inst( 'makan_catering_spv_koor.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_catering_spv_koor.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'makan_catering_spv_koor.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_catering_spv_koor.is_approve' ),
			Field::inst( 'makan_catering_spv_koor.is_defaultprogram' ),
			Field::inst( 'makan_catering_spv_koor.shift' ),
			Field::inst( 'makan_catering_spv_koor.jumlah' ),
			Field::inst( 'makan_catering_spv_koor.tanggal' )
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
			->where( 'makan_catering_spv_koor.is_active', 1);
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>