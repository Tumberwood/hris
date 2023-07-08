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
	$show_inactive_status = $_POST['show_inactive_status_htsrptd'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsrptd' )
		->debug(true)
		->fields(
			Field::inst( 'htsrptd.id' ),
			Field::inst( 'htsrptd.id_hemxxmh_pengaju' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htsrptd.id_htsxxmh_pengaju' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htsrptd.id_hemxxmh_pengganti' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htsrptd.id_htsxxmh_pengganti' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'htsrptd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsrptd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsrptd.keterangan' ),
			Field::inst( 'htsrptd.is_active' ),
			Field::inst( 'htsrptd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsrptd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsrptd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsrptd.is_approve' ),
			Field::inst( 'htsrptd.is_defaultprogram' ),
			Field::inst( 'htsrptd.tanggal' )
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
		->leftJoin( 'hemxxmh as hemxxmh_pengaju','hemxxmh_pengaju.id','=','htsrptd.id_hemxxmh_pengaju' )
		->leftJoin( 'hemjbmh as hemjbmh_pengaju','hemjbmh_pengaju.id_hemxxmh','=','hemxxmh_pengaju.id' )
		
		->leftJoin( 'hemxxmh as hemxxmh_pengganti','hemxxmh_pengganti.id','=','htsrptd.id_hemxxmh_pengganti' )
		->leftJoin( 'hemjbmh as hemjbmh_pengganti','hemjbmh_pengganti.id_hemxxmh','=','hemxxmh_pengganti.id' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsrptd.is_active', 1);
	}
	
	include( "htsrptd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>