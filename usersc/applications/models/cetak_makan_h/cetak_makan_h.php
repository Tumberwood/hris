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
	$show_inactive_status = $_POST['show_inactive_status_cetak_makan_h'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'cetak_makan_h' )
		->debug(true)
		->fields(
			Field::inst( 'cetak_makan_h.id' ),
			Field::inst( 'cetak_makan_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'cetak_makan_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'cetak_makan_h.keterangan' ),
			Field::inst( 'cetak_makan_h.is_active' ),
			Field::inst( 'cetak_makan_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'cetak_makan_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'cetak_makan_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'cetak_makan_h.is_approve' ),
			Field::inst( 'cetak_makan_h.is_defaultprogram' ),
			
			Field::inst( 'cetak_makan_h.jumlah_orang' ),
			Field::inst( 'cetak_makan_h.tanggal' )
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
		)

		->where( 'cetak_makan_h.tanggal', $start_date, '>=' )	// disesuaikan
		->where( 'cetak_makan_h.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'cetak_makan_h.is_active', 1);
	}
	
	include( "cetak_makan_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>