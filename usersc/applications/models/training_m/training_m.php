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
	// -----------
	
	$editor = Editor::inst( $db, 'training_m' )
		->debug(true)
		->fields(
			Field::inst( 'training_m.id' ),
			Field::inst( 'training_m.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'training_m.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'training_m.keterangan' ),
			Field::inst( 'training_m.is_active' ),
			Field::inst( 'training_m.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'training_m.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'training_m.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'training_m.is_approve' ),
			Field::inst( 'training_m.is_defaultprogram' ),
			
			Field::inst( 'files.web_path' ),
			Field::inst( 'training_m.id_files_foto' )
				->upload( Upload::inst(  $abs_us_root.$us_url_root.'usersc/files/training/__ID__.__EXTN__' )
				->db( 'files', 'id', array(
						'filename'    => Upload::DB_FILE_NAME,
						'filesize'    => Upload::DB_FILE_SIZE,
						'web_path'    => Upload::DB_WEB_PATH,
						'system_path' => Upload::DB_SYSTEM_PATH,
						'extn' 		  => Upload::DB_EXTN
					) )
					->validator( Validate::fileSize( 500000, 'Ukuran lampiran maksimal 500Kb' ) )
					->validator( Validate::fileExtensions( array( 'png', 'jpg', 'jpeg'), "Hanya boleh format png, jpg atau jpeg" ) )
				)
		)
		->leftJoin( 'files','files.id','=','training_m.id_files_foto' )
		;
	
	// do not erase
	// function show / hide inactive document
	
	include( "training_m_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>