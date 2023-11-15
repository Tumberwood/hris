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
	// -----------

	// if (isset($_GET['id_materi_m']) && !empty(isset($_GET['id_materi_m']))) {
	// 	$id_materi_m = $_GET['id_materi_m'];
	// } else {
	// 	$id_materi_m = 0;
	// }

	// if (isset($_POST['id_materi_m']) && !empty(isset($_POST['id_materi_m']))) {
	// 	$id_materi_m = $_POST['id_materi_m'];
	// } else {
	// 	$id_materi_m = 0;
	// }
	
	// print_r($_GET['id_materi_m']);
	$editor = Editor::inst( $db, 'quiz_m' )
		->debug(true)
		->fields(
			Field::inst( 'quiz_m.id' ),
			Field::inst( 'quiz_m.id_materi_m' )
			->set( Field::SET_CREATE ),
			Field::inst( 'quiz_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'quiz_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'quiz_m.keterangan' ),
			Field::inst( 'quiz_m.jawaban_a' ),
			Field::inst( 'quiz_m.jawaban_b' ),
			Field::inst( 'quiz_m.jawaban_c' ),
			Field::inst( 'quiz_m.jawaban_d' ),
			Field::inst( 'quiz_m.jawaban_benar' ),
			Field::inst( 'quiz_m.is_active' ),
			Field::inst( 'quiz_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'quiz_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'quiz_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'quiz_m.is_approve' ),
			Field::inst( 'quiz_m.is_defaultprogram' )
		)
		// ->where('quiz_m.id_materi_m',$id_materi_m)
		;
	
	// do not erase
	// function show / hide inactive document
	
	// include( "quiz_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>