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
	$show_inactive_status = $_POST['show_inactive_status_ggsxxsh'];
	// -----------
	
	$editor = Editor::inst( $db, 'ggsxxsh' )
		->debug(true)
		->fields(
			Field::inst( 'ggsxxsh.id' ),
			Field::inst( 'ggsxxsh.id_files_company_logo' )
                ->upload(
                    Upload::inst($abs_us_root . $us_url_root . 'usersc/files/uploads/__ID__.__EXTN__')
                    ->db('files', 'id', array(
                        'filename'    => Upload::DB_FILE_NAME,
                        'filesize'    => Upload::DB_FILE_SIZE,
                        'web_path'    => Upload::DB_WEB_PATH,
                        'system_path' => Upload::DB_SYSTEM_PATH,
                        'extn'         => Upload::DB_EXTN
                    ))
                    ->validator( Validate::fileSize( 500000, 'Ukuran foto maksimal 500kb' ) )
                    ->validator(Validate::fileExtensions(array('png', 'jpg', 'jpeg'), "Hanya diperbolehkan jenis png, jpg, atau jpeg"))
                ),
			Field::inst( 'ggsxxsh.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'ggsxxsh.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'ggsxxsh.keterangan' ),
			Field::inst( 'ggsxxsh.is_active' ),
			Field::inst( 'ggsxxsh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'ggsxxsh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'ggsxxsh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'ggsxxsh.is_approve' ),
			Field::inst( 'ggsxxsh.is_defaultprogram' ),
			Field::inst( 'ggsxxsh.posisi_logo_login' ),
			Field::inst( 'files.web_path' )
		)
        ->leftJoin( 'files','files.id','=','ggsxxsh.id_files_company_logo' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'ggsxxsh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>