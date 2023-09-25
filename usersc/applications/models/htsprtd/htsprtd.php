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
	$show_inactive_status = $_POST['show_inactive_status_htsprtd'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsprtd' )
		->debug(true)
		->fields(
			Field::inst( 'htsprtd.id' ),
			Field::inst( 'htsprtd.id_hemxxmh' ),
			Field::inst( 'htsprtd.kode' ),
			Field::inst( 'htsprtd.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsprtd.keterangan' ),
			Field::inst( 'htsprtd.is_active' ),
			Field::inst( 'htsprtd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprtd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsprtd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprtd.is_approve' ),
			Field::inst( 'htsprtd.is_defaultprogram' ),
			Field::inst( 'htsprtd.tipe' )
				->set( Field::SET_CREATE )
				->setValue( 'manual' ),
			Field::inst( 'htsprtd.tanggal' )
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
			Field::inst( 'htsprtd.jam' )
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

			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh as peg','peg.id','=','htsprtd.id_hemxxmh' )
		->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','peg.id','LEFT' )
		->leftJoin( 'hemxxmh','hemxxmh.kode_finger','=','htsprtd.kode' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsprtd.is_active', 1);
	}

	//UPDATE BY FERRY , BUG FILTER 14 SEP 2023
	if($_POST['select_hemxxmh'] > 0){
		$editor->where( 'hemxxmh.id', $_POST['select_hemxxmh'] );
	}

	if($_POST['start_date'] != '' && $_POST['end_date'] != ''){
		$editor
			->where( 'htsprtd.tanggal', $_POST['start_date'], '>=' )
			->where( 'htsprtd.tanggal', $_POST['end_date'], '<=' );
	}

	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('hemjbmh.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}
	
	include( "htsprtd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>