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
	$show_inactive_status = $_POST['show_inactive_status_hesxxtd'];
	// -----------
	
	$editor = Editor::inst( $db, 'hesxxtd' )
		->debug(true)
		->fields(
			Field::inst( 'hesxxtd.id' ),
			Field::inst( 'hesxxtd.id_hesxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hesxxtd.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hesxxtd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hesxxtd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hesxxtd.id_hesxxmh_tetap' )
			->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hesxxtd.keterangan' ),
			Field::inst( 'hesxxtd.is_active' ),
			Field::inst( 'hesxxtd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxtd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hesxxtd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hesxxtd.is_approve' ),
			Field::inst( 'hesxxtd.is_defaultprogram' ),
			Field::inst( 'hesxxtd.tanggal_mulai' )
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
			Field::inst( 'hesxxtd.tanggal_selesai' )
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
			Field::inst( 'hesxxtd.keputusan' ),
			Field::inst( 'hesxxtd.nik_baru' ),
			Field::inst( 'hesxxtd.status_ke' ),
			Field::inst( 'hemxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hesxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' )

		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hesxxtd.id_hemxxmh' )
		->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id','LEFT' )
		->leftJoin('hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh','LEFT' )
		->leftJoin('hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh','LEFT' )
		->leftJoin('hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh','LEFT' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hesxxtd.is_active', 1);
	}
	
	include( "hesxxtd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>