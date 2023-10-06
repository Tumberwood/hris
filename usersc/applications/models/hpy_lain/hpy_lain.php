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
	$show_inactive_status = $_POST['show_inactive_status_hpy_lain'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpy_lain' )
		->debug(true)
		->fields(
			Field::inst( 'hpy_lain.id' ),
			Field::inst( 'hpy_lain.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpy_lain.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpy_lain.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpy_lain.keterangan' ),
			Field::inst( 'hpy_lain.is_active' ),
			Field::inst( 'hpy_lain.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_lain.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpy_lain.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpy_lain.is_approve' ),
			Field::inst( 'hpy_lain.is_defaultprogram' ),
			Field::inst( 'hpy_lain.jenis' ),
			Field::inst( 'hpy_lain.nominal' ),
			Field::inst( 'hpy_lain.tanggal' )
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

			Field::inst( 'concat(hemxxmh.kode, " - ", hemxxmh.nama, " - ", hetxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hpy_lain.id_hemxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpy_lain.is_active', 1);
	}
	
	include( "hpy_lain_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>