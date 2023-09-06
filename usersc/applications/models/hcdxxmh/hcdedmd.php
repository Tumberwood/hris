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
	$show_inactive_status = $_POST['show_inactive_status_hcdedmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdedmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdedmd.id' ),
				Field::inst( 'hcdedmd.id_hcdxxmh' ),
				Field::inst( 'hcdedmd.id_gctxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hcdedmd.id_gedxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hcdedmd.kode' ),
				Field::inst( 'hcdedmd.nama' ),
				Field::inst( 'hcdedmd.keterangan' ),
				Field::inst( 'hcdedmd.is_active' ),
				Field::inst( 'hcdedmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdedmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdedmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hcdedmd.tahun_lulus' ),
				Field::inst( 'hcdedmd.jurusan' ),
				Field::inst( 'hcdedmd.nilai_akhir' ),

				Field::inst( 'gctxxmh.nama' ),
				Field::inst( 'gedxxmh.nama' )
			)
			->leftJoin( 'gctxxmh','gctxxmh.id','=','hcdedmd.id_gctxxmh' )
			->leftJoin( 'gedxxmh','gedxxmh.id','=','hcdedmd.id_gedxxmh' )
			->where('hcdedmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdedmd.is_active', 1);
		}
		
		include( "hcdedmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>