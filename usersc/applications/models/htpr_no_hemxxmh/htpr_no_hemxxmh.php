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
	$show_inactive_status = $_POST['show_inactive_status_htpr_no_hemxxmh'];
	// -----------
	
	$editor = Editor::inst( $db, 'htpr_no_hemxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'htpr_no_hemxxmh.id' ),
			Field::inst( 'htpr_no_hemxxmh.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpr_no_hemxxmh.id_hetxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpr_no_hemxxmh.id_hodxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htpr_no_hemxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htpr_no_hemxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htpr_no_hemxxmh.keterangan' ),
			Field::inst( 'htpr_no_hemxxmh.is_active' ),
			Field::inst( 'htpr_no_hemxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_no_hemxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htpr_no_hemxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_no_hemxxmh.is_approve' ),
			Field::inst( 'htpr_no_hemxxmh.is_defaultprogram' ),
			Field::inst( 'htpr_no_hemxxmh.tanggal' )
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
			Field::inst( 'hemxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' )

		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htpr_no_hemxxmh.id_hemxxmh' )
		->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id','LEFT' )
		->leftJoin('hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh','LEFT' )
		->leftJoin('hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh','LEFT' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htpr_no_hemxxmh.is_active', 1);
	}
	
	include( "htpr_no_hemxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>