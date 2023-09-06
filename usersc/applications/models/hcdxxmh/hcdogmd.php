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
	$show_inactive_status = $_POST['show_inactive_status_hcdogmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdogmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdogmd.id' ),
				Field::inst( 'hcdogmd.id_hcdxxmh' ),
				Field::inst( 'hcdogmd.kode' ),
				Field::inst( 'hcdogmd.nama' ),
				Field::inst( 'hcdogmd.jenis' ),
				Field::inst( 'hcdogmd.tahun' ),
				Field::inst( 'hcdogmd.jabatan' ),
				Field::inst( 'hcdogmd.keterangan' ),
				Field::inst( 'hcdogmd.is_active' ),
				Field::inst( 'hcdogmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdogmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdogmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hcdogmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdogmd.is_active', 1);
		}
		
		include( "hcdogmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>