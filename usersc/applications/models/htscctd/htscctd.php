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
	$show_inactive_status = $_POST['show_inactive_status_htscctd'];
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	// -----------
	
	$editor = Editor::inst( $db, 'htscctd' )
		->debug(true)
		->fields(
			Field::inst( 'htscctd.id' ),
			Field::inst( 'htscctd.id_hemxxmh_pengaju' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htscctd.id_htsxxmh_pengaju' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htscctd.id_hemxxmh_pengganti' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htscctd.id_htsxxmh_pengganti' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htscctd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htscctd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htscctd.keterangan' ),
			Field::inst( 'htscctd.is_active' ),
			Field::inst( 'htscctd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htscctd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htscctd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htscctd.is_approve' ),
			Field::inst( 'htscctd.is_defaultprogram' ),
			Field::inst( 'htscctd.tanggal' )
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

			Field::inst( 'concat(hemxxmh_pengaju.kode, " - ",hemxxmh_pengaju.nama) as hemxxmh_pengaju' ),
			Field::inst( 'concat(hemxxmh_pengganti.kode, " - ",hemxxmh_pengganti.nama) as hemxxmh_pengganti' )

		)
		->leftJoin( 'hemxxmh as hemxxmh_pengaju','hemxxmh_pengaju.id','=','htscctd.id_hemxxmh_pengaju' )
		->leftJoin( 'hemjbmh as hemjbmh_pengaju','hemjbmh_pengaju.id_hemxxmh','=','hemxxmh_pengaju.id' )
		
		->leftJoin( 'hemxxmh as hemxxmh_pengganti','hemxxmh_pengganti.id','=','htscctd.id_hemxxmh_pengganti' )
		->leftJoin( 'hemjbmh as hemjbmh_pengganti','hemjbmh_pengganti.id_hemxxmh','=','hemxxmh_pengganti.id' )
		->where( 'htscctd.tanggal', $_POST['start_date'], '>=')
		->where( 'htscctd.tanggal', $_POST['end_date'], '<=')
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htscctd.is_active', 1);
	}
	
	include( "htscctd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>