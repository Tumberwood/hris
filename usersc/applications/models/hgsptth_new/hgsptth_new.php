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
	$show_inactive_status = $_POST['show_inactive_status_hgsptth_new'];
	// -----------
	
	$editor = Editor::inst( $db, 'hgsptth_new' )
		->debug(true)
		->fields(
			Field::inst( 'hgsptth_new.id' ),
			Field::inst( 'hgsptth_new.id_htsptth_new' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'hgsptth_new.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'hgsptth_new.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'hgsptth_new.keterangan' ),
			Field::inst( 'hgsptth_new.is_active' ),
			Field::inst( 'hgsptth_new.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth_new.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'hgsptth_new.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'hgsptth_new.is_approve' ),
			Field::inst( 'hgsptth_new.is_defaultprogram' ),
			Field::inst( 'hgsptth_new.tanggal_awal' )
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
			Field::inst( 'hgsptth_new.tanggal_akhir' )
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
			Field::inst( 'hgsptth_new.dari_tanggal' )
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
			Field::inst( 'hgsptth_new.tipe' ),
			
			Field::inst( 'hgsptth_new.generated_on' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00 00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y H:i:s', strtotime( $val ) );
					}
				} ),

			Field::inst( 'htsptth.nama' )
		)
		->leftJoin( 'htsptth_new as htsptth','htsptth.id','=','hgsptth_new.id_htsptth_new' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'hgsptth_new.is_active', 1);
	}
	
	// include( "../../../helpers/kode_fn_generate_c.php" );
	include( "hgsptth_new_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>