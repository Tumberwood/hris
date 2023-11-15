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
	
	if ( ! isset($_GET['id_materi_m']) || ! is_numeric($_GET['id_materi_m']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'quiz_m' )
			->debug(true)
			->fields(
				Field::inst( 'quiz_m.id' ),
				Field::inst( 'quiz_m.id_materi_m' ),
				Field::inst( 'quiz_m.kode' ),
				Field::inst( 'quiz_m.nama' ),
				Field::inst( 'quiz_m.jawaban_a' ),
				Field::inst( 'quiz_m.jawaban_b' ),
				Field::inst( 'quiz_m.jawaban_c' ),
				Field::inst( 'quiz_m.jawaban_d' ),
				Field::inst( 'quiz_m.jawaban_benar' ),
				Field::inst( 'quiz_m.keterangan' ),
				Field::inst( 'quiz_m.is_active' ),
				Field::inst( 'quiz_m.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'quiz_m.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'quiz_m.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('quiz_m.id_materi_m',$_GET['id_materi_m'])
			;
		
		// include( "quiz_m_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_GET )
			->json();
	}
?>