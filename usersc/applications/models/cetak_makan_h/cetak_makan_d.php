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
	$show_inactive_status = $_POST['show_inactive_status_cetak_makan_d'];
	// -----------
	
	if ( ! isset($_POST['id_cetak_makan_h']) || ! is_numeric($_POST['id_cetak_makan_h']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'cetak_makan_d' )
			->debug(true)
			->fields(
				Field::inst( 'cetak_makan_d.id' ),
				Field::inst( 'cetak_makan_d.id_cetak_makan_h' ),
				Field::inst( 'cetak_makan_d.kode' ),
				Field::inst( 'cetak_makan_d.nama' ),
				Field::inst( 'cetak_makan_d.perusahaan' ),
				Field::inst( 'cetak_makan_d.pic_tamu' ),
				Field::inst( 'cetak_makan_d.konfirmasi' ),
				Field::inst( 'cetak_makan_d.keterangan' ),
				Field::inst( 'cetak_makan_d.is_active' ),
				Field::inst( 'cetak_makan_d.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'cetak_makan_d.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'cetak_makan_d.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('cetak_makan_d.id_cetak_makan_h',$_POST['id_cetak_makan_h']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'cetak_makan_d.is_active', 1);
		}
		
		include( "cetak_makan_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>