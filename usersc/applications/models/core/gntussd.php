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
	$show_inactive_status = $_POST['show_inactive_status_gntussd'];
	// -----------
	
	if ( ! isset($_POST['id_gntxxsh']) || ! is_numeric($_POST['id_gntxxsh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'gntussd' )
			->debug(true)
			->fields(
				Field::inst( 'gntussd.id' ),
				Field::inst( 'gntussd.id_gntxxsh' ),
				Field::inst( 'gntussd.id_users_penerima' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'gntussd.kode' ),
				Field::inst( 'gntussd.nama' ),
				Field::inst( 'gntussd.keterangan' ),
				Field::inst( 'gntussd.is_active' ),
				Field::inst( 'gntussd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'gntussd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'gntussd.created_on' )
					->set( Field::SET_CREATE ),

				Field::inst( 'hemxxmh.nama' ),
				Field::inst( 'concat(users.fname, " ", users.lname) AS nama' )
					->set( Field::SET_NONE )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id_users','=','gntussd.id_users_penerima' )
			->leftJoin( 'users','users.id','=','gntussd.id_users_penerima' )
			->where('gntussd.id_gntxxsh',$_POST['id_gntxxsh'])
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'gntussd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>