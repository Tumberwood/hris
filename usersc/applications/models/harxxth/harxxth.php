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
	$show_inactive_status = $_POST['show_inactive_status_harxxth'];
	// -----------
	
	$editor = Editor::inst( $db, 'harxxth' )
		->debug(true)
		->fields(
			Field::inst( 'harxxth.id' ),
			Field::inst( 'harxxth.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hovxxmh_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hovxxmh_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hodxxmh_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hodxxmh_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hosxxmh_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hosxxmh_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hevxxmh_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hevxxmh_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),

			Field::inst( 'harxxth.id_hetxxmh_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_hetxxmh_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				
			Field::inst( 'harxxth.id_holxxmd_2_awal' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.id_holxxmd_2_akhir' )
				->setFormatter( Format::ifEmpty( 0 ) ),

			Field::inst( 'harxxth.id_files_dokumen' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'harxxth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'harxxth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'harxxth.keterangan' ),
			Field::inst( 'harxxth.is_active' ),
			Field::inst( 'harxxth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'harxxth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'harxxth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'harxxth.is_approve' ),
			Field::inst( 'harxxth.is_defaultprogram' ),
			Field::inst( 'harxxth.tanggal_efektif' )
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
			Field::inst( 'CONCAT(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','harxxth.id_hemxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'harxxth.is_active', 1);
	}
	
	include( "harxxth_extra.php" );
	include( "../../../helpers/kode_fn_generate_c.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>