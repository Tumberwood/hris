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
	$show_inactive_status = $_POST['show_inactive_status_hgspeth'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgspeth' )
		->debug(true)
		->fields(
			Field::inst( 'hgspeth.id' ),
			Field::inst( 'hgspeth.id_htsptth' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgspeth.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgspeth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgspeth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgspeth.keterangan' ),
			Field::inst( 'hgspeth.is_active' ),
			Field::inst( 'hgspeth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgspeth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgspeth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgspeth.is_approve' ),
			Field::inst( 'hgspeth.is_defaultprogram' ),
			Field::inst( 'hgspeth.tanggal_awal' )
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
			Field::inst( 'hgspeth.tanggal_akhir' )
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
			Field::inst( 'hgspeth.jumlah_siklus' ),

			Field::inst( 'CONCAT(hemxxmh.kode , " - " ,hemxxmh.nama) as hemxxmh_data' ),
			Field::inst( 'htsptth.nama' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','hgspeth.id_hemxxmh' )
		->leftJoin( 'htsptth','htsptth.id','=','hgspeth.id_htsptth' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgspeth.is_active', 1);
	}
	
	include( "../../../helpers/kode_fn_generate_c.php" );
	include( "hgspeth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>