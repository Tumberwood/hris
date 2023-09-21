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
	$show_inactive_status = $_POST['show_inactive_status_htssctd_tukarhari'];
	// -----------
	
	$editor = Editor::inst( $db, 'htssctd_tukarhari' )
		->debug(true)
		->fields(
			Field::inst( 'htssctd_tukarhari.id' ),
			Field::inst( 'htssctd_tukarhari.id_hodxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htssctd_tukarhari.kode' ),
			Field::inst( 'htssctd_tukarhari.nama' ),
			Field::inst( 'htssctd_tukarhari.keterangan' ),
			Field::inst( 'htssctd_tukarhari.is_active' ),
			Field::inst( 'htssctd_tukarhari.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htssctd_tukarhari.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htssctd_tukarhari.is_approve' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'htssctd_tukarhari.tanggal_terpilih' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === "0000-00-00" || $val === null){
						echo "";
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) ),
			Field::inst( 'htssctd_tukarhari.tanggal_pengganti' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === "0000-00-00" || $val === null){
						echo "";
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) )
		)
		->leftJoin( 'hodxxmh','hodxxmh.id','=','htssctd_tukarhari.id_hodxxmh' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htssctd_tukarhari.is_active', 1);
	}
	
	include( "htssctd_tukarhari_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>