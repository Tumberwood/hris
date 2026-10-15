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
	$show_inactive_status = $_POST['show_inactive_status_pph_tahunan_d'];
	// -----------
	
	if ( ! isset($_POST['id_pph_tahunan_h']) || ! is_numeric($_POST['id_pph_tahunan_h']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'pph_tahunan_d' )
			->debug(true)
			->fields(
				Field::inst( 'pph_tahunan_d.id' ),
				Field::inst( 'pph_tahunan_d.id_pph_tahunan_h' ),
				Field::inst( 'pph_tahunan_d.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'pph_tahunan_d.kode' ),
				Field::inst( 'pph_tahunan_d.nama' ),
				Field::inst( 'pph_tahunan_d.keterangan' ),
				Field::inst('pph_tahunan_d.is_active'),
				
				Field::inst('pph_tahunan_d.bulan'),
				Field::inst('pph_tahunan_d.bruto'),
				Field::inst('pph_tahunan_d.pph'),

				Field::inst( 'pph_tahunan_d.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'pph_tahunan_d.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'pph_tahunan_d.created_on' )
				->set( Field::SET_CREATE ),

				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hemxxmh.nama' ),
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','pph_tahunan_d.id_hemxxmh' )
			->where('pph_tahunan_d.id_pph_tahunan_h',$_POST['id_pph_tahunan_h']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'pph_tahunan_d.is_active', 1);
		}
		
		include( "pph_tahunan_d_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>