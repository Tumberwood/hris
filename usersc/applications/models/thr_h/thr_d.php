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
	$show_inactive_status = $_POST['show_inactive_status_thr_d'];
	// -----------
	
	if ( ! isset($_POST['id_thr_h']) || ! is_numeric($_POST['id_thr_h']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'thr_d' )
			->debug(true)
			->fields(
				Field::inst( 'thr_d.id' ),
				Field::inst( 'thr_d.id_thr_h' ),
				Field::inst( 'thr_d.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'thr_d.kode' ),
				Field::inst( 'thr_d.nama' ),
				Field::inst( 'thr_d.keterangan' ),
				Field::inst('thr_d.is_active'),
				
				Field::inst('thr_d.nominal'),

				Field::inst( 'thr_d.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'thr_d.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'thr_d.created_on' )
				->set( Field::SET_CREATE ),

				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hemxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','thr_d.id_hemxxmh' )
			->where('thr_d.id_thr_h',$_POST['id_thr_h']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'thr_d.is_active', 1);
		}
		
		include( "thr_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>