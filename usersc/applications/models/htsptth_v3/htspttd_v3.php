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
	$show_inactive_status = $_POST['show_inactive_status_htspttd_v3'];
	// -----------
	
	if ( ! isset($_POST['id_htsptth_v3']) || ! is_numeric($_POST['id_htsptth_v3']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htspttd_v3' )
			->debug(true)
			->fields(
				Field::inst( 'htspttd_v3.id' ),
				Field::inst( 'htspttd_v3.id_htsptth_v3' ),
				Field::inst( 'htspttd_v3.id_htsxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htspttd_v3.kode' ),
				Field::inst( 'htspttd_v3.nama' ),
				Field::inst( 'htspttd_v3.keterangan' ),
				Field::inst( 'htspttd_v3.is_active' ),
				Field::inst( 'htspttd_v3.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htspttd_v3.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htspttd_v3.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htspttd_v3.shift' ),
				Field::inst( 'htsxxmh.kode' )
			)
			->where('htspttd_v3.id_htsptth_v3',$_POST['id_htsptth_v3'])
			->leftJoin( 'htsxxmh','htsxxmh.id','=','htspttd_v3.id_htsxxmh' )
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htspttd_v3.is_active', 1);
		}
		
		include( "htspttd_v3_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>