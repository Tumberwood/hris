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
	$show_inactive_status = $_POST['show_inactive_status_htsprrd_htlxxmh_h'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htsprrd_htlxxmh_h' )
		->debug(true)
		->fields(
			Field::inst( 'htsprrd_htlxxmh_h.id' ),
			Field::inst( 'htsprrd_htlxxmh_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsprrd_htlxxmh_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsprrd_htlxxmh_h.keterangan' ),
			Field::inst( 'htsprrd_htlxxmh_h.is_active' ),
			Field::inst( 'htsprrd_htlxxmh_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprrd_htlxxmh_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsprrd_htlxxmh_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprrd_htlxxmh_h.is_approve' ),
			Field::inst( 'htsprrd_htlxxmh_h.is_defaultprogram' ),
			Field::inst( 'htsprrd_htlxxmh_h.tanggal_awal' )
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
			Field::inst( 'htsprrd_htlxxmh_h.tanggal_akhir' )
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

		->where( 'htsprrd_htlxxmh_h.tanggal_awal', $start_date, '>=' )	// disesuaikan
		->where( 'htsprrd_htlxxmh_h.tanggal_awal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsprrd_htlxxmh_h.is_active', 1);
	}
	
	include( "htsprrd_htlxxmh_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>