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
	$show_inactive_status = $_POST['show_inactive_status_rencana_makan'];
	// -----------
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'rencana_makan' )
		->debug(true)
		->fields(
			Field::inst( 'rencana_makan.id' ),
			Field::inst( 'rencana_makan.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'rencana_makan.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'rencana_makan.nama' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'rencana_makan.keterangan' ),
			Field::inst( 'rencana_makan.is_active' ),
			Field::inst( 'rencana_makan.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'rencana_makan.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'rencana_makan.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'rencana_makan.is_approve' ),
			Field::inst( 'rencana_makan.is_defaultprogram' ),
			Field::inst( 'rencana_makan.shift' ),
			Field::inst( 'rencana_makan.tanggal' )
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
			
			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','rencana_makan.id_hemxxmh' )
		
		->where( 'rencana_makan.tanggal', $start_date, '>=' )
		->where( 'rencana_makan.tanggal', $end_date, '<=' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'rencana_makan.is_active', 1);
	}
	
	include( "rencana_makan_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>