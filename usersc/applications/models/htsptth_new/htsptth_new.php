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
	$show_inactive_status = $_POST['show_inactive_status_htsptth_new'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsptth_new' )
		->debug(true)
		->fields(
			Field::inst( 'htsptth_new.id' ),
			Field::inst( 'htsptth_new.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsptth_new.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsptth_new.keterangan' ),
			Field::inst( 'htsptth_new.is_active' ),
			Field::inst( 'htsptth_new.is_tukar' ),
			Field::inst( 'htsptth_new.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth_new.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsptth_new.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsptth_new.is_approve' ),
			Field::inst( 'htsptth_new.generated_on' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00 00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y H:i:s', strtotime( $val ) );
					}
				} ),
			Field::inst( 'htsptth_new.is_defaultprogram' ),
			Field::inst( 'htsptth_new.jumlah_grup' )
		);
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsptth_new.is_active', 1);
	}
	
	include( "htsptth_new_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>