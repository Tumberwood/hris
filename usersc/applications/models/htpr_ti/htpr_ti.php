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
	$show_inactive_status = $_POST['show_inactive_status_htpr_ti'];
	// -----------
	
	$editor = Editor::inst( $db, 'htpr_ti' )
		->debug(true)
		->fields(
			Field::inst( 'htpr_ti.id' ),
			Field::inst( 'htpr_ti.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htpr_ti.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htpr_ti.keterangan' ),
			Field::inst( 'htpr_ti.is_active' ),
			Field::inst( 'htpr_ti.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_ti.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htpr_ti.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htpr_ti.is_approve' ),
			Field::inst( 'htpr_ti.tanggal_efektif' )
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
			Field::inst( 'htpr_ti.is_defaultprogram' ),
			Field::inst( 'htpr_ti.menit' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htpr_ti.is_active', 1);
	}
	
	include( "htpr_ti_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>