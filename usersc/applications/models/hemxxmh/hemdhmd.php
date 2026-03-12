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
	$show_inactive_status = $_POST['show_inactive_status_hemdhmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemdhmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemdhmd.id' ),
				Field::inst( 'hemdhmd.id_hemxxmh' ),
				Field::inst( 'hemdhmd.kode' ),
				Field::inst( 'hemdhmd.nama' ),
				Field::inst( 'hemdhmd.lama' ),
				Field::inst( 'hemdhmd.tahun' ),
				Field::inst( 'hemdhmd.dirawat_di' ),
				Field::inst( 'hemdhmd.keterangan' ),
				Field::inst( 'hemdhmd.is_active' ),
				Field::inst( 'hemdhmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemdhmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemdhmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hemdhmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemdhmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>