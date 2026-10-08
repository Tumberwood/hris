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
	$show_inactive_status = $_POST['show_inactive_status_hemxxmh_medical_leave'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemxxmh_medical_leave' )
			->debug(true)
			->fields(
				Field::inst( 'hemxxmh_medical_leave.id' ),
				Field::inst( 'hemxxmh_medical_leave.id_hemxxmh' ),
				Field::inst( 'hemxxmh_medical_leave.kode' ),
				Field::inst( 'hemxxmh_medical_leave.nama' ),
				Field::inst( 'hemxxmh_medical_leave.keterangan' ),
				Field::inst( 'hemxxmh_medical_leave.is_active' ),
				Field::inst( 'hemxxmh_medical_leave.medical_leave' ),
				Field::inst( 'hemxxmh_medical_leave.tanggal_efektif' )
					->setFormatter( Format::ifEmpty( 0 ) )
				,
				Field::inst( 'hemxxmh_medical_leave.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemxxmh_medical_leave.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemxxmh_medical_leave.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hemxxmh_medical_leave.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemxxmh_medical_leave.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>