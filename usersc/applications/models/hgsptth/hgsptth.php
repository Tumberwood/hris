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
	$show_inactive_status = $_POST['show_inactive_status_hgsptth'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgsptth' )
		->debug(true)
		->fields(
			Field::inst( 'hgsptth.id' ),
			Field::inst( 'hgsptth.id_htsptth' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgsptth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgsptth.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgsptth.keterangan' ),
			Field::inst( 'hgsptth.is_active' ),
			Field::inst( 'hgsptth.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgsptth.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth.is_approve' ),
			Field::inst( 'hgsptth.is_defaultprogram' ),
			Field::inst( 'hgsptth.tanggal_awal' )
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
			Field::inst( 'hgsptth.tanggal_akhir' )
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
			Field::inst( 'hgsptth.jumlah_siklus' ),

			Field::inst( 'htsptth.nama' )
		)
		->leftJoin( 'htsptth','htsptth.id','=','hgsptth.id_htsptth' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgsptth.is_active', 1);
	}
	
	include( "../../../helpers/kode_fn_generate_c.php" );
	include( "hgsptth_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>