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
	$show_inactive_status = $_POST['show_inactive_status_hgtprth'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgtprth' )
		->debug(true)
		->fields(
			Field::inst( 'hgtprth.id' ),
			Field::inst( 'hgtprth.id_heyxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgtprth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgtprth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgtprth.keterangan' ),
			Field::inst( 'hgtprth.is_active' ),
			Field::inst( 'hgtprth.generated_on' )
			->getFormatter( function ( $val, $data, $opts ) {
				if ($val === '0000-00-00 00:00:00' || $val === null){
					echo '';
				}else{
					return date( 'd M Y H:i:s', strtotime( $val ) );
				}
			} ),
			Field::inst( 'hgtprth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgtprth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgtprth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgtprth.is_approve' ),
			Field::inst( 'hgtprth.is_defaultprogram' ),
			Field::inst( 'hgtprth.tanggal' )
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
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'v_hgtprth_htsprrd.is_approve' )
		)
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hgtprth.id_heyxxmh' )
		->leftJoin( 'v_hgtprth_htsprrd','v_hgtprth_htsprrd.id','=','hgtprth.id' )
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgtprth.is_active', 1);
	}
	
	include( "hgtprth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>