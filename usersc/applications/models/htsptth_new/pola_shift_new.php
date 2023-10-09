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
	$show_inactive_status = $_POST['show_inactive_status_pola_shift_new'];
	// -----------
	
	if ( ! isset($_POST['id_htsptth_new']) || ! is_numeric($_POST['id_htsptth_new']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'pola_shift_new' )
			->debug(true)
			->fields(
				Field::inst( 'pola_shift_new.id' ),
				Field::inst( 'pola_shift_new.id_htsptth_new' ),
				Field::inst( 'pola_shift_new.kode' ),
				Field::inst( 'pola_shift_new.nama' ),
				Field::inst( 'pola_shift_new.keterangan' ),
				Field::inst( 'pola_shift_new.is_active' ),
				Field::inst( 'pola_shift_new.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'pola_shift_new.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'pola_shift_new.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'pola_shift_new.grup' ),
				Field::inst( 'pola_shift_new.shift' )
			)
			->where('pola_shift_new.id_htsptth_new',$_POST['id_htsptth_new']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'pola_shift_new.is_active', 1);
		}
		
		include( "pola_shift_new_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>