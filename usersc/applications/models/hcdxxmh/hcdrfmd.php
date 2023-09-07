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
	$show_inactive_status = $_POST['show_inactive_status_hcdrfmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdrfmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdrfmd.id' ),
				Field::inst( 'hcdrfmd.id_hcdxxmh' ),
				Field::inst( 'hcdrfmd.kode' ),
				Field::inst( 'hcdrfmd.nama' ),
				Field::inst( 'hcdrfmd.alamat' ),
				Field::inst( 'hcdrfmd.no_hp' ),
				Field::inst( 'hcdrfmd.pekerjaan' ),
				Field::inst( 'hcdrfmd.hubungan' ),
				Field::inst( 'hcdrfmd.keterangan' ),
				Field::inst( 'hcdrfmd.is_active' ),
				Field::inst( 'hcdrfmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdrfmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdrfmd.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hcdrfmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdrfmd.is_active', 1);
		}
		
		include( "hcdrfmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>