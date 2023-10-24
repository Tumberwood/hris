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

	// if (isset($_GET['id_training_m']) && !empty(isset($_GET['id_training_m']))) {
	// 	$id_training_m = $_GET['id_training_m'];
	// } else {
	// 	$id_training_m = 0;
	// }

	// if (isset($_POST['id_training_m']) && !empty(isset($_POST['id_training_m']))) {
	// 	$id_training_m = $_POST['id_training_m'];
	// } else {
	// 	$id_training_m = 0;
	// }
	
	// print_r($_GET['id_training_m']);
	$editor = Editor::inst( $db, 'sub_training_m' )
		->debug(true)
		->fields(
			Field::inst( 'sub_training_m.id' ),
			Field::inst( 'sub_training_m.id_training_m' )
			->set( Field::SET_CREATE ),
			Field::inst( 'sub_training_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'sub_training_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'sub_training_m.keterangan' ),
			Field::inst( 'sub_training_m.is_active' ),
			Field::inst( 'sub_training_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'sub_training_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'sub_training_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'sub_training_m.is_approve' ),
			Field::inst( 'sub_training_m.is_defaultprogram' ),
			Field::inst( 'sub_training_m.link_yt' )
		)
		// ->where('sub_training_m.id_training_m',$id_training_m)
		;
	
	// do not erase
	// function show / hide inactive document
	
	// include( "sub_training_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>