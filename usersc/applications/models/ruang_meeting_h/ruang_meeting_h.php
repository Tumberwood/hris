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
	$show_inactive_status = $_POST['show_inactive_status_ruang_meeting_h'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'ruang_meeting_h' )
		->debug(true)
		->fields(
			Field::inst( 'ruang_meeting_h.id' ),
			Field::inst( 'ruang_meeting_h.id_acara_m' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'ruang_meeting_h.id_ruang_meeting_m' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'ruang_meeting_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'ruang_meeting_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'ruang_meeting_h.keterangan' ),
			Field::inst( 'ruang_meeting_h.is_active' ),
			Field::inst( 'ruang_meeting_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'ruang_meeting_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'ruang_meeting_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'ruang_meeting_h.is_approve' ),
			Field::inst( 'ruang_meeting_h.jumlah_peserta' ),
			Field::inst( 'ruang_meeting_h.is_konsumsi' ),
			Field::inst( 'ruang_meeting_h.keterangan_konsumsi' ),
			Field::inst( 'ruang_meeting_h.tanggal' )
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
			Field::inst( 'ruang_meeting_h.start' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'ruang_meeting_h.finish' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'users.fname' ),
			Field::inst( 'acara_m.nama' ),
			Field::inst( 'ruang_meeting_m.nama' ),
		)
		->leftJoin( 'users','users.id','=','ruang_meeting_h.created_by' )
		->leftJoin( 'acara_m','acara_m.id','=','ruang_meeting_h.id_acara_m' )
		->leftJoin( 'ruang_meeting_m','ruang_meeting_m.id','=','ruang_meeting_h.id_ruang_meeting_m' )
		->where( 'ruang_meeting_h.tanggal', $start_date, '>=' )	// disesuaikan
		->where( 'ruang_meeting_h.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'ruang_meeting_h.is_active', 1);
	}
	
	include( "ruang_meeting_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	include( "../../../helpers/kode_fn_generate_c.php" );
	
	$editor
		->process( $_POST )
		->json();
?>