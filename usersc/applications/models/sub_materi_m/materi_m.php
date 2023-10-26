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

	// if (isset($_GET['id_sub_materi_m']) && !empty(isset($_GET['id_sub_materi_m']))) {
	// 	$id_sub_materi_m = $_GET['id_sub_materi_m'];
	// } else {
	// 	$id_sub_materi_m = 0;
	// }

	// if (isset($_POST['id_sub_materi_m']) && !empty(isset($_POST['id_sub_materi_m']))) {
	// 	$id_sub_materi_m = $_POST['id_sub_materi_m'];
	// } else {
	// 	$id_sub_materi_m = 0;
	// }
	
	// print_r($_GET['id_sub_materi_m']);
	$editor = Editor::inst( $db, 'materi_m' )
		->debug(true)
		->fields(
			Field::inst( 'materi_m.id' ),
			Field::inst( 'materi_m.id_sub_materi_m' )
			->set( Field::SET_CREATE ),
			Field::inst( 'materi_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'materi_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'materi_m.keterangan' ),
			Field::inst( 'materi_m.is_active' ),
			Field::inst( 'materi_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'materi_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'materi_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'materi_m.is_approve' ),
			Field::inst( 'materi_m.is_defaultprogram' ),
			Field::inst( 'materi_m.link_yt' )
		)
		// ->where('materi_m.id_sub_materi_m',$id_sub_materi_m)
		;
	
	// do not erase
	// function show / hide inactive document
	
	// include( "materi_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>