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
	$show_inactive_status = $_POST['show_inactive_status_hemogmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemogmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemogmd.id' ),
				Field::inst( 'hemogmd.id_hemxxmh' ),
				Field::inst( 'hemogmd.kode' ),
				Field::inst( 'hemogmd.nama' ),
				Field::inst( 'hemogmd.jenis' ),
				Field::inst( 'hemogmd.tahun' ),
				Field::inst( 'hemogmd.jabatan' ),
				Field::inst( 'hemogmd.keterangan' ),
				Field::inst( 'hemogmd.is_active' ),
				Field::inst( 'hemogmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemogmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemogmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hemogmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemogmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>