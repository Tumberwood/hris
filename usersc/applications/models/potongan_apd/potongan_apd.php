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
	$show_inactive_status = $_POST['show_inactive_status_potongan_apd'];
	// -----------
	
	$editor = Editor::inst( $db, 'potongan_apd' )
		->debug(true)
		->fields(
			Field::inst( 'potongan_apd.id' ),
			Field::inst( 'potongan_apd.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'potongan_apd.id_hpcxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->setValue(103),
			Field::inst( 'potongan_apd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'potongan_apd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'potongan_apd.keterangan' ),
			Field::inst( 'potongan_apd.is_active' ),
			Field::inst( 'potongan_apd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'potongan_apd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'potongan_apd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'potongan_apd.is_approve' ),
			Field::inst( 'potongan_apd.is_defaultprogram' ),
			Field::inst( 'potongan_apd.tanggal' )
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

			Field::inst( 'hpcxxmh.nama' ),
			Field::inst( 'concat(hemxxmh.kode, " - ", hemxxmh.nama, " - ", hetxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','potongan_apd.id_hemxxmh' )
		->leftJoin( 'hpcxxmh','hpcxxmh.id','=','potongan_apd.id_hpcxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'potongan_apd.is_active', 1);
	}
	
	include( "potongan_apd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>