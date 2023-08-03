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
	$show_inactive_status = $_POST['show_inactive_status_htsprtd'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsprtd' )
		->debug(true)
		->fields(
			Field::inst( 'htsprtd.id' ),
			Field::inst( 'htsprtd.id_hemxxmh' )
				->set( Field::SET_CREATE )
				->setValue( $_SESSION['id_hemxxmh'] ),
			Field::inst( 'htsprtd.id_files_foto' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->upload( Upload::inst(  $abs_us_root.$us_url_root.'usersc/files/__ID__.__EXTN__' )
					->db( 'files', 'id', array(
						'filename'    => Upload::DB_FILE_NAME,
						'filesize'    => Upload::DB_FILE_SIZE,
						'web_path'    => Upload::DB_WEB_PATH,
						'system_path' => Upload::DB_SYSTEM_PATH,
						'extn' 		  => Upload::DB_EXTN
					) )
					->validator( Validate::fileSize( 500000, 'Ukuran lampiran maksimal 500Kb' ) )
					->validator( Validate::fileExtensions( array( 'png', 'jpg', 'jpeg'), "Hanya boleh format png, jpg atau jpeg" ) )
				),
			Field::inst( 'htsprtd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsprtd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsprtd.keterangan' ),
			Field::inst( 'htsprtd.is_active' ),
			Field::inst( 'htsprtd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprtd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsprtd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprtd.is_approve' ),
			Field::inst( 'htsprtd.is_defaultprogram' ),
			Field::inst( 'htsprtd.tipe' ),
			Field::inst( 'htsprtd.tanggal' )
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
			Field::inst( 'htsprtd.jam' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '00:00:00' || $val === null){
						echo '';
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) ),
			Field::inst( 'htsprtd.lat' ),
			Field::inst( 'htsprtd.lng' ),
			
			Field::inst( 'CONCAT(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htsprtd.id_hemxxmh' )
		->where( 'htsprtd.id_hemxxmh', $_SESSION['id_hemxxmh'] );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsprtd.is_active', 1);
	}
	
	include( "htsprtd_ol_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>