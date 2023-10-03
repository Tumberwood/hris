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
	$show_inactive_status = $_POST['show_inactive_status_hemxxmh'];
	// -----------

	$editor = Editor::inst( $db, 'hemxxmh' )
		->debug(true)
		->fields(
			Field::inst( 'hemxxmh.id' ),
			Field::inst( 'hemjbmh.id_hovxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_hodxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_hosxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_hevxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_hetxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_heyxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_hesxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemjbmh.id_heyxxmd' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hemxxmh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hemxxmh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hemxxmh.keterangan' ),
			Field::inst( 'hemxxmh.kode_finger' ),
			Field::inst( 'hemxxmh.is_active' ),
			Field::inst( 'hemxxmh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hemxxmh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hemxxmh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hemxxmh.is_approve' ),
			Field::inst( 'hemxxmh.is_pot_makan' ),
			Field::inst( 'hemxxmh.is_defaultprogram' ),
			
			Field::inst( 'hemjbmh.tanggal_masuk' )
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
			Field::inst( 'hemjbmh.tanggal_keluar' )
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
			Field::inst( 'hemjbmh.grup_hk' ),
			Field::inst( 'hovxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hemdcmh.ktp_no' ),
			Field::inst( 'hosxxmh.nama' ),
			Field::inst( 'hevxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'heyxxmd.nama' ),
			Field::inst( 'hesxxmh.nama' ),
			Field::inst( 'v_hemxxmh_htsptth.pola_shift' ),
			Field::inst( 'v_hemxxmh_htsptth.grup_ke' )
		)
		->leftJoin( 'hemdcmh','hemdcmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hovxxmh','hovxxmh.id','=','hemjbmh.id_hovxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
		->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
		->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
		->leftJoin( 'v_hemxxmh_htsptth','v_hemxxmh_htsptth.id_hemxxmh','=','hemxxmh.id' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hemxxmh.is_active', 1);
	}
	
	include( "hemxxmh_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>