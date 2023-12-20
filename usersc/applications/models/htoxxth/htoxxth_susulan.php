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
	$show_inactive_status = $_POST['show_inactive_status_htoxxth'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htoxxth' )
		->debug(true)
		->fields(
			Field::inst( 'htoxxth.id' ),
			Field::inst( 'htoxxth.id_holxxmd' ),
			Field::inst( 'htoxxth.id_heyxxmh' ),
			Field::inst( 'htoxxth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htoxxth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htoxxth.keterangan' ),
			Field::inst( 'htoxxth.is_active' ),
			Field::inst( 'htoxxth.is_susulan' )
				->set( Field::SET_CREATE )
				->setValue(1),
			Field::inst( 'htoxxth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htoxxth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htoxxth.is_approve' ),
			Field::inst( 'htoxxth.is_defaultprogram' ),
			Field::inst( 'htoxxth.tanggal' )
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
			
			Field::inst( 'holxxmd.nama' ),
			Field::inst( 'heyxxmh.nama' )
		)
		->leftJoin( 'holxxmd','holxxmd.id','=','htoxxth.id_holxxmd' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','htoxxth.id_heyxxmh' )
		->where( 'htoxxth.is_susulan', 1)
		->where( 'htoxxth.tanggal', $start_date, '>=' )
		->where( 'htoxxth.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htoxxth.is_active', 1);
	}
	
	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('htoxxth.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}
	
	include( "htoxxth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	include( "../../../helpers/kode_fn_generate_c.php" );
	
	$editor
		->process( $_POST )
		->json();
?>