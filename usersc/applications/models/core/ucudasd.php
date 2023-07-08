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
	$show_inactive_status = $_POST['show_inactive_status'];
	// -----------
	
	if ( ! isset($_POST['id_udpxxsh']) || ! is_numeric($_POST['id_udpxxsh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'ucudasd' )
			->debug(true)
			->fields(
				Field::inst( 'ucudasd.id' ),
				Field::inst( 'ucudasd.id_udpxxsh' ),
				Field::inst( 'ucudasd.id_pages' )
					->options( Options::inst()
						->table( 'pages' )
						->value( 'id' )
						->label( ['title'] )
						->where( function ($q) {
							$q
								->where( 'LEFT (page, 6)', 'usersc' )
								->where( 'id', '(SELECT id_pages FROM ucudasd)', 'NOT IN', false );
						})
						->order( 'title' )
					),
				Field::inst( 'ucudasd.kode' ),
				Field::inst( 'ucudasd.nama' ),
				Field::inst( 'ucudasd.keterangan' ),
				Field::inst( 'ucudasd.is_active' ),
				Field::inst( 'ucudasd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'ucudasd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'ucudasd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'ucudasd.hak_c' ),
				Field::inst( 'ucudasd.hak_r' ),
				Field::inst( 'ucudasd.hak_u' ),
				Field::inst( 'ucudasd.hak_d' ),
				Field::inst( 'ucudasd.hak_a' ),
				Field::inst( 'pages.title' )
			)
			->leftJoin( 'pages','pages.id','=','ucudasd.id_pages' )
			->where('ucudasd.id_udpxxsh',$_POST['id_udpxxsh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'ucudasd.is_active', 1);
		}
		
		// input log
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>