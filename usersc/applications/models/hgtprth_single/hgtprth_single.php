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
	$show_inactive_status = $_POST['show_inactive_status_hgtprth_single'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgtprth_single' )
		->debug(true)
		->fields(
			Field::inst( 'hgtprth_single.id' ),
			Field::inst( 'hgtprth_single.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgtprth_single.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgtprth_single.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgtprth_single.keterangan' ),
			Field::inst( 'hgtprth_single.is_active' ),
			Field::inst( 'hgtprth_single.generated_on' )
			->getFormatter( function ( $val, $data, $opts ) {
				if ($val === '0000-00-00 00:00:00' || $val === null){
					echo '';
				}else{
					return date( 'd M Y H:i:s', strtotime( $val ) );
				}
			} ),
			Field::inst( 'hgtprth_single.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgtprth_single.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgtprth_single.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgtprth_single.is_approve' ),
			Field::inst( 'hgtprth_single.is_defaultprogram' ),
			Field::inst( 'hgtprth_single.tanggal' )
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
			Field::inst( 'concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hgtprth_single.id_hemxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgtprth_single.is_active', 1);
	}
	
	if($_POST['start_date'] != '' && $_POST['end_date'] != ''){
		$editor
			->where( 'hgtprth_single.tanggal', $_POST['start_date'], '>=' )
			->where( 'hgtprth_single.tanggal', $_POST['end_date'], '<=' );
	}

	include( "hgtprth_single_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>