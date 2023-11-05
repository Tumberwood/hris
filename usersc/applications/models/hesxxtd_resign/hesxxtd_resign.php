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
	$show_inactive_status = $_POST['show_inactive_status_hesxxtd_resign'];
	// -----------
	
	$editor = Editor::inst( $db, 'hesxxtd_resign' )
		->debug(true)
		->fields(
			Field::inst( 'hesxxtd_resign.id' ),
			Field::inst( 'hesxxtd_resign.id_harxxmh' )
				->setValue(4),
			Field::inst( 'hesxxtd_resign.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hesxxtd_resign.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hesxxtd_resign.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hesxxtd_resign.keterangan' ),
			Field::inst( 'hesxxtd_resign.is_active' ),
			Field::inst( 'hesxxtd_resign.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxtd_resign.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hesxxtd_resign.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxtd_resign.is_approve' ),
			Field::inst( 'hesxxtd_resign.tanggal_selesai' )
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
			Field::inst( 'hesxxtd_resign.is_defaultprogram' ),
			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hesxxtd_resign.id_hemxxmh' )
		->leftJoin( 'harxxmh','harxxmh.id','=','hesxxtd_resign.id_harxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hesxxtd_resign.is_active', 1);
	}
	
	include( "hesxxtd_resign_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>