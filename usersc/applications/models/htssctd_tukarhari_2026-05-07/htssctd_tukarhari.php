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
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	// -----------
	
	$editor = Editor::inst( $db, 'htssctd_tukarhari' )
		->debug(true)
		->fields(
			Field::inst( 'htssctd_tukarhari.id' ),
			Field::inst( 'htssctd_tukarhari.id_hosxxmh' )
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
		->join(
			Mjoin::inst( 'hosxxmh' )
				->link( 'htssctd_tukarhari.id', 'hosxxmh_htssctd_tukarhari.id_htssctd_tukarhari' )
				->link( 'hosxxmh.id', 'hosxxmh_task_report.id_hosxxmh' )
				->order( 'hosxxmh.nama asc' )
				->fields(
					Field::inst( 'id' )
                    	->validator( 'Validate::required' )
						->options( Options::inst()
							->table( 'hosxxmh' )
							->value( 'id' )
							->label( 'nama' )
						),
					Field::inst( 'id' ),
					Field::inst( 'nama' )
				)
		)
		->where( 'htssctd_tukarhari.tanggal_terpilih', $_POST['start_date'], '>=')
		->where( 'htssctd_tukarhari.tanggal_terpilih', $_POST['end_date'], '<=')
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