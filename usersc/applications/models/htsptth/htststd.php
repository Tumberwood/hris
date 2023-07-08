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
	$show_inactive_status = $_POST['show_inactive_status_htststd'];
	// -----------
	
	if ( ! isset($_POST['id_htsptth']) || ! is_numeric($_POST['id_htsptth']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htststd' )
			->debug(true)
			->fields(
				Field::inst( 'htststd.id' ),
				Field::inst( 'htststd.id_htsptth' ),
				Field::inst( 'htststd.id_htsxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htststd.kode' ),
				Field::inst( 'htststd.nama' ),
				Field::inst( 'htststd.keterangan' ),
				Field::inst( 'htststd.is_active' ),
				Field::inst( 'htststd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htststd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htststd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htststd.urutan' ),
				Field::inst( 'htststd.mulai_grup' ),
				
				Field::inst( 'htsxxmh.kode' )

			)
			->leftJoin( 'htsxxmh','htsxxmh.id','=','htststd.id_htsxxmh' )
			->where('htststd.id_htsptth',$_POST['id_htsptth']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htststd.is_active', 1);
		}
		
		include( "htststd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>