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
	$show_inactive_status = $_POST['show_inactive_status_pph_tahunan_h'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'pph_tahunan_h' )
		->debug(true)
		->fields(
			Field::inst( 'pph_tahunan_h.id' ),
			Field::inst( 'pph_tahunan_h.id_periode_payroll' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'pph_tahunan_h.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'pph_tahunan_h.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'pph_tahunan_h.keterangan' ),
			Field::inst( 'pph_tahunan_h.is_active' ),
			Field::inst( 'pph_tahunan_h.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'pph_tahunan_h.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'pph_tahunan_h.is_resign' )
				->set( Field::SET_CREATE )
				->setValue(1),
			Field::inst( 'pph_tahunan_h.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'pph_tahunan_h.is_approve' ),
			
		
			Field::inst( 'periode_payroll.tanggal_awal' )
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
			Field::inst( 'periode_payroll.tanggal_akhir' )
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
		->leftJoin( 'periode_payroll','periode_payroll.id','=','pph_tahunan_h.id_periode_payroll' )
		;	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'pph_tahunan_h.is_active', 1);
	}
	
	include( "pph_tahunan_h_extra.php" );
	include( "../../../helpers/edt_log.php" );
	// include( "../../../helpers/kode_fn_generate_c.php" );
	
	$editor
		->process( $_POST )
		->json();
?>