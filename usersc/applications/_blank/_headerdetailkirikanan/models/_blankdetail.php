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
	$show_inactive_status = $_POST['show_inactive_status__blankdetail'];
	// -----------
	
	if ( ! isset($_POST['id__blankheader']) || ! is_numeric($_POST['id__blankheader']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, '_blankdetail' )
			->debug(true)
			->fields(
				Field::inst( '_blankdetail.id' ),
				Field::inst( '_blankdetail.id__blankheader' ),
				Field::inst( '_blankdetail.kode' ),
				Field::inst( '_blankdetail.nama' ),
				Field::inst( '_blankdetail.keterangan' ),
				Field::inst( '_blankdetail.is_active' ),
				Field::inst( '_blankdetail.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( '_blankdetail.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( '_blankdetail.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('_blankdetail.id__blankheader',$_POST['id__blankheader']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( '_blankdetail.is_active', 1);
		}
		
		include( "_blankdetail_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>