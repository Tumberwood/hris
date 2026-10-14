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
	$show_inactive_status = $_POST['show_inactive_status_bpjs_d'];
	// -----------
	
	if ( ! isset($_POST['id_bpjs_h']) || ! is_numeric($_POST['id_bpjs_h']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'bpjs_d' )
			->debug(true)
			->fields(
				Field::inst( 'bpjs_d.id' ),
				Field::inst( 'bpjs_d.id_bpjs_h' ),
				Field::inst( 'bpjs_d.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'bpjs_d.kode' ),
				Field::inst( 'bpjs_d.nama' ),
				Field::inst( 'bpjs_d.keterangan' ),
				Field::inst('bpjs_d.is_active'),
				
				Field::inst('bpjs_d.jht_perusahaan'),
				Field::inst('bpjs_d.jkk'),
				Field::inst('bpjs_d.jkm'),
				Field::inst('bpjs_d.jp_perusahaan'),
				Field::inst('bpjs_d.pot_jht_karyawan'),
				Field::inst('bpjs_d.pot_jp_karyawan'),
				Field::inst('bpjs_d.bpjs_kes_perusahaan'),
				Field::inst('bpjs_d.bpjs_kes_karyawan'),

				Field::inst( 'bpjs_d.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'bpjs_d.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'bpjs_d.created_on' )
				->set( Field::SET_CREATE ),

				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hemxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','bpjs_d.id_hemxxmh' )
			->where('bpjs_d.id_bpjs_h',$_POST['id_bpjs_h']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'bpjs_d.is_active', 1);
		}
		
		include( "bpjs_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>