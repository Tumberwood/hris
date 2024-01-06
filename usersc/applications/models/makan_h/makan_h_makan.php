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
	$show_inactive_status = $_POST['show_inactive_status_makan_h'];
	// -----------
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$editor = Editor::inst( $db, 'makan_h' )
		->debug(true)
		->fields(
			Field::inst( 'makan_h.id' ),
			Field::inst( 'makan_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'makan_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} )
				->set( Field::SET_CREATE )
				->setValue('makan')
				,
			Field::inst( 'makan_h.keterangan' ),
			Field::inst( 'makan_h.is_active' ),
			Field::inst( 'makan_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'makan_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'makan_h.is_approve' ),
			Field::inst( 'makan_h.is_defaultprogram' ),
			Field::inst( 'makan_h.jumlah_ceklok_s1' ),
			Field::inst( 'makan_h.jumlah_ceklok_s2' ),
			Field::inst( 'makan_h.jumlah_ceklok_s3' ),
			Field::inst( 'makan_h.nominal_s1' ),
			Field::inst( 'makan_h.nominal_s2' ),
			Field::inst( 'makan_h.nominal_s3' ),
			Field::inst( 'makan_h.subtotal_s1' ),
			Field::inst( 'makan_h.subtotal_s2' ),
			Field::inst( 'makan_h.subtotal_s3' ),
			Field::inst( 'makan_h.subtotal_all' ),
			Field::inst( 'makan_h.tanggal' )
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
				) )
		)
		->where( 'makan_h.nama', 'makan' )
		->where( 'makan_h.tanggal', $start_date, '>=' )
		->where( 'makan_h.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'makan_h.is_active', 1);
	}
	
	include( "makan_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>