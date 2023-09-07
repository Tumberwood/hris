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
	$show_inactive_status = $_POST['show_inactive_status_hcddhmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcddhmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcddhmd.id' ),
				Field::inst( 'hcddhmd.id_hcdxxmh' ),
				Field::inst( 'hcddhmd.kode' ),
				Field::inst( 'hcddhmd.nama' ),
				Field::inst( 'hcddhmd.lama' ),
				Field::inst( 'hcddhmd.tahun' ),
				Field::inst( 'hcddhmd.dirawat_di' ),
				Field::inst( 'hcddhmd.keterangan' ),
				Field::inst( 'hcddhmd.is_active' ),
				Field::inst( 'hcddhmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcddhmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcddhmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hcddhmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcddhmd.is_active', 1);
		}
		
		include( "hcddhmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>