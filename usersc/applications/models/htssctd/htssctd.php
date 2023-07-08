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
	$show_inactive_status = $_POST['show_inactive_status_htssctd'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date   = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htssctd' )
		->debug(true)
		->fields(
			Field::inst( 'htssctd.id' ),
			Field::inst( 'htssctd.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htssctd.id_htsxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htssctd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htssctd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htssctd.keterangan' ),
			Field::inst( 'htssctd.is_active' ),
			Field::inst( 'htssctd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htssctd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htssctd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htssctd.is_approve' ),
			Field::inst( 'htssctd.is_defaultprogram' ),
			Field::inst( 'htssctd.tanggal' )
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

			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' ),

			Field::inst( 'htsxxmh.kode' ),
			Field::inst( 'htsxxmh.jam_awal' ),
			Field::inst( 'htsxxmh.jam_akhir' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htssctd.id_hemxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'htsxxmh','htsxxmh.id','=','htssctd.id_htsxxmh' )
		->where( 'htssctd.tanggal', $start_date , '>=' )
		->where( 'htssctd.tanggal', $end_date , '<=' );

	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htssctd.is_active', 1);
	}
	
	include( "htssctd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>