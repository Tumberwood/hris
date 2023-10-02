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
	$show_inactive_status = $_POST['show_inactive_status_hpyxxth'];
	// -----------
	
	$editor = Editor::inst( $db, 'hpyxxth' )
		->debug(true)
		->fields(
			Field::inst( 'hpyxxth.id' ),
			Field::inst( 'hpyxxth.id_heyxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hpyxxth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hpyxxth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hpyxxth.keterangan' ),
			Field::inst( 'hpyxxth.is_active' ),
			Field::inst( 'hpyxxth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hpyxxth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hpyxxth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hpyxxth.is_approve' ),
			Field::inst( 'hpyxxth.is_defaultprogram' ),
			Field::inst( 'hpyxxth.tanggal_awal' )
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
			
			Field::inst( 'hpyxxth.generated_on' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00 00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y H:i:s', strtotime( $val ) );
					}
				} ),
			Field::inst( 'hpyxxth.tanggal_akhir' )
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
			Field::inst( 'heyxxmh.nama' )
		)
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hpyxxth.id_heyxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hpyxxth.is_active', 1);
	}
	
	include( "hpyxxth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>