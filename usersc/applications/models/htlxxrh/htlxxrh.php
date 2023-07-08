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
	$show_inactive_status = $_POST['show_inactive_status_htlxxrh'];
	// -----------
	
	$editor = Editor::inst( $db, 'htlxxrh' )
		->debug(true)
		->fields(
			Field::inst( 'htlxxrh.id' ),
			Field::inst( 'htlxxrh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htlxxrh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htlxxrh.keterangan' ),
			Field::inst( 'htlxxrh.is_active' ),
			Field::inst( 'htlxxrh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htlxxrh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htlxxrh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htlxxrh.is_approve' ),
			Field::inst( 'htlxxrh.is_defaultprogram' ),
			Field::inst( 'htlxxrh.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
						}else{
							return date( 'd M Y', strtotime( $val ) );
					}
				} ),
			
			Field::inst( 'htlxxmh.nama' ),

			Field::inst( 'hemxxmh.kode' ),
			Field::inst( 'hemxxmh.nama' )
		)
		->leftJoin( 'htlxxmh','htlxxmh.id','=','htlxxrh.id_htlxxmh' )
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htlxxrh.id_hemxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htlxxrh.is_active', 1);
	}

	if($_POST['id_hemxxmh'] > 0){
		$editor->where( 'htlxxrh.id_hemxxmh', $_POST['id_hemxxmh'] );
	}

	if($_POST['start_date'] != '' && $_POST['end_date'] != ''){
		$editor
			->where( 'htlxxrh.tanggal', $_POST['start_date'], '>=' )
			->where( 'htlxxrh.tanggal', $_POST['end_date'], '<=' );
	}


	
	include( "htlxxrh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>