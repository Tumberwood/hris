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
	$show_inactive_status = $_POST['show_inactive_status_hcdtrmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdtrmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdtrmd.id' ),
				Field::inst( 'hcdtrmd.id_files' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->upload( Upload::inst(  $abs_us_root.$us_url_root.'usersc/files/kandidat/__ID__.__EXTN__' )
					->db( 'files', 'id', array(
						'filename'    => Upload::DB_FILE_NAME,
						'filesize'    => Upload::DB_FILE_SIZE,
						'web_path'    => Upload::DB_WEB_PATH,
						'system_path' => Upload::DB_SYSTEM_PATH,
						'extn' 		  => Upload::DB_EXTN
					) )
					->validator( Validate::fileSize( 5000000, 'Ukuran file lampiran maksimal 2mb' ) )
					->validator( Validate::fileExtensions( array( 'pdf', 'png', 'jpg', 'jpeg' ), "Hanya diperbolehkan jenis pdf, png, jpg, atau jpeg" ) )
					),
				Field::inst( 'hcdtrmd.id_hcdxxmh' ),
				Field::inst( 'hcdtrmd.tanggal_mulai' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) ),
				Field::inst( 'hcdtrmd.tanggal_selesai' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) ),
				Field::inst( 'hcdtrmd.kode' ),
				Field::inst( 'hcdtrmd.nama' ),
				Field::inst( 'hcdtrmd.keterangan' ),
				Field::inst( 'hcdtrmd.is_active' ),
				Field::inst( 'hcdtrmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdtrmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdtrmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hcdtrmd.lembaga' ),
				Field::inst( 'hcdtrmd.biayai' ),
				Field::inst( 'hcdtrmd.bersertifikat' )
			)
			->where('hcdtrmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdtrmd.is_active', 1);
		}
		
		include( "hcdtrmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>