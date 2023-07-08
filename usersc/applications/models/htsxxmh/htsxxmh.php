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
	$show_inactive_status = $_POST['show_inactive_status_htsxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'htsxxmh.id' ),
			Field::inst( 'htsxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsxxmh.keterangan' ),
			Field::inst( 'htsxxmh.is_active' ),
			Field::inst( 'htsxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsxxmh.is_approve' ),
			Field::inst( 'htsxxmh.is_defaultprogram' ),
			Field::inst( 'htsxxmh.jam_awal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htsxxmh.jam_akhir' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htsxxmh.jam_awal_istirahat' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htsxxmh.jam_akhir_istirahat' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htsxxmh.menit_toleransi_awal_in' ),
			Field::inst( 'htsxxmh.menit_toleransi_akhir_in' ),
			Field::inst( 'htsxxmh.menit_toleransi_awal_out' ),
			Field::inst( 'htsxxmh.menit_toleransi_akhir_out' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsxxmh.is_active', 1);
	}
	
	include( "htsxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>