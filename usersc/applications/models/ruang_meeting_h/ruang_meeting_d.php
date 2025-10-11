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
	$show_inactive_status = $_POST['show_inactive_status_ruang_meeting_d'];
	// -----------
	
	if ( ! isset($_POST['id_ruang_meeting_h']) || ! is_numeric($_POST['id_ruang_meeting_h']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'ruang_meeting_d' )
			->debug(true)
			->fields(
				Field::inst( 'ruang_meeting_d.id' ),
				Field::inst( 'ruang_meeting_d.id_ruang_meeting_h' ),
				Field::inst( 'ruang_meeting_d.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'ruang_meeting_d.kode' ),
				Field::inst( 'ruang_meeting_d.nama' ),
				Field::inst( 'ruang_meeting_d.keterangan' ),
				Field::inst( 'ruang_meeting_d.is_active' ),
				Field::inst( 'ruang_meeting_d.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'ruang_meeting_d.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'ruang_meeting_d.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hemxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','ruang_meeting_d.id_hemxxmh' )
			->where('ruang_meeting_d.id_ruang_meeting_h',$_POST['id_ruang_meeting_h']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'ruang_meeting_d.is_active', 1);
		}
		
		include( "ruang_meeting_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>